<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Productos;

class AcceptanceTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function root_redirects_to_product_index()
    {
        $this->browse(function (Browser $b) {
            $b->visit('/')
              ->assertPathIs('/productos');
        });
    }

    /** @test */
    public function index_displays_table_and_create_link()
    {
        $this->browse(function (Browser $b) {
            $b->visit('/productos')
              ->assertSee('Productos')
              ->assertSee('Crear Producto')
              ->clickLink('Crear Producto')
              ->assertPathIs('/productos/crear');
        });
    }

    /** @test */
    public function create_form_has_all_fields_including_file_input()
    {
        $this->browse(function (Browser $b) {
            $b->visit('/productos/crear')
              ->assertPresent('input[name="nombre"]')
              ->assertPresent('input[name="sku"]')
              ->assertPresent('input[name="precio"]')
              ->assertPresent('textarea[name="descripcion"]')
              ->assertPresent('input[type="file"][name="imagen"]')
              ->assertPresent('button[type="submit"]');
        });
    }

    /** @test */
    public function validation_errors_keep_you_on_create_page_when_empty()
    {
        $this->browse(function (Browser $b) {
            $b->visit('/productos/crear')
              ->press('Crear Producto')
              ->waitForLocation('/productos/crear')
              ->assertPathIs('/productos/crear')
              ->assertSee('Crear Producto');
        });
    }

    /** @test */
    public function validation_errors_keep_you_on_create_page_on_duplicate_sku()
    {
        Productos::create([
            'nombre'      => 'Existente',
            'sku'         => 'EXIST-001',
            'precio'      => 1000,
            'descripcion' => '...',
        ]);

        $this->browse(function (Browser $b) {
            $b->visit('/productos/crear')
              ->type('nombre', 'Nuevo')
              ->type('sku', 'EXIST-001')
              ->type('precio', '2000')
              ->press('Crear Producto')
              ->waitForLocation('/productos/crear')
              ->assertPathIs('/productos/crear')
              ->assertSee('Crear Producto');
        });
    }

    /** @test */
    public function create_new_product_successfully()
    {
        $this->browse(function (Browser $b) {
            $b->visit('/productos/crear')
              ->type('nombre', 'Producto prueba')
              ->type('sku', 'PRD-1234')
              ->type('precio', '1500')
              ->type('descripcion', 'Descripción de prueba')
              ->press('Crear Producto')
              ->waitForLocation('/productos')
              ->assertPathIs('/productos')
              ->assertSee('Producto creado exitosamente')
              ->assertSee('Producto prueba');
        });
    }

    /** @test */
    public function edit_link_prefills_the_form()
    {
        $producto = Productos::create([
            'nombre'      => 'Original',
            'sku'         => 'ORIG-123',
            'precio'      => 1234,
            'descripcion' => 'Desc original',
        ]);

        $this->browse(function (Browser $b) use ($producto) {
            $b->visit('/productos')
              ->clickLink('Editar')
              ->assertPathIs("/productos/{$producto->id}/editar")
              // en tu Blade añades un espacio tras el valor old(), por eso lo comprobamos así
              ->assertInputValue('nombre', $producto->nombre.' ')
              ->assertInputValue('sku',    $producto->sku)
              ->assertInputValue('precio', (string)$producto->precio)
              ->assertSeeIn('textarea[name="descripcion"]', $producto->descripcion);
        });
    }

    /** @test */
    public function update_product_and_see_success_flash()
    {
        $producto = Productos::create([
            'nombre'      => 'Viejo',
            'sku'         => 'VIEJO',
            'precio'      => 500,
            'descripcion' => 'Antiguo',
        ]);

        $this->browse(function (Browser $b) use ($producto) {
            $b->visit("/productos/{$producto->id}/editar")
              ->type('nombre', 'NuevoNombre')
              ->press('Actualizar el Producto')
              ->waitForLocation('/productos')
              ->assertSee('Producto actualizado exitosamente')
              ->assertSee('NuevoNombre');
        });
    }



    /** @test */
    public function delete_product_with_confirmation_and_flash()
    {
        $producto = Productos::create([
            'nombre'      => 'Para borrar',
            'sku'         => 'DEL-123',
            'precio'      => 600,
            'descripcion' => 'Borrar',
        ]);

        $this->browse(function (Browser $b) use ($producto) {
            $b->visit('/productos')
              ->clickLink('Eliminar')
              ->assertDialogOpened('¿Está seguro de eliminar este producto?')
              ->acceptDialog()
              ->waitForLocation('/productos')
              ->assertSee('Producto eliminado exitosamente')
              ->assertDontSee('Para borrar');
        });
    }

    /** @test */
    public function existing_product_shows_on_index()
    {
        // Crear un producto directamente
        $producto = Productos::create([
            'nombre'      => 'MiProducto',
            'sku'         => 'IDX-999',
            'precio'      => 777,
            'descripcion' => 'Descripción índice',
        ]);

        $this->browse(function (Browser $b) use ($producto) {
            $b->visit('/productos')
              ->assertSee($producto->nombre)
              ->assertSee($producto->sku)
              // El precio sale con $ en la tabla, así que:
              ->assertSee('$' . $producto->precio);
        });
    }
}