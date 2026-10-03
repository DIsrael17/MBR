<?php

it('renderiza el botón como <button> o como enlace', function () {
    $this->blade('<x-ui.button type="submit">Guardar</x-ui.button>')
        ->assertSee('<button type="submit"', false)
        ->assertSee('Guardar');

    $this->blade('<x-ui.button href="/destino" variant="secondary">Ir</x-ui.button>')
        ->assertSee('<a href="/destino"', false)
        ->assertDontSee('<button', false);
});

it('muestra label y error de validación en el input', function () {
    $this->withViewErrors(['email' => 'El correo es obligatorio.'])
        ->blade('<x-ui.input name="email" label="Correo" type="email" />')
        ->assertSee('for="email"', false)
        ->assertSee('aria-invalid="true"', false)
        ->assertSee('El correo es obligatorio.');
});

it('renderiza card con encabezado y pie', function () {
    $this->blade(<<<'BLADE'
        <x-ui.card>
            <x-slot:header>Título</x-slot:header>
            Cuerpo
            <x-slot:footer>Pie</x-slot:footer>
        </x-ui.card>
        BLADE)
        ->assertSeeInOrder(['Título', 'Cuerpo', 'Pie']);
});

it('renderiza modal accesible', function () {
    $this->blade('<x-ui.modal name="confirmar" title="¿Seguro?">Contenido</x-ui.modal>')
        ->assertSee('role="dialog"', false)
        ->assertSee('aria-modal="true"', false)
        ->assertSee('¿Seguro?')
        ->assertSee('Contenido');
});
