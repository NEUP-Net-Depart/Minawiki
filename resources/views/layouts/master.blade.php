<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!--Import materialize.css-->
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/css/materialize.min.css">

    <link rel="stylesheet" href="/css/app.css">

    <link rel="stylesheet" href="/css/theme/yayin/app.css">

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <meta name="theme-color" content="#4dc4ff" />

    <title>@yield('title') - {{ env('APP_NAME', "Minawiki") }}</title>
</head>
<body>

<!--Import jQuery before materialize.js-->
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>

<!-- Compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js"></script>

<script type="text/javascript" src="/js/app.js"></script>

@yield('nav')
<main>
    @yield('content')
</main>
@include('layouts.footer')

</body>
</html>