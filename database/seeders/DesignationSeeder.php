<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Designation;
class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Designation::insert([
            ['name'=>'Salesman'],
            ['name'=>'Customer Realation Officer'],
            ['name'=>'Sales Manager'],
            ['name'=>'Dept Manager'],
            ['name'=>'Assistant Sales manager'],
        ]);
    }
}
