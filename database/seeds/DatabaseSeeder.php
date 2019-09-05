<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        \App\User::create([
            'name'          =>  'superadministrator',
            'email'         =>  'superadministrator@app.com',
            'password'      =>  bcrypt('password'),
            'role'          =>  'superadministrator',
            'isActivated'   =>  1,
            'isFilled'      =>  1
        ]);

        \App\User::create([
            'name'          =>  'humanresource',
            'email'         =>  'hr@app.com',
            'password'      =>  bcrypt('password'),
            'role'          =>  'hr',
            'isActivated'   =>  1,
            'isFilled'      =>  1
        ]);
    }
}
