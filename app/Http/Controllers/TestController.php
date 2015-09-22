<?php

namespace App\Http\Controllers;

use App\Models\BannerStream;
use App\Models\Mappers\BannerMapper;
use App\Models\Mappers\LogMapper;
use App\Models\Mappers\NotificationMapper;
use App\Models\Stream;
use App\Models\StreamTimelog;
use Auth;
use Mail;
use App\Models\User;
use Faker\Factory as FakerFactory;

class TestController extends Controller
{
    /*
    public function index() {

        Mail::raw('test mailgun', function ($message) {
            $message->to('info@ifrond.com', 'Ravil')->subject('Test subject');
        });

        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@adtw.ch',
            'password' => bcrypt('dextr12345'),
            'last_activity' => \Carbon\Carbon::now(),
        ]);

    }

    public function loginAs($userId) {
        $user = User::findOrFail($userId);
        Auth::loginUsingId($user->id, true);

        return redirect('/profile');
    }

    public function createAdmin() {

        Auth::loginUsingId($user->id, true);

        return redirect('/admin');
    }

*/

    public function stream($userId)
    {
        $user = User::findOrFail($userId);

        $banners = BannerMapper::activeTwitcher($user);
        if (count($banners) == 0) {
            echo 'no banners'; die();
        }
        $faker = FakerFactory::create();

        $streamDate = \Carbon\Carbon::createFromTimestamp($faker->dateTimeBetween($user->created_at)->getTimestamp());
        $stream = Stream::create([
            'user_id' => $user->id,
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
                $screenshot = $faker->imageUrl(640, 360);
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
    }

    public function recalcStreams()
    {
        $bannerStreams = BannerStream::all();
        echo '<table border="1"><tr><th>stream</th><th>banner</th><th>client</th><th>price</th><th>recalced price</th><th>difference</th></tr>';
        foreach ($bannerStreams as $bs) {
            $amount = $bs->amount;
            $recalcedAmount = StreamTimelog::whereStreamId($bs->stream_id)->sum('amount');
            if ($recalcedAmount > 0) {
                $diff = number_format(100 * $amount/$recalcedAmount, 2);
            } else {
                if ($amount > 0) {
                    $diff = 'failed';
                }
            }
            if ($diff == '100.00') {
                $diff = '';
                echo '<tr>';
            } else {
                $diff .= '%';
                echo '<tr style="font-weight: bold">';
            }
            echo '<tr>';
            echo '<td>stream#'.$bs->stream_id.'</td>';
            echo '<td>banner#'.$bs->banner_id.'</td>';
            echo '<td>client#'.$bs->banner->client_id.'</td>';

            echo '<td>'.$amount.'</td>';

            echo '<td>'.$recalcedAmount.'</td>';
            echo '<td>'.$diff.'</td>';
            echo '<tr>';
        }
        echo '</table>';

    }
}
