<?php

use App\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schemal;

class RegionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Schema::hasTable('regions')) {
            Region::firstOrCreate([
                'name_uz' => 'Toshkent',
                'name_ru' => 'Ташкент',
                'parent_id' => 0
            ]);
            Region::firstOrCreate([
                'name_uz' => 'Olmazor tumani',
                'name_ru' => 'Алмазарский район',
                'parent_id' => 1
            ]);
            Region::firstOrCreate([
                'name_uz' => 'Bektemir tumani',
                'name_ru' => 'Бектемирский район',
                'parent_id' => 1
            ]);
            Region::firstOrCreate([
                'name_uz' => 'Mirobod tumani',
                'name_ru' => 'Мирабадский район',
                'parent_id' => 1
            ]);
            Region::firstOrCreate([
                'name_uz' => 'Mirzo Ulugʻbek tumani',
                'name_ru' => 'Мирзо-Улугбекский район',
                'parent_id' => 1
            ]);
            Region::firstOrCreate([
                'name_uz' => 'Sergeli tumani',
                'name_ru' => 'Сергелийский район ',
                'parent_id' => 1
            ]);
            Region::firstOrCreate([
                'name_uz' => 'Uchtepa tumani',
                'name_ru' => 'Учтепинский район',
                'parent_id' => 1
            ]);
            Region::firstOrCreate([
                'name_uz' => 'Chilonzor tumani',
                'name_ru' => 'Чиланзарский район',
                'parent_id' => 1
            ]);
            Region::firstOrCreate([
                'name_uz' => 'Shayxontohur tumani',
                'name_ru' => 'Шайхантахурский район',
                'parent_id' => 1
            ]);
            Region::firstOrCreate([
                'name_uz' => 'Yunusobod tumani',
                'name_ru' => 'Юнусабадский район',
                'parent_id' => 1
            ]);
            Region::firstOrCreate([
                'name_uz' => 'Yakkasaroy tumani',
                'name_ru' => 'Яккасарайский район',
                'parent_id' => 1
            ]);
            Region::firstOrCreate([
                'name_uz' => 'Yashnobod tumani',
                'name_ru' => 'Яшнабадский район',
                'parent_id' => 1
            ]);

        }
    }
}
