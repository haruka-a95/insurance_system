<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <title>保険システム</title>

        <!-- jQuery -->
        <script src="/js/jquery-3.7.1.min.js"></script>
        <script>
        window.jQuery || document.write('<script src="https://code.jquery.com/jquery-3.6.0.min.js"><\/script>')
        </script>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="mx-5">
            <h1>社内システムホーム</h1>
            <p class="bg-sky-100">ログイン機能は省略。</p>
        </div>
    </body>
</html>
