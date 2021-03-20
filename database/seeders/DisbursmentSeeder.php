<?php

namespace Database\Seeders;

use App\Models\Disbursment;
use Illuminate\Database\Seeder;

class DisbursmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Disbursment::factory(10)->create();
    }
}
