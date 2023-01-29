<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            'title' => 'new title',
            'desc' => 'new desc'
        ]);
    }
}
