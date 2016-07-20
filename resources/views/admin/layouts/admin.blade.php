<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>StartUI - Premium Bootstrap 4 Admin Dashboard Template</title>

    <link href="img/favicon.144x144.png" rel="apple-touch-icon" type="image/png" sizes="144x144">
    <link href="img/favicon.114x114.png" rel="apple-touch-icon" type="image/png" sizes="114x114">
    <link href="img/favicon.72x72.png" rel="apple-touch-icon" type="image/png" sizes="72x72">
    <link href="img/favicon.57x57.png" rel="apple-touch-icon" type="image/png">
    <link href="img/favicon.png" rel="icon" type="image/png">
    <link href="img/favicon.ico" rel="shortcut icon">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="{{ asset('css/lib/font-awesome/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugin/jstree/src/themes/default/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/bootstrap-sweetalert/sweetalert.css') }}">
</head>

<body class="horizontal-navigation">

@include('admin/layouts/header')

@yield('content')
{{--{{ Widget::fileSystems() }}--}}

<script src="{{ asset('js/lib/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('js/lib/tether/tether.min.js') }}"></script>
<script src="{{ asset('js/lib/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/lib/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('js/lib/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('js/plugins.js') }}"></script>
<script src="{{ asset('js/plugin/jstree/jstree.min.js') }}"></script>
<script src="{{ asset('js/lib/hide-show-password/bootstrap-show-password.min.js') }}"></script>
<script src="{{ asset('js/lib/hide-show-password/bootstrap-show-password-init.js') }}"></script>
<script src="{{ asset('js/lib/bootstrap-sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('js/widgets/index.js') }}"></script>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>