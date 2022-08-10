<?php

namespace App\Console\Commands;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Exception;


class StartAppCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $permissions = [
            ['name' => 'show_dashboard' , 'guard_name'=> 'web'],
            ['name' => 'show_api_requests', 'guard_name'=> 'web'],


            ['name' => 'show_container', 'guard_name'=> 'web'],
            ['name' => 'edit_container', 'guard_name'=> 'web'],

            ['name' => 'show_users', 'guard_name'=> 'web'],
            ['name' => 'edit_users', 'guard_name'=> 'web'],
            ['name' => 'delete_users', 'guard_name'=> 'web'],
            ['name' => 'show_roles', 'guard_name'=> 'web'],
            ['name' => 'edit_roles', 'guard_name'=> 'web'],
            ['name' => 'delete_roles', 'guard_name'=> 'web'],
        ];
        $user = [
            'name'=> 'admin',
            'phone' => '1234',
            'password' => 'admin',
            'email' => 'example@gmail.com',
            'ip' => 1234,
            'otp' => ''
        ];
        try {
            DB::beginTransaction();
            Permission::insert($permissions);
            $admin = Role::create(['name' => 'admin']);
            $super_admin = Role::create(['name' => 'super_admin']);
            $administrator = Role::create(['name' => 'administrator']);
            $super_admin->syncPermissions(Permission::all());
            $administrator->syncPermissions(Permission::all());
            $user = User::create($user);
            $user->syncRoles([$admin,$super_admin,$administrator]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
        }
        return 0;
    }
}
