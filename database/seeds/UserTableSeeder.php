<?php

use Illuminate\Database\Seeder;
use App\Models\UserProfile;
use App\Models\User;
use App\Models\Ref;
use Faker\Factory as FakerFactory;

class UserTableSeeder extends Seeder
{

    public $data = [];
    /*
     * FakerFactory
     */
    public $faker;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        DB::table('user_profiles')->truncate();

        $this->faker = FakerFactory::create();

        $data = [];
        $data['languages'] = Ref::whereType('language')->get();
        $data['games'] = Ref::whereType('game')->get();
        $data['bannerTypes'] = Ref::whereType('banner_type')->get();
        $this->data = $data;

        $this->admin();
        $this->streamers();
        $this->clients();


    }

    public function admin()
    {
        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@adtw.ch',
            'password' => bcrypt('dextr12345'),
            'last_activity' => \Carbon\Carbon::now(),
            'role' => 'admin',
            'provider' => 'local'
        ]);
        $profile = UserProfile::create([
            'first_name' => 'Administrator',
            'user_id' => $user->id
        ]);
    }

    public function streamers($limit = 1000)
    {
        $faker = $this->faker;

        for ($i = 2; $i < $limit; $i++) {
            $user = User::create([
                'name' => $faker->firstName,
                'email' => $faker->freeEmail,
                'password' => bcrypt('1'),
                'last_activity' => \Carbon\Carbon::now(),
                'role' => 'user',
                'type' => 'twitcher',
                'provider' => 'twitch',
                'twitch_views' => rand(0, 20000),
                'twitch_followers' => rand(0, 20000),
                'twitch_videos' => rand(0, 20000),
                'oauth_id' => rand(100000000, 2000000000),
                'language_id' => $this->data['languages']->random()->id,
                'balance' => rand(0, 100)
            ]);
            $profile = UserProfile::create([
                'first_name' => $user->name,
                'user_id' => $user->id,
                'about' => $faker->sentence,
                'avatar' => $faker->imageUrl(300, 300),
            ]);
        }
    }

    public function clients($limit = 1000)
    {
        $faker = $this->faker;

        for ($i = 2; $i < $limit; $i++) {
            $user = User::create([
                'name' => $faker->firstName,
                'email' => $faker->freeEmail,
                'password' => bcrypt('1'),
                'last_activity' => \Carbon\Carbon::createFromTimestamp($faker->unixTime),
                'role' => 'user',
                'type' => 'client',
                'provider' => 'local',
                'balance' => rand(0, 100)
            ]);
            $profile = UserProfile::create([
                'first_name' => $user->name,
                'last_name' => $faker->lastName,
                'user_id' => $user->id,
            ]);
        }
    }
}
