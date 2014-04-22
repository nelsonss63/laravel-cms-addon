<div class="navbar navbar-inverse navbar-fixed-top">
   <div class="navbar-inner">
      <div class="container-fluid">
         <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
         </button>
         <a class="brand" href="/">CMS</a>
         <div class="nav-collapse collapse">
            <ul class="nav pull-right">
                <li><a href="{{ route('cmsLogout') }}">Log out</a></li>
            </ul>
            <ul class="nav">
               <li><a href="{{ route('cmsEdit') }}">{{ Lang::get('cms::m.edit') }}</a></li>
               <li><a href="{{ route('cmsAdmin') }}">{{ Lang::get('cms::m.admin') }}</a></li>
            </ul>
         </div><!--/.nav-collapse -->
      </div>
   </div>
</div>