<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <title>@if(!empty($title)) {{ $title }} @endif</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="description" content="">
   <meta name="author" content="">

   <link href="/packages/cednet/cms/bootstrap/css/bootstrap.css" rel="stylesheet">
   <link href="/packages/cednet/cms/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

   <link href="/packages/cednet/cms/css/style.css" rel="stylesheet">
   <link href="/packages/cednet/cms/css/tree.css" rel="stylesheet">

   <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
   <!--[if lt IE 9]>
   <script src="/packages/cednet/cms/bootstrap/js/html5shiv.js"></script>
   <![endif]-->

   <!-- Fav and touch icons -->
   <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/packages/cednet/cms/bootstrap/ico/apple-touch-icon-144-precomposed.png">
   <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/packages/cednet/cms/bootstrap/ico/apple-touch-icon-114-precomposed.png">
   <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/packages/cednet/cms/bootstrap/ico/apple-touch-icon-72-precomposed.png">
   <link rel="apple-touch-icon-precomposed" href="/packages/cednet/cms/bootstrap/ico/apple-touch-icon-57-precomposed.png">
   <link rel="shortcut icon" href="/packages/cednet/cms/bootstrap/ico/favicon.png">

   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <script src="/packages/cednet/cms/bootstrap/js/jquery.js"></script>
   <script src="/packages/cednet/cms/js/page.js"></script>

</head>

<body>

<!-- check for flash notification message -->
@if(Session::has('flash_notice'))
<div id="flash_notice" class="alert alert-success">{{ Session::get('flash_notice') }}</div>
@endif
@if(Session::has('flash_error'))
<div id="flash_error" class="alert alert-error">{{ Session::get('flash_error') }}</div>
@endif

@yield('content')

<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="/packages/cednet/cms/bootstrap/js/bootstrap-alert.js"></script>
<script src="/packages/cednet/cms/bootstrap/js/bootstrap-modal.js"></script>
<script src="/packages/cednet/cms/bootstrap/js/bootstrap-dropdown.js"></script>
<script src="/packages/cednet/cms/bootstrap/js/bootstrap-button.js"></script>
<script src="/packages/cednet/cms/bootstrap/js/bootstrap-collapse.js"></script>
<script src="/packages/cednet/cms/bootstrap/js/bootstrap-carousel.js"></script>

</body>
</html>