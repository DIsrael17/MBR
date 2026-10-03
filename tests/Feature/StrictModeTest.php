<?php

use App\Models\User;
use Illuminate\Database\LazyLoadingViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('lanza excepción al acceder a una relación no cargada (lazy loading)', function () {
    User::factory()->count(2)->create();

    $user = User::query()->get()->first();

    expect(fn () => $user->notifications)->toThrow(LazyLoadingViolationException::class);
});
