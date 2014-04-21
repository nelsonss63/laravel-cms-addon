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
               <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                     Account
                     <b class="caret"></b>
                  </a>
                  <ul class="dropdown-menu">
                     <li class="active"><a href="{{ route('logout') }}">Log out</a></li>
                  </ul>
               </li>
            </ul>
            <ul class="nav">
               <li class="active"><a href="{{ route('edit') }}">{{ Lang::get('cms::m.edit') }}</a></li>
               <li class="active"><a href="{{ route('admin') }}">{{ Lang::get('cms::m.admin') }}</a></li>
            </ul>
         </div><!--/.nav-collapse -->
      </div>
   </div>
</div>