<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('muestra el login del panel', function (string $path) {
    $this->get("/{$path}/login")->assertOk();
})->with(['admin', 'panel']);

it('redirige a visitantes al login del panel', function (string $path) {
    $this->get("/{$path}")->assertRedirect("/{$path}/login");
})->with(['admin', 'panel']);

it('niega los paneles a usuarios sin acceso fuera de local', function (string $path) {
    $this->actingAs(User::factory()->create())
        ->get("/{$path}")
        ->assertForbidden();
})->with(['admin', 'panel']);
