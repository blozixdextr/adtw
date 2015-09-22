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
        DB::table('banner_stream')->truncate();
        DB::table('user_transfers')->truncate();

        $this->faker = FakerFactory::create();

        $data = [];
        $data['languages'] = Ref::whereType('language')->get();
        $data['games'] = Ref::whereType('game')->get();
        $data['bannerTypes'] = Ref::whereType('banner_type')->get();
        $this->data = $data;

        for ($i = 0; $i <= 10; $i++) {
            $this->addStreams();
        }

        $this->payStreams();

    }

    public function addStreams()
    {
        $twitchers = User::whereType('twitcher')->get();
        $faker = $this->faker;
        $i = 0;
        foreach ($twitchers as $t) {
            try {
                $bannerTypes = $t->bannerTypes;
                if (count($bannerTypes) == 0) {
                    continue;
                }
                $bannerType = $bannerTypes->random();
                $banners = BannerMapper::activeTwitcher($t, $bannerType->id);
                if (count($banners) == 0) {
                    continue;
                }
                $streamDate = \Carbon\Carbon::createFromTimestamp($faker->dateTimeBetween($t->created_at)->getTimestamp());
                $stream = Stream::create([
                    'user_id' => $t->id,
                    'time_start' => $streamDate
                ]);
                BannerMapper::bannersToStream($stream, $banners);
                LogMapper::log('stream_start', $stream->id);
                foreach ($banners as $b) {
                    NotificationMapper::bannerStream($b, $stream);
                }
                $maxMinutes = rand(3, 30);
                for ($m = 0; $m < $maxMinutes; $m++) {
                    $startTime = $streamDate->getTimestamp();
                    $endTime = $streamDate->addMinutes(10)->getTimestamp();
                    $startTime = \Carbon\Carbon::createFromTimestamp($startTime);
                    $endTime = \Carbon\Carbon::createFromTimestamp($endTime);
                    $streamDate = $endTime;

                    $status = rand(0, 1);
                    if ($status == 1) {
                        //$uploadDir = '/assets/app/upload/t/';
                        $screenshot = $faker->imageUrl(640, 360);
                        //$screenshot = $uploadDir.basename($screenshot);
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
                            'timeslot_start' => $startTime,
                            'timeslot_end' => $endTime,
                            'viewers' => $viewers,
                            'status' => 'live',
                            'screenshot' => $screenshot,
                            'response' => $response
                        ]);
                        $streamTimelog->calcAmount();
                        foreach ($banners as $b) {
                            BannerMapper::trackBanner($b, $stream, $streamTimelog);
                        }
                    } else {
                        $streamTimelog = StreamTimelog::create([
                            'stream_id' => $stream->id,
                            'timeslot_start' => $startTime,
                            'timeslot_end' => $endTime,
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
            } catch (\Exception $e) {
                dd($e->getTraceAsString());
            }
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
