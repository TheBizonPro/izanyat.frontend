<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\CreateAdmin;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    	dump('Run seeds...');
    	$CreateAdmin = new CreateAdmin;
    	$CreateAdmin->run();
    }
}
