<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use ReflectionClass;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $reflectionClass = new ReflectionClass($this);
        $localPath = Str::of($reflectionClass->getFileName())->beforeLast('database');
        $json = file_get_contents($localPath . '/data/seeds/countries.json');
        $countries = json_decode($json, true);
        foreach ($countries['RECORDS'] as $country) {
            DB::table('countries')->insertOrIgnore([
                'id' => $country['id'],
                'name' => $country['name'],
                'iso_code_2' => $country['iso_code_2'],
                'iso_code_3' => $country['iso_code_3'],
                'ue' => $country['ue'],
                'active' => 1,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
        }

    }
}
