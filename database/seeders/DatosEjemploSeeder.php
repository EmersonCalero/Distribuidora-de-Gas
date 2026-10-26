<?php

namespace Database\Seeders;

use App\Models\Almacen;
use App\Models\Balon;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Proveedor;
use App\Models\Usuario;
use App\Models\Vehiculo;
use Illuminate\Database\Seeder;

class DatosEjemploSeeder extends Seeder
{
    public function run(): void
    {
        $personas = [
            ['dni' => '00000001', 'nombre' => 'Rosa', 'apellido' => 'Quispe', 'telefono' => '900000001',
             'direccion' => 'Av. Ejemplo 100', 'genero' => 'F', 'fecha_nacimiento' => '1990-01-01', 'email' => 'rosa@ejemplo.test'],
            ['dni' => '00000002', 'nombre' => 'Luis', 'apellido' => 'Mamani', 'telefono' => '900000002',
             'direccion' => 'Jr. Ejemplo 200', 'genero' => 'M', 'fecha_nacimiento' => '1985-05-05', 'email' => 'luis@ejemplo.test'],
            ['dni' => '00000003', 'nombre' => 'Ana', 'apellido' => 'Huamán', 'telefono' => '900000003',
             'direccion' => 'Calle Ejemplo 300', 'genero' => 'F', 'fecha_nacimiento' => '1992-02-02', 'email' => 'ana@ejemplo.test'],
        ];

        foreach ($personas as $p) {
            Usuario::firstOrCreate(['dni' => $p['dni']], $p);
        }

        $idRosa = Usuario::where('dni', '00000001')->value('id');
        $idLuis = Usuario::where('dni', '00000002')->value('id');
        $idAna  = Usuario::where('dni', '00000003')->value('id');

        Cliente::firstOrCreate(['usuario_id' => $idRosa], [
            'tipo_cliente' => 'residencial', 'es_frecuente' => true,
            'zona' => 'Centro', 'direccion' => 'Av. Ejemplo 100',
        ]);

        Cliente::firstOrCreate(['usuario_id' => $idAna], [
            'tipo_cliente' => 'comercial', 'es_frecuente' => false,
            'zona' => 'Norte', 'direccion' => 'Calle Ejemplo 300',
        ]);

        Empleado::firstOrCreate(['usuario_id' => $idLuis], [
            'turno' => 'manana', 'cargo' => 'Repartidor',
            'hora_ingreso' => '08:00', 'hora_salida' => '16:00',
        ]);

        Vehiculo::firstOrCreate(['placa' => 'ABC-123'], [
            'marca' => 'Hyundai', 'modelo' => 'H100', 'capacidad_balones' => 80,
        ]);

        Proveedor::firstOrCreate(['ruc' => '20000000001'], [
            'razon_social' => 'Proveedor Ejemplo SAC', 'telefono' => '900000004',
            'direccion' => 'Parque Industrial',
        ]);

        Almacen::firstOrCreate(['nombre' => 'Almacén Central'], [
            'direccion' => 'Jr. Almacén 1', 'capacidad' => 500,
        ]);

        Balon::firstOrCreate(['codigo' => 'B10'], [
            'marca' => 'Marca Ejemplo', 'capacidad_kg' => 10, 'precio_venta' => 50,
        ]);

        Balon::firstOrCreate(['codigo' => 'B15'], [
            'marca' => 'Marca Ejemplo', 'capacidad_kg' => 15, 'precio_venta' => 70,
        ]);
    }
}
