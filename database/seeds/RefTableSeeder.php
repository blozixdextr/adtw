<?php

use Illuminate\Database\Seeder;
use App\Models\UserProfile;
use App\Models\User;
use App\Models\Ref;
use Faker\Factory as FakerFactory;

class RefTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('refs')->truncate();
        DB::table('ref_user')->truncate();

        $bannerTypes = ['728*90', '120*600', '300*250'];
        $this->addRefs($bannerTypes, 'banner_type');

        $languages = ['English', 'Russian', 'Spanish'];
        $this->addRefs($languages, 'language');

        $games = [
            'League of Legends', 'Dota 2', 'HeartStone', 'CS:GO', 'Starcraft 2',
            'WOW', 'Destiny', 'Diablo 3', 'Super Mario Maker', 'H1Z1', 'FIFA 16', 'Minecraft', 'Runescape',
            'GTA 5', 'WOT', 'Call of Duty', 'Poker', 'Arma 3'
        ];
        $this->addRefs($games, 'game');

    }

    public function addRefs($refs, $type)
    {
        foreach ($refs as $r) {
            $ref = Ref::create([
                'type' => $type,
                'title' => $r
            ]);
        }
    }
}
