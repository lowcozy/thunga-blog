<?php

use Illuminate\Database\Seeder;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = App\User::create([
            'name' => 'ThuNga',
            'email' => 'thangbt1307@gmail.com',
            'password' => bcrypt('thunga28091997'),
            'admin' => 1

        ]);


        App\Profile::create([
            'user_id' => $user->id,
            'avatar' => 'uploads/avatars/1.png',
            'about' => 'A dream you dream alone is only a dream. A dream you dream together is reality.',
            'facebook' => 'https://www.facebook.com/100042180217077',
            'youtube' => 'https://www.instagram.com/thunga9797'
        ]);
    }
}
