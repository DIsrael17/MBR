<?php

arch('no se dejan funciones de depuración')
    ->expect(['dd', 'dump', 'ddd', 'ray', 'var_dump'])
    ->not->toBeUsed();

arch('los controllers no usan el facade DB')
    ->expect('App\Http\Controllers')
    ->not->toUse('Illuminate\Support\Facades\DB');

arch('App\Enums solo contiene enums')
    ->expect('App\Enums')
    ->toBeEnums();

arch('los modelos extienden Eloquent Model')
    ->expect('App\Models')
    ->toExtend('Illuminate\Database\Eloquent\Model');
