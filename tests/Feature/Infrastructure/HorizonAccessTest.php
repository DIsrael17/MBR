<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('niega /horizon a visitantes fuera de local', function () {
    $this->get('/horizon')->assertForbidden();
});

it('niega /horizon a usuarios autenticados fuera de local', function () {
    $this->actingAs(User::factory()->create())
        ->get('/horizon')
        ->assertForbidden();
});
