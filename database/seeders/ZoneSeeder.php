<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use ReflectionClass;

class ZoneSeeder extends Seeder
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
        $json = file_get_contents($localPath . '/data/seeds/zones.json');
        $zones = json_decode($json)->RECORDS;
        foreach ($zones as $zone) {
            DB::table('zones')->insertOrIgnore([
                'country_id' => $zone->country_id,
                'name' => $zone->name,
                'code' => $zone->code,
                'active' => 1,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
        }

    }
}
