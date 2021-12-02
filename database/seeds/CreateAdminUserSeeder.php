<?php
use Illuminate\Database\Seeder;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class CreateAdminUserSeeder extends Seeder
{
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {

     $user = User::create([
      'name'       => 'admin',
      'email'      => 'm.alaa@egy2d.com',
      'mobile_no'  => '01158807801',
      'password'   => bcrypt('12345678'),
      'roles_name' => ["owner"],
      'status'     => 'مفعل',
    ]);

    $role = Role::create(['name' => 'owner']);

    $permissions = Permission::pluck('id','id')->all();

    $role->syncPermissions($permissions);

    $user->assignRole([$role->id]);


  }
}
