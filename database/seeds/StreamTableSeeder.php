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
use App\Models\Mappers\StreamMapper;
use App\Models\Stream;
use App\Models\StreamTimelog;
use App\Models\BannerStream;

class StreamTableSeeder extends Seeder
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
        DB::table('streams')->truncate();
        DB::table('stream_timelogs')->truncate();

        $this->faker = FakerFactory::create();

        $data = [];
        $data['languages'] = Ref::whereType('language')->get();
        $data['games'] = Ref::whereType('game')->get();
        $data['bannerTypes'] = Ref::whereType('banner_type')->get();
        $this->data = $data;

        $this->addStreams();
        $this->addStreams();
        $this->payStreams();
    }

    public function addStreams($limit = 1000)
    {
        $twitchers = User::whereType('twitcher')->get();
        $faker = $this->faker;
        $i = 0;
        foreach ($twitchers as $t) {
            if ($i > $limit) {
                continue;
            }
            $bannerType = $t->bannerTypes->random()->id;
            $banners = BannerMapper::activeTwitcher($t, $bannerType->id);
            $streamDate = \Carbon\Carbon::createFromTimestamp($faker->dateTimeBetween($t->created_at)->getTimestamp());
            $stream = Stream::create([
                'user_id' => $t->id,
                'time_start' => $streamDate
            ]);
            BannerMapper::bannersToStream($stream, $banners);
            LogMapper::log('stream_start', $stream->id);
            foreach ($banners as $b) {
                NotificationMapper::bannerStream($b);
            }
            $maxMinutes = rand(3, 30);
            for ($m = 0; $m < $maxMinutes; $m++) {
                $status = rand(0, 1);
                if ($status == 1) {
                    $uploadDir = '/assets/app/upload/t/';
                    $screenshot = $faker->image(public_path($uploadDir), 640, 360);
                    $screenshot = $uploadDir.basename($screenshot);
                    $viewers = rand(0, 100);
                    $response = (object)[
                        'stream' => (object)[
                            'viewers' => $viewers,
                            'preview' => (object)[
                                'medium' => $faker->imageUrl(640, 360)
                            ]
                        ]
                    ];
                    $streamTimelog = StreamTimelog::create([
                        'stream_id' => $stream->id,
                        'timeslot_start' => $streamDate,
                        'timeslot_end' => $streamDate->addMinutes(10),
                        'viewers' => $viewers,
                        'status' => 'live',
                        'screenshot' => $screenshot,
                        'response' => $response
                    ]);
                    foreach ($banners as $b) {
                        BannerMapper::trackBanner($b, $stream, $streamTimelog);
                    }
                } else {
                    $streamTimelog = StreamTimelog::create([
                        'stream_id' => $stream->id,
                        'timeslot_start' => $streamDate,
                        'timeslot_end' => $streamDate->addMinutes(10),
                        'viewers' => 0,
                        'status' => 'died',
                        'screenshot' => '',
                        'response' => (object)[]
                    ]);
                }
            }
            $stream->time_end = $streamDate;
            $stream->save();
            $i++;
        }
    }

    public function payStreams()
    {
        $bannerStreams = BannerStream::whereStatus('waiting')->get();
        $faker = $this->faker;

        foreach ($bannerStreams as $bs) {
            $skip = boolval(rand(0, 1));
            if ($skip) {
                continue;
            }
            $banner = $bs->banner;
            $user = $banner->client;
            $stream = $bs->stream;
            $transfer = StreamMapper::pay($user, $banner, $stream);
            $decline = rand(0, 5);
            if ($decline == 4) {
                $bs->status = 'declining';
                $bs->client_comment = $faker->paragraph;
                $bs->save();

                LogMapper::log('banner_declining', $banner->id, $stream->id);
                NotificationMapper::bannerPayDeclining($banner, $stream, $bs->amount);
            } else {
                $bs->status = 'accepted';
                $bs->save();
                LogMapper::log('banner_paid', $banner->id, $stream->id);
                NotificationMapper::bannerPayAccept($banner, $stream, $bs->amount);
            }
        }

    }



}
