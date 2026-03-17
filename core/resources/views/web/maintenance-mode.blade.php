{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>
        Maintenance Mode on
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ asset('storage/company') }}/{{ $web_config['fav_icon']->value }}">
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ asset('storage/company') }}/{{ $web_config['fav_icon']->value }}">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Titillium+Web:wght@400;600;700&display=swap"
        rel="stylesheet">
</head>
<!-- Body-->

<body class="toolbar-enabled rtl" style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">

    <div class="container rtl" style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
        <div class="row">
            <div class="col-12 mt-5">
                <center>
                    <img style="width: 350px!important;" src="{{ asset('assets/front-end') }}/img/maintenance-mode.jpg">
                    <h1>Website is under Maintenance.</h1><br>
                    <h5>Plese come back later.</h5>
                </center>
            </div>
        </div>
    </div>

    <!-- Vendor scrits: js libraries and plugins-->
    {{-- <script src="{{asset('assets/front-end')}}/vendor/jquery/dist/jquery.slim.min.js"></script> --}}
{{-- <script src="{{ asset('assets/front-end') }}/vendor/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/front-end') }}/vendor/smooth-scroll/smooth-scroll.polyfills.min.js"></script> --}}
{{-- </body> --}}

{{-- </html>  --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Under Maintenance</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body,
        html {
            height: 100%;
            font-family: 'Roboto Mono', monospace;
            background: #0d0d0d;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        /* Laravel code background (both sides) */
        .code-bg-left,
        .code-bg-right {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 150px;
            font-size: 14px;
            color: rgba(0, 255, 150, 0.05);
            overflow: hidden;
            z-index: 0;
        }

        .code-bg-left {
            left: 0;
        }

        .code-bg-right {
            right: 0;
        }

        .code-line {
            position: absolute;
            white-space: nowrap;
            animation: moveUp linear infinite;
        }

        @keyframes moveUp {
            0% {
                top: 100%;
            }

            100% {
                top: -5%;
            }
        }

        /* Glass container */
        .container {
            position: relative;
            max-width: 700px;
            width: 90%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(12px);
            padding: 60px 40px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.7);
            z-index: 1;
        }

        h1 {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #f4645f;
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.7);
        }

        h2 {
            font-size: 1.2rem;
            margin-bottom: 40px;
            font-weight: 400;
            color: #ccc;
        }

        .countdown {
            display: flex;
            justify-content: center;
            gap: 20px;
            font-weight: 700;
            color: #00ffff;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .countdown div {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .countdown span {
            font-size: 1rem;
            color: #ccc;
            margin-top: 5px;
        }

        .illustration {
            width: 120px;
            opacity: 0.8;
            margin-top: 15px;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        @media(max-width:700px) {
            h1 {
                font-size: 2.2rem;
            }

            .countdown {
                font-size: 2rem;
            }

            h2 {
                font-size: 1rem;
            }

            .container {
                padding: 40px 20px;
            }
        }
    </style>
</head>

<body>

    <!-- Laravel code floating left & right -->
    <div class="code-bg-left" id="codeLeft"></div>
    <div class="code-bg-right" id="codeRight"></div>

    <div class="container">
        <h1>🚧 Site Under Maintenance 🚧</h1>
        <h2>We are performing updates. Please check back later.</h2>

        <div class="countdown" id="countdown">
            <div><span id="hours">--</span><span>Hours</span></div>
            <div><span id="minutes">--</span><span>Minutes</span></div>
            <div><span id="seconds">--</span><span>Seconds</span></div>
        </div>

        <img class="illustration" src="https://cdn-icons-png.flaticon.com/512/3106/3106363.png" alt="construction">
    </div>

    <script>
        // Countdown timer H:M:S
        let countdownMinutes = Math.floor(Math.random() * (20 - 5 + 1)) + 5; // 5-20 minutes random
        let totalSeconds = countdownMinutes * 60;

        function updateTimer() {
            let h = Math.floor(totalSeconds / 3600);
            let m = Math.floor((totalSeconds % 3600) / 60);
            let s = totalSeconds % 60;

            document.getElementById("hours").innerText = h < 10 ? "0" + h : h;
            document.getElementById("minutes").innerText = m < 10 ? "0" + m : m;
            document.getElementById("seconds").innerText = s < 10 ? "0" + s : s;

            totalSeconds--;
            if (totalSeconds < 0) {
                clearInterval(timer);
                document.getElementById("hours").innerText = "00";
                document.getElementById("minutes").innerText = "00";
                document.getElementById("seconds").innerText = "00";
            }
        }
        let timer = setInterval(updateTimer, 1000);
        updateTimer();

        // Laravel code snippets floating
        const codeSnippets = [
            "Route::get('/', function() { return view('welcome'); });",
            "Artisan::call('migrate');",
            "public function index() { return view('home'); }",
            "Model::create($request->all());",
            "Blade::directive('datetime', function($expr){ return now(); });",
            "DB::table('users')->get();",
            "Auth::check();",
            "$user->roles()->attach($role);",
            "event(new UserRegistered($user));",
            "Config::get('app.timezone');"
        ];

        function createCodeLines(containerId) {
            const container = document.getElementById(containerId);
            for (let i = 0; i < 20; i++) {
                const line = document.createElement("div");
                line.className = "code-line";
                line.style.top = Math.random() * 100 + "%";
                line.style.left = "0px";
                line.style.animationDuration = (10 + Math.random() * 20) + "s";
                line.innerText = codeSnippets[Math.floor(Math.random() * codeSnippets.length)];
                container.appendChild(line);
            }
        }

        createCodeLines("codeLeft");
        createCodeLines("codeRight");
    </script>

</body>

</html>
