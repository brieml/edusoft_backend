<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Contracts\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        // Roles principales
        $business = Role::create(['name' => 'ADMIN']);
        $administrator = Role::create(['name' => 'DOCENTE']);
        $reader = Role::create(['name' => 'ALUMNO']);


        // Gestión de Empresas
        Permission::create(['name' => 'business.index', 'description' => 'Visualizar listado de empresas'])->syncRoles([$administrator]);
        Permission::create(['name' => 'business.create', 'description' => 'Registrar nueva empresa'])->syncRoles([$administrator]);
        Permission::create(['name' => 'business.show', 'description' => 'Consultar detalles de empresa'])->syncRoles([$administrator]);
        Permission::create(['name' => 'business.update', 'description' => 'Modificar información de empresa'])->syncRoles([$administrator]);
        Permission::create(['name' => 'business.destroy', 'description' => 'Eliminar registro de empresa'])->syncRoles([$administrator]);


    }
}
