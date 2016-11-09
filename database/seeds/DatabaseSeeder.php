<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\System\Collection;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        Model::unguard();

        $users = collect([
                ['name' => 'User', 'email' => 'user@user.com', 'password' => Hash::make('password')]
            ])->each(function ($user) {
                User::create($user);
            });

        Model::reguard();
    }
}
