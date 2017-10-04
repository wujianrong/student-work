<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    protected $userCount = 100;
    protected $taskCount = 100;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        factory(App\Models\User::class, $this->userCount)->create();
        $this->call(RoleUserTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(PermissionRoleTableSeeder::class);

        $this->call(DepartmentsSeeder::class);
        $this->call(WorkTypesSeeder::class);
        $this->call(CollegesSeeder::class);
        $this->call(AssessSeeder::class);
        $this->call(MenusSeeder::class);
        $this->call(MenuRoleSeeder::class);
        factory(App\Models\Task::class, $this->taskCount)->create();
        //$this->call(TaskSeeder::class);
        $this->call(MettingsSeeder::class);
    }
}
