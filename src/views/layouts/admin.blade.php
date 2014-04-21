<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <title>{{ $title }}</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="description" content="">
   <meta name="author" content="">

   <script src="/packages/cednet/laravel-cms-addon/bootstrap/js/jquery.js"></script>

   <!-- Le styles -->
   <link href="/packages/cednet/laravel-cms-addon/bootstrap/css/bootstrap.css" rel="stylesheet">
   <style type="text/css">
      body {
         padding-top: 60px;
         padding-bottom: 40px;
      }
      .sidebar-nav {
         padding: 9px 0;
      }

      @media (max-width: 980px) {
         /* Enable use of floated navbar text */
         .navbar-text.pull-right {
            float: none;
            padding-left: 5px;
            padding-right: 5px;
         }
      }
   </style>
   <link href="/packages/cednet/laravel-cms-addon/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

   <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
   <!--[if lt IE 9]>
   <script src="/packages/cednet/laravel-cms-addon/bootstrap/js/html5shiv.js"></script>
   <![endif]-->

   <!-- Fav and touch icons -->
   <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/packages/cednet/laravel-cms-addon/bootstrap/ico/apple-touch-icon-144-precomposed.png">
   <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/packages/cednet/laravel-cms-addon/bootstrap/ico/apple-touch-icon-114-precomposed.png">
   <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/packages/cednet/laravel-cms-addon/bootstrap/ico/apple-touch-icon-72-precomposed.png">
   <link rel="apple-touch-icon-precomposed" href="/packages/cednet/laravel-cms-addon/bootstrap/ico/apple-touch-icon-57-precomposed.png">
</head>

<body>

@include('cms::layouts/admin_nav')

<div class="container-fluid">
   <div class="row-fluid">
      <div class="span3">

         @section('main_nav')

         <div class="well sidebar-nav">
            <ul class="nav nav-list">
               <li class="nav-header">{{ Lang::get('cms::m.navigation') }}</li>
               @foreach($nav as $title => $link)
               <li><a href="{{ $link }}">{{ $title }}</a></li>
               @endforeach
            </ul>
         </div><!--/.well -->

         @show

      </div><!--/span-->
      <div class="span9">

         <!-- check for flash notification message -->
         @if(Session::has('flash_notice'))
         <div id="flash_notice" class="alert alert-success">{{ Session::get('flash_notice') }}</div>
         @endif
         @if(Session::has('flash_error'))
         <div id="flash_error" class="alert alert-error">{{ Session::get('flash_error') }}</div>
         @endif



         @yield('content')




      </div><!--/span-->
   </div><!--/row-->

   <hr>

   <footer>
      <p>@if(!empty($company_name)) &copy; {{ $company_name }} @endif</p>
   </footer>

</div><!--/.fluid-container-->

<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="/packages/cednet/laravel-cms-addon/bootstrap/js/bootstrap-transition.js"></script>
<script src="/packages/cednet/laravel-cms-addon/bootstrap/js/bootstrap-alert.js"></script>
<script src="/packages/cednet/laravel-cms-addon/bootstrap/js/bootstrap-modal.js"></script>
<script src="/packages/cednet/laravel-cms-addon/bootstrap/js/bootstrap-dropdown.js"></script>
<script src="/packages/cednet/laravel-cms-addon/bootstrap/js/bootstrap-scrollspy.js"></script>
<script src="/packages/cednet/laravel-cms-addon/bootstrap/js/bootstrap-tab.js"></script>
<script src="/packages/cednet/laravel-cms-addon/bootstrap/js/bootstrap-tooltip.js"></script>
<script src="/packages/cednet/laravel-cms-addon/bootstrap/js/bootstrap-popover.js"></script>
<script src="/packages/cednet/laravel-cms-addon/bootstrap/js/bootstrap-button.js"></script>
<script src="/packages/cednet/laravel-cms-addon/bootstrap/js/bootstrap-collapse.js"></script>
<script src="/packages/cednet/laravel-cms-addon/bootstrap/js/bootstrap-carousel.js"></script>
<script src="/packages/cednet/laravel-cms-addon/bootstrap/js/bootstrap-typeahead.js"></script>

</body>
</html>
