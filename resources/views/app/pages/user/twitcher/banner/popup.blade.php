<!DOCTYPE HTML>
<html>
<head>
    <title>@yield('title', 'ADTW')</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <style>
        body {
            background-color: #{{ $bgColor }};
        }
        .wrapper {
            line-height: 620px;
            text-align: center;
        }
        img {
            display: inline-block;
            line-height: 620px;
            vertical-align: middle;
        }
    </style>
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script>
        var banners = [];
        var bannerIndex = 0;
        @foreach($banners as $b)
            banners.push('{{ $b->file }}');
        @endforeach
        function bannerRotation() {
            $('.wrapper').html('<img src="' + banners[bannerIndex] + '">');
            if (bannerIndex < banners.length - 1) {
                bannerIndex++;
            } else {
                bannerIndex = 0;
            }
        }
        function bannersRefresh() {
            $.getJSON('/user/twitcher/banner/ping/{{ $bannerType->id }}', function(data){
                banners = data.banners;
                bannerIndex = 0;
                bannerRotation();
            })
        }
        $(function(){
            bannerRotation();
            setInterval(bannerRotation, 1000);
            setInterval(bannersRefresh, 1000*60*5);
        });
    </script>

</head>
<body>
    <div class="wrapper"></div>
</body>
</html>