<?php

use App\Models\Group;
use App\Models\Link;
use App\Models\PublicLink;
use App\Models\Tag;
use App\Models\User;

beforeEach(function () {
    $this->owner = User::factory()->create();
    $this->intruder = User::factory()->create();
});

test('users cannot rename other users\' tags', function () {
    $tag = Tag::create(['name' => 'private', 'user_id' => $this->owner->id]);

    $this->actingAs($this->intruder)
        ->put(route('tags.update', $tag), ['tagName' => 'renamed'])
        ->assertForbidden();

    expect($tag->fresh()->name)->toBe('private');
});

test('users cannot delete other users\' tags', function () {
    $tag = Tag::create(['name' => 'private', 'user_id' => $this->owner->id]);

    $this->actingAs($this->intruder)
        ->delete(route('tags.destroy', $tag))
        ->assertForbidden();

    expect($tag->fresh())->not->toBeNull();
});

test('users cannot delete other users\' links', function () {
    $link = Link::factory()->create(['user_id' => $this->owner->id]);

    $this->actingAs($this->intruder)
        ->delete(route('links.destroy', $link))
        ->assertForbidden();

    expect($link->fresh())->not->toBeNull();
});

test('users cannot delete other users\' groups', function () {
    $group = Group::create(['title' => 'Private', 'user_id' => $this->owner->id]);

    $this->actingAs($this->intruder)
        ->delete(route('groups.destroy', $group))
        ->assertForbidden();

    expect($group->fresh())->not->toBeNull();
});

test('owners can still rename and delete their own tags, links and groups', function () {
    $tag = Tag::create(['name' => 'mine', 'user_id' => $this->owner->id]);
    $link = Link::factory()->create(['user_id' => $this->owner->id]);
    $group = Group::create(['title' => 'Mine', 'user_id' => $this->owner->id]);

    $this->actingAs($this->owner);

    $this->put(route('tags.update', $tag), ['tagName' => 'renamed'])->assertOk();
    expect($tag->fresh()->name)->toBe('renamed');

    $this->delete(route('tags.destroy', $tag))->assertOk();
    $this->delete(route('links.destroy', $link))->assertOk();
    $this->delete(route('groups.destroy', $group))->assertOk();

    expect($tag->fresh())->toBeNull()
        ->and($link->fresh())->toBeNull()
        ->and($group->fresh())->toBeNull();
});

test('users cannot share other users\' groups', function () {
    $group = Group::create(['title' => 'Private', 'user_id' => $this->owner->id]);

    $this->actingAs($this->intruder)
        ->post(route('publicLinks.store'), ['groupId' => $group->id])
        ->assertForbidden();

    expect(PublicLink::count())->toBe(0);
});

test('a shared group only lists links of the group owner', function () {
    $ownerTag = Tag::create(['name' => 'private', 'user_id' => $this->owner->id]);
    $ownerLink = Link::factory()->create(['title' => 'Owner link', 'user_id' => $this->owner->id]);
    $ownerLink->attachTag($ownerTag);

    $this->actingAs($this->intruder);

    $group = Group::forceCreate([
        'title' => 'Matches the owner\'s tag',
        'user_id' => $this->intruder->id,
        'query_options' => ['containsTagsOr' => [$ownerTag->id]],
    ]);
    $intruderLink = Link::factory()->create(['title' => 'Intruder link', 'user_id' => $this->intruder->id]);
    $intruderLink->groups()->attach($group);

    $this->post(route('publicLinks.store'), ['groupId' => $group->id])->assertOk();

    $this->get(PublicLink::firstOrFail()->getLink())
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('links.data', fn ($links) => collect($links)->pluck('title')->all() === ['Intruder link']));
});

test('users cannot add their links to other users\' groups', function () {
    $group = Group::create(['title' => 'Private', 'user_id' => $this->owner->id]);

    $this->actingAs($this->intruder)
        ->post(route('links.store'), [
            'link' => 'https://example.com/spam',
            'title' => 'Spam',
            'groups' => [$group->id],
            'tags' => [],
        ]);

    $link = Link::where('title', 'Spam')->firstOrFail();

    expect($link->groups()->count())->toBe(0);
});

test('users cannot nest their groups under other users\' groups', function () {
    $foreignGroup = Group::create(['title' => 'Private', 'user_id' => $this->owner->id]);

    $this->actingAs($this->intruder)
        ->post(route('groups.store'), [
            'title' => 'Nested',
            'parentGroupId' => $foreignGroup->id,
            'orTags' => [],
            'andTags' => [],
            'notTags' => [],
        ])
        ->assertSessionHasErrors('parentGroupId');

    expect(Group::where('title', 'Nested')->exists())->toBeFalse();
});
