<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('configs')->insert([
          'title'=>'Blog Sitesi Başlığı',
          'created_at'=>new DateTime(),
          'updated_at'=>new DateTime(),
        ]);

    }
}
