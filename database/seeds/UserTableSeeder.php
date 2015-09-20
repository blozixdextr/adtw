<?php

use Illuminate\Database\Seeder;
use App\Models\UserProfile;
use App\Models\User;
use App\Models\Ref;
use Faker\Factory as FakerFactory;
use App\Models\Mappers\NotificationMapper;
use App\Models\Mappers\LogMapper;

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
        DB::table('user_auth_token')->truncate();
        DB::table('user_transfers')->truncate();
        DB::table('ref_user')->truncate();
        DB::table('notifications')->truncate();
        DB::table('logs')->truncate();

        $this->faker = FakerFactory::create();

        $data = [];
        $data['languages'] = Ref::whereType('language')->get();
        $data['games'] = Ref::whereType('game')->get();
        $data['bannerTypes'] = Ref::whereType('banner_type')->get();
        $this->data = $data;

        $this->admin();
        $this->streamers(500);
        $this->clients(500);


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

        for ($i = 0; $i < $limit; $i++) {
            $date = \Carbon\Carbon::createFromTimestamp($faker->dateTimeBetween('-1 year')->getTimestamp());
            //$date = $faker->dateTimeBetween('-1 year')->getTimestamp();
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
                'created_at' => $date,
                'balance' => 0
            ]);
            $profile = UserProfile::create([
                'first_name' => $user->name,
                'user_id' => $user->id,
                'about' => $faker->sentence,
                'avatar' => $faker->imageUrl(300, 300),
                'created_at' => $date
            ]);
            LogMapper::log('twitch_register', $user->id);
            NotificationMapper::registration($user);

            $bannersCount = rand(0, 3);
            $bannersClean = [];
            if ($bannersCount > 0) {
                if ($bannersCount == 1) {
                    $bannersClean = [$this->data['bannerTypes']->random()->id];
                } else {
                    $bannersClean = $this->data['bannerTypes']->random($bannersCount)->pluck('id')->toArray();
                }
            }
            $gamesCount = rand(0, 6);
            $gamesClean = [];
            if ($gamesCount > 0) {
                if ($gamesCount == 1) {
                    $gamesClean = [$this->data['games']->random()->id];
                } else {
                    $gamesClean = $this->data['games']->random($gamesCount)->pluck('id')->toArray();
                }
            }

            $user->refs()->sync(array_merge($bannersClean, $gamesClean));
        }
    }

    public function clients($limit = 1000)
    {
        $faker = $this->faker;

        for ($i = 0; $i < $limit; $i++) {
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
            LogMapper::log('client_register', $user->id);
            NotificationMapper::registration($user);
        }
    }
}
