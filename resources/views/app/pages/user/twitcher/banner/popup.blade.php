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
            /*line-height: {{ $requiredSizes[1] }}px;*/
            text-align: center;
        }
        img {
            display: block;
            /*line-height: {{ $requiredSizes[1] }}px;*/
            /*vertical-align: middle;*/
            margin: 0 auto;
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
                if (banners.length == 0) {
                    window.close();
                }
                bannerIndex = 0;
                bannerRotation();
            })
        }
        $(function(){
            bannerRotation();
            bannersRefresh();
            setInterval(bannerRotation, {{ $rotationPeriod }});
            setInterval(bannersRefresh, {{ $trackPeriod }});
        });
    </script>

</head>
<body>
    <div class="wrapper"></div>
</body>
</html>