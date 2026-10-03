<?php

it('responde 200 en la página principal con el layout público', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('lang="es"', false)
        ->assertSee('id="contenido"', false)
        ->assertSee(config('app.name'))
        ->assertSee('Encuentra tu próxima propiedad');
});
