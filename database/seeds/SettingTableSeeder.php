<?php

use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Setting::create([

           'site_name' =>"ThuNga 's Blog",
           'contact_number' =>'0977.137.018',
           'contact_email' =>'thangbt1307@gmail.com',
           'address' =>'Ha Noi, Vietnam'


        ]);
    }
}
