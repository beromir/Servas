<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('login'));
});

test('users are redirected to their links', function () {
    $this->actingAs(User::factory()->create());

    $response = $this->get('/');

    $response->assertRedirect(route('links.index'));
});
