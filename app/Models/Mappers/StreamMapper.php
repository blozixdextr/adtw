<?php

namespace App\Models\Mappers;

use App\Models\User;
use App\Models\UserProfile ;
use App\Models\UserPayment;
use App\Models\UserTransfer;
use App\Models\Stream;


class StreamMapper
{
    public static function byClient(User $user, $filters = [], $limit = 50)
    {
        //$streams = $user->streams();

        $streams = Stream::select('streams.*')->join('banner_stream', 'streams.id', '=', 'banner_stream.stream_id');
        $streams->join('banners', 'banners.id', '=', 'banner_stream.banner_id');
        $streams->where('banners.client_id', $user->id);
        $streams->orderBy('time_start', 'desc')->distinct();

        if (count($filters) > 0) {
            if (isset($filters['active'])) {
                if ($filters['active'] == 1) {
                    $streams->where('streams.time_end', null);
                }
                if ($filters['active'] == 0) {
                    $streams->where('streams.time_end', '<', \DB::raw('NOW()'));
                }
            }
        }

        $streams = $streams->paginate($limit);

        return $streams;
    }

}