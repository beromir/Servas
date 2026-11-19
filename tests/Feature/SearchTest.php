<?php

use App\Models\Group;
use App\Models\Link;
use App\Models\Tag;
use App\Models\User;

test('search only returns the current user\'s links, groups and tags', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    Link::factory()->create(['title' => 'Needle mine', 'user_id' => $user->id]);
    Link::factory()->create(['title' => 'Needle theirs', 'user_id' => $otherUser->id]);
    Group::create(['title' => 'Needle group mine', 'user_id' => $user->id]);
    Group::create(['title' => 'Needle group theirs', 'user_id' => $otherUser->id]);
    Tag::create(['name' => 'needle-tag-mine', 'user_id' => $user->id]);
    Tag::create(['name' => 'needle-tag-theirs', 'user_id' => $otherUser->id]);

    $titles = $this->actingAs($user)
        ->post('/search', ['searchString' => 'needle'])
        ->assertOk()
        ->collect()
        ->pluck('title');

    expect($titles->sort()->values()->all())
        ->toBe(['Needle group mine', 'Needle mine', 'needle-tag-mine']);
});
