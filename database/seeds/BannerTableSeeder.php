<?php

use Illuminate\Database\Seeder;
use App\Models\UserProfile;
use App\Models\User;
use App\Models\Ref;
use App\Models\Banner;
use Faker\Factory as FakerFactory;
use App\Models\Mappers\BannerMapper;
use App\Models\Mappers\NotificationMapper;
use App\Models\Mappers\LogMapper;

class BannerTableSeeder extends Seeder
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
        DB::table('withdrawals')->truncate();
        DB::table('payments')->truncate();
        DB::table('banners')->truncate();
        DB::table('banner_stream')->truncate();

        $this->faker = FakerFactory::create();

        $data = [];
        $data['languages'] = Ref::whereType('language')->get();
        $data['games'] = Ref::whereType('game')->get();
        $data['bannerTypes'] = Ref::whereType('banner_type')->get();
        $this->data = $data;

        $this->addBanners();
    }

    public function addBanners($limit = 2000)
    {
        $clients = User::whereType('client')->get();
        $twitchers = User::whereType('twitcher')->get();
        $bannerTypes = $this->data['bannerTypes'];
        $faker = $this->faker;
        $i = 0;
        foreach ($clients as $c) {
            try {
                $bannerCounts = rand(0, 4);
                if ($i > $limit) {
                    //continue;
                }
                for ($i = 0; $i < $bannerCounts; $i++) {
                    $bannerType = $bannerTypes->random();
                    $twitcher = $twitchers->random();
                    if (BannerMapper::twitcherFree($twitcher, $bannerType->id)) {
                        $limit = rand(0, $c->balance);
                        if ($limit > 0) {
                            $requiredSizes = explode('*', $bannerType->title);
                            $w = $requiredSizes[0];
                            $h = $requiredSizes[1];
                            $uploadDir = '/assets/app/upload/b/';
                            $file = $faker->image(public_path($uploadDir), $w, $h);
                            $file = $uploadDir.basename($file);
                            $banner = BannerMapper::addForTwitcher($twitcher, $c, $bannerType, $file, $limit);
                            NotificationMapper::bannerAdd($banner);
                            LogMapper::log('banner_add', $banner->id);
                            $accept = rand(0, 2);
                            $i++;
                            if ($accept) {
                                BannerMapper::acceptBanner($banner);
                            } else {
                                BannerMapper::declineBanner($banner);
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                dd($e->getTraceAsString());
            }
        }
    }


}
