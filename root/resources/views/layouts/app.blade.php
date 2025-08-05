<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '保険会社システム')</title>
    <!-- jQuery -->
    <script src="/js/jquery-3.7.1.min.js"></script>
    <script>
       window.jQuery || document.write('<script src="https://code.jquery.com/jquery-3.6.0.min.js"><\/script>')
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">
    <!-- ナビゲーション -->
    <x-navigation />

    <!-- メインコンテンツ -->
    <main class="container mx-auto p-4">
        @yield('content')
    </main>

    <!-- フッター -->
    <footer class="bg-gray-200 text-center p-4 mt-4">
        <p>&copy; {{ date('Y') }} 保険会社システム</p>
    </footer>

</body>
</html>
