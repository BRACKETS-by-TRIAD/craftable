<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillDefaultUserAndPermissions extends Migration
{
    protected $users;
    protected $roles;
    protected $permissions;

    public function __construct()
    {
        //Add new teams
        $this->permissions = [
            [
                'name' => 'admin',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ],
        ];

        //Add new teams
        $this->roles = [
            [
                'name' => 'Administrator',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
                'permissions' => [
                    'admin',
                ],
            ],
        ];

        //Add new teams
        $this->users = [
            [
                'first_name' => 'Administrator',
                'last_name' => 'Administrator',
                'email' => 'administrator@brackets.sk',
                'password' => Hash::make('best package ever'),
                'remember_token' => NULL,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
                'activated' => true,
                'roles' => [
                    'Administrator'
                ],
                'permissions' => [
                    //
                ],
            ],
        ];
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            foreach ($this->permissions as $permission) {
                DB::table('permissions')->insert($permission);
            }

            foreach ($this->roles as $role) {
                $permissions = $role['permissions'];
                unset($role['permissions']);

                $roleId = DB::table('roles')->insertGetId($role);

                $permissionItems = DB::table('permissions')->whereIn('name', $permissions)->get();
                foreach ($permissionItems as $permissionItem) {
                    DB::table('role_has_permissions')->insert(['permission_id' => $permissionItem->id, 'role_id' => $roleId]);
                }
            }

            foreach ($this->users as $user) {
                $roles = $user['roles'];
                unset($user['roles']);
                $permissions = $user['permissions'];
                unset($user['permissions']);

                $userId = DB::table('users')->insertGetId($user);

                $roleItems = DB::table('roles')->whereIn('name', $roles)->get();
                foreach ($roleItems as $roleItem) {
                    //TODO change the model_type
                    DB::table('model_has_roles')->insert(['role_id' => $roleItem->id, 'model_id' => $userId, 'model_type' => 'App\Models\User']);
                }

                $permissionItems = DB::table('permissions')->whereIn('name', $permissions)->get();
                foreach ($permissionItems as $permissionItem) {
                    //TODO change the model_type
                    DB::table('model_has_permissions')->insert(['permission_id' => $permissionItem->id, 'model_id' => $userId, 'model_type' => 'App\Models\User']);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::transaction(function () {
            foreach ($this->users as $user) {
                if(!empty($userItem = DB::table('users')->where('email', '=', $user['email'])->first())) {
                    DB::table('users')->where('id', '=', $userItem->id)->delete();
                    DB::table('model_has_permissions')->where('model_id', '=', $userItem->id)->where('model_type', '=', 'App\Models\User')->delete();
                    DB::table('model_has_roles')->where('model_id', '=', $userItem->id)->where('model_type', '=', 'App\Models\User')->delete();
                }
            }

            foreach ($this->roles as $role) {
                if(!empty($roleItem = DB::table('roles')->where('name', '=', $role['name'])->first())) {
                    DB::table('roles')->where('id', '=', $roleItem->id)->delete();
                    DB::table('model_has_roles')->where('role_id', '=', $roleItem->id)->delete();
                }
            }

            foreach ($this->permissions as $permission) {
                if(!empty($permissionItem = DB::table('permissions')->where('name', '=', $permission['name'])->first())) {
                    DB::table('permissions')->where('id', '=', $permissionItem->id)->delete();
                    DB::table('model_has_permissions')->where('permission_id', '=', $permissionItem->id)->delete();
                }
            }
        });
    }
}
