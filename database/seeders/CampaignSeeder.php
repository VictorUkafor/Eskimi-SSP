<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\AdvertisingCampaign;

class CampaignSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $user = User::where('email', 'johndoe@email.com')->first();

        if($user){
            for($i = 0; $i < 10; $i++) {
                AdvertisingCampaign::create([
                    'user_id' => $user->id,
                    'uid' => Str::uuid()->toString(),
                    'name' => $faker->name(),
                    'daily_budget' => 50.00,
                    'total_budget' => 100.00,
                    'date_from' => $faker->date($format = 'Y-m-d', $max = 'now'),
                    'date_to' => $faker->date($format = 'Y-m-d', $max = 'tomorrow'),
                    'creative_upload' => ''
                ]);
            }

        }
    }
}
