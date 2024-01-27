<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'id'=> 1
        ],
            [
                'name' => 'Rabeya Bosri Tamanna',
                'email' => 'rbtamanna@appnap.io',
                'password' => Hash::make('welcome'),
                'role' => Config::get('variable_constants.role.admin'),
            ]);
    }
}
