<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\User;
class UserSeeder extends Seeder
{
    public function run()
    { 
        $user= new User();
        $user->cedentecodigo=1;
        $user->name='ramon';
        $user->email='rcn091@gmail.com';
        $user->password=bcrypt('barco6911');
        $user->save();

    }
}