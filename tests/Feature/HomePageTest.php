<?php

it('responde 200 en la página principal', function () {
    $this->get('/')->assertOk();
});
