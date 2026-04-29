<?php

namespace Database\Seeders;

use App\Models\Role as AppRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissaoSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        if (! AppRole::where('name', 'Cozinha')->exists()) {
            AppRole::create(['name' => 'Cozinha']);
        }

        $permissoes = [
            'ver-dashboard-completo',   
            'ver-funcionarios',
            'ver-produtos',
            'ver-caixa',
            'ver-sangrias',
            'ver-ocorrencias',
            'bater-ponto',              
            'ver-registros-ponto',      
            'ver-balcao',
            'ver-mesas',
            'ver-cozinha',
            'ver-pedidos',
            'ver-taxa-entrega',
            'ver-relatorios',
            'ver-cardapio',
        ];

        foreach ($permissoes as $nome) {
            Permission::firstOrCreate(['name' => $nome, 'guard_name' => 'web']);
        }

        $mapa = [
            'Administrador' => $permissoes, 

            'Garcom' => [
                'ver-produtos',
                'ver-ocorrencias',
                'bater-ponto',
                'ver-mesas',
                'ver-pedidos',
                'ver-cardapio',
            ],

            'Caixa' => [
                'ver-produtos',
                'ver-caixa',
                'ver-sangrias',
                'ver-ocorrencias',
                'bater-ponto',
                'ver-balcao',
                'ver-pedidos',
                'ver-cardapio',
            ],

            'Atendente' => [
                'ver-ocorrencias',
                'bater-ponto',
                'ver-balcao',
                'ver-mesas',
                'ver-pedidos',
                'ver-cardapio',
            ],

            'Cozinha' => [
                'ver-ocorrencias',
                'bater-ponto',
                'ver-cozinha',
                'ver-cardapio',
            ],
        ];

        foreach ($mapa as $nomeRole => $perms) {
            $spatieRole = Role::firstOrCreate(['name' => $nomeRole, 'guard_name' => 'web']);
            $spatieRole->syncPermissions($perms);
        }

        User::with('role')->get()->each(function (User $user) {
            if ($user->role) {
                $spatieRole = Role::where('name', $user->role->name)->first();
                if ($spatieRole) {
                    $user->syncRoles([$spatieRole->name]);
                }
            }
        });

        $this->command->info('Permissões criadas e roles sincronizados com sucesso!');
    }
}
