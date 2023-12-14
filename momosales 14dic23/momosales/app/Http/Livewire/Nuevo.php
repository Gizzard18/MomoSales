<?php

namespace App\Http\Livewire;
use Livewire\Livewire;
use Tests\TestCase;
use App\Http\Livewire\CategoriaProductos;

class Nuevo extends TestCase
{
    public function testConfirmFunction()
    {
        // Instanciar el componente Livewire
        $component = Livewire::test(CategoriaProductos::class);

        // Emular un evento que dispare la función Confirm
        $component->call('Confirm', 'categoria_productos', 1); // Pasa los argumentos necesarios

        // Verificar que la función Confirm ha sido llamada
        $this->assertTrue($component->get('confirmExecuted')); // Suponiendo que tengas una propiedad confirmExecuted

        // Puedes realizar más aserciones según tu lógica de negocio
    }
}
