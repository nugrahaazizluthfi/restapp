<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Demo A',
                'email' => 'demoa@demo.id',
                'email_verified_at' => NULL,
                'password' => '$2y$10$qds4gYs0ulU8r.Yz1qfS8eKoy0PND3Am7NHoMw1l4axl6rXoJbgai',
                'remember_token' => NULL,
                'created_at' => '2019-07-08 04:02:44',
                'updated_at' => '2019-07-08 04:02:44',
                'username' => 'demoa',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Demo B',
                'email' => 'demob@demo.id',
                'email_verified_at' => NULL,
                'password' => '$2y$10$mjk7MAjey0kTOI.qinTZT.JofVtq6v9KRZAJl73DKQ/P0TEuT381O',
                'remember_token' => NULL,
                'created_at' => '2019-07-08 04:03:07',
                'updated_at' => '2019-07-08 04:03:07',
                'username' => 'demob',
            ),
        ));
        
        
    }
}