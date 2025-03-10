<?php

use App\Models\User;
use App\Models\Group;
use App\Models\Link;
use App\Repositories\GroupRepository;

test('user can retrieve own links', function () {
    $user = User::factory()
        ->create();

    $group = Group::factory()
        ->for($user)
        ->create();

    $link = Link::factory()
        ->for($user)
        ->create();

    $group->links()->attach($link);

    // set acting as user
    $this->actingAs($user);

    $links = GroupRepository::getLinks($group, filterByCurrentUser: true);

    expect($links->count())->toBe(1);
});

test('user cannot retrieve other\'s links', function () {
    $user = User::factory()
        ->create();

    $group = Group::factory()
        ->for($user)
        ->create();

    $link = Link::factory()
        ->for($user)
        ->create();

    $group->links()->attach($link);

    // set acting as user
    $this->actingAs(User::factory()->create());

    $links = GroupRepository::getLinks($group, filterByCurrentUser: true);

    expect($links->count())->toBe(0);
});
