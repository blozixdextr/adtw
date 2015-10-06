<!DOCTYPE HTML>
<html>
<head>
    <title>@yield('title', 'ADTW')</title>
    <style>
        body {
            background-color: #{{ $bgColor }};
            padding: 0;
            margin: 0;
        }
        .wrapper {
            text-align: center;
        }
        img {
            display: block;
            margin: 0 auto;
            padding: 0;
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

        function fitPopupWindowToBanner() {
            var w = {{ $requiredSizes[0] }};
            var h = {{ $requiredSizes[1] }};
            var ah = $(window).height();
            var aw = $(window).width();
            this.resizeBy(0, h - ah);
        }

        $(function(){
            bannerRotation();
            bannersRefresh();
            setInterval(bannerRotation, {{ $rotationPeriod }});
            setInterval(bannersRefresh, {{ $trackPeriod }});

            fitPopupWindowToBanner();

        });
    </script>

</head>
<body>
    <div class="wrapper"></div>
</body>
</html>