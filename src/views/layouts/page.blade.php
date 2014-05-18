<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <title>@if(!empty($title)) {{ $title }} @endif</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="description" content="">
   <meta name="author" content="">

   <link href="/packages/cednet/laravel-cms-addon/bootstrap/css/bootstrap.css" rel="stylesheet">
   <link href="/packages/cednet/laravel-cms-addon/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

   <link href="/packages/cednet/laravel-cms-addon/css/style.css" rel="stylesheet">
   <link href="/packages/cednet/laravel-cms-addon/css/tree.css" rel="stylesheet">

   <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
   <!--[if lt IE 9]>
   <script src="/packages/cednet/laravel-cms-addon/bootstrap/js/html5shiv.js"></script>
   <![endif]-->

   <!-- Fav and touch icons -->
   <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/packages/cednet/laravel-cms-addon/bootstrap/ico/apple-touch-icon-144-precomposed.png">
   <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/packages/cednet/laravel-cms-addon/bootstrap/ico/apple-touch-icon-114-precomposed.png">
   <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/packages/cednet/laravel-cms-addon/bootstrap/ico/apple-touch-icon-72-precomposed.png">
   <link rel="apple-touch-icon-precomposed" href="/packages/cednet/laravel-cms-addon/bootstrap/ico/apple-touch-icon-57-precomposed.png">
   <link rel="shortcut icon" href="/packages/cednet/laravel-cms-addon/bootstrap/ico/favicon.png">

   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <script src="/packages/cednet/laravel-cms-addon/bootstrap/js/jquery.js"></script>
   <script src="/packages/cednet/laravel-cms-addon/js/page.js"></script>

</head>

<body>

<div class="navbar">
   <div class="navbar-inner">
      <div class="container-fluid">

         <div class="nav-collapse collapse">

            {{ \Cms\Models\MenuCreator::navbar('2') }}

            <script src="/packages/cednet/laravel-cms-addon/js/twitter-bootstrap-hover-dropdown.js?view=1"></script>
            <script>
               /* Ads dropdown to Navbar */
               $(document).ready(function() {
                  $('.js-activated').dropdownHover().dropdown();
               });
            </script>


            <ul class="nav pull-right">
               @if(Auth::check() && \Cms\Models\User::getUser()->edit)
               <li><a href="{{ route('cmsEdit') }}">{{ Lang::get('cms::m.edit') }}</a></li>
               @endif
               @if(Auth::check() && \Cms\Models\User::getUser()->admin)
               <li><a href="{{ route('cmsAdmin') }}">{{ Lang::get('cms::m.admin') }}</a></li>
               @endif
               <li class="dropdown">
                  <a class="dropdown-toggle js-activated" data-toggle="dropdown" href="#">
                     @if(Auth::check())
                     {{ Lang::get('cms::m.hello') }} {{ \Cms\Models\User::getUser()->username }}
                     @else
                     {{ Lang::get('cms::m.my-account') }}
                     @endif
                     <b class="caret"></b>
                  </a>
                  <ul class="dropdown-menu">
                     @if(Auth::check())
                     <li class="divider"></li>
                     <li><a href="{{ route('cmsLogout') }}">{{ Lang::get('cms::m.logout') }}</a></li>
                     @else
                     <li><a href="{{ route('cmsLogin') }}">{{ Lang::get('cms::m.login') }}</a></li>
                     @endif
                  </ul>
               </li>
            </ul>

            <form class="navbar-search pull-right" method="POST" action="/search">
               <input type="text" name="q" class="search-query" placeholder="Search" style="width: 150px;">
            </form>

         </div><!--/.nav-collapse -->
      </div>
   </div>
</div>


<ul class="breadcrumb">
   <li><a href="/">{{ Lang::get('cms::m.home') }}</a></li>
   @if(!empty($page))
   @foreach($breadcrumbs as $subpage)
      @if($page->slug == $subpage->slug)
         <li class="active"><span class="divider">/</span>{{ $subpage->content->title }}</li>
      @else
         <li><span class="divider">/</span><a href="{{ $subpage->url }}">{{ $subpage->content->title }}</a></li>
      @endif
   @endforeach
   @endif
</ul>


<div class="container-fluid">
   <div class="row-fluid">
      <div class="span3">
         <div class="well sidebar-nav">

             @if(!empty($page))
                 {{ \Cms\Models\MenuCreator::mainNav() }}
             @endif

         </div><!--/.well -->
      </div><!--/span-->
      <div class="span9">

         {{-- On Page Edit Link for Admin And Editors --}}
         @if(Auth::check() AND \Cms\Models\User::getUser()->admin AND (!empty($allow_edit_page) AND $allow_edit_page === TRUE))
            <div class="edit-page">
               <img src="/packages/cednet/laravel-cms-addon/css/images/pen-icon.png" />
               <span><a href="{{ route('editPage', array($page->id)) }}">{{ Lang::get('cms::m.edit-page') }}</a></span>
            </div>
         @endif

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
<script src="/packages/cednet/laravel-cms-addon/bootstrap/js/bootstrap-alert.js"></script>
<script src="/packages/cednet/laravel-cms-addon/bootstrap/js/bootstrap-modal.js"></script>
<script src="/packages/cednet/laravel-cms-addon/bootstrap/js/bootstrap-dropdown.js"></script>
<script src="/packages/cednet/laravel-cms-addon/bootstrap/js/bootstrap-button.js"></script>
<script src="/packages/cednet/laravel-cms-addon/bootstrap/js/bootstrap-collapse.js"></script>
<script src="/packages/cednet/laravel-cms-addon/bootstrap/js/bootstrap-carousel.js"></script>

</body>
</html>