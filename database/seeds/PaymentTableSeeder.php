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
use App\Models\UserPayment;
use App\Models\Mappers\PaymentMapper;

class PaymentTableSeeder extends Seeder
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
        DB::table('payments')->truncate();
        DB::table('withdrawals')->truncate();

        $this->faker = FakerFactory::create();

        $data = [];
        $data['languages'] = Ref::whereType('language')->get();
        $data['games'] = Ref::whereType('game')->get();
        $data['bannerTypes'] = Ref::whereType('banner_type')->get();
        $this->data = $data;
/*
        $this->paypal();
        $this->stripe();
        $this->withdraw();
*/
    }

    public function paypal()
    {
        $user = User::whereType('client')->get()->random(25);
        $faker = $this->faker;
        foreach ($user as $u) {
            try {
                $amount = rand(5, 50);
                $payment = UserPayment::create([
                    'user_id' => $u->id,
                    'merchant' => 'paypal',
                    'transaction_number' => rand(1000000, 2000000),
                    'title' => 'refill',
                    'amount' => $amount,
                    'currency' => 'USD'
                ]);
                LogMapper::log('payment', $amount, 'refilled', ['payment_id' => $payment->id, 'merchant' => 'paypal']);
                NotificationMapper::refilled($payment);
            } catch (\Exception $e) {
                dd($e->getTraceAsString());
            }
        }
    }

    public function stripe()
    {
        $user = User::whereType('client')->get()->random(25);
        $faker = $this->faker;
        foreach ($user as $u) {
            try {
                $amount = rand(5, 50);
                $payment = UserPayment::create([
                    'user_id' => $u->id,
                    'merchant' => 'stripe',
                    'transaction_number' => rand(1000000, 2000000),
                    'title' => 'refill',
                    'amount' => $amount,
                    'currency' => 'USD'
                ]);
                LogMapper::log('payment', $amount, 'refilled', ['payment_id' => $payment->id, 'merchant' => 'stripe']);
                NotificationMapper::refilled($payment);
            } catch (\Exception $e) {
                dd($e->getTraceAsString());
            }
        }
    }

    public function withdraw()
    {
        $user = User::whereType('twitcher')->where('balance', '>', 5)->get()->random(25);
        $faker = $this->faker;
        foreach ($user as $u) {
            try {
                $account = $faker->freeEmail;
                $amount = rand(3, $u->availableBalance());
                PaymentMapper::withdrawPaypalPrepare($u, $account, $amount);
                NotificationMapper::withdraw($u, $amount, 'USD', 'paypal', $account);
                LogMapper::log('withdraw', $u->id, 'request', ['account' => $account, 'merchant' => 'paypal', 'amount' => $amount]);
            } catch (\Exception $e) {
                dd($e->getTraceAsString());
            }
        }
    }

}
