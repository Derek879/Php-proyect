<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatosInicialesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categorias')->insert([
            ['nombre' => 'Kekes', 'descripcion' => 'Kekes artesanales'],
            ['nombre' => 'Alfajores', 'descripcion' => 'Alfajores rellenos'],
            ['nombre' => 'Cupcakes', 'descripcion' => 'Cupcakes personalizados'],
        ]);

        DB::table('clientes')->insert([
            [
                'nombre' => 'Cliente de prueba',
                'correo' => 'cliente@test.com',
                'telefono' => '999999999',
                'direccion' => 'Lima, Perú',
            ],
        ]);

        DB::table('productos')->insert([
            [
                'nombre' => 'Keke de Chocolate',
                'descripcion' => 'Keke artesanal de chocolate',
                'precio' => 35.00,
                'stock' => 10,
                'imagen' => null,
                'categoria_id' => 1,
            ],
            [
                'nombre' => 'Alfajores',
                'descripcion' => 'Caja de alfajores rellenos de manjar',
                'precio' => 18.00,
                'stock' => 20,
                'imagen' => null,
                'categoria_id' => 2,
            ],
            [
                'nombre' => 'Cupcakes',
                'descripcion' => 'Cupcakes decorados personalizados',
                'precio' => 20.00,
                'stock' => 15,
                'imagen' => null,
                'categoria_id' => 3,
            ],
        ]);
    }
}