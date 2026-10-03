<?php

it('configura el disco s3 desde variables de entorno', function () {
    expect(config('filesystems.disks.s3'))
        ->toMatchArray(['driver' => 's3'])
        ->toHaveKeys(['key', 'secret', 'region', 'bucket', 'endpoint']);
});

it('expone el disco public en /storage', function () {
    expect(config('filesystems.disks.public.url'))->toEndWith('/storage')
        ->and(config('filesystems.links'))->toHaveKey(public_path('storage'));
});
