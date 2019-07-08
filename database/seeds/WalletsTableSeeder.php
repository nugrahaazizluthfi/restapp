<?php

use Illuminate\Database\Seeder;

class WalletsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('wallets')->delete();
        
        \DB::table('wallets')->insert(array (
            0 => 
            array (
                'id' => 1,
                'balance' => 500000,
                'last_transaction' => '2019-07-08 00:00:00',
                'user_id' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'balance' => 10000000,
                'last_transaction' => '2019-07-08 00:00:00',
                'user_id' => 2,
            ),
        ));
        
        
    }
}