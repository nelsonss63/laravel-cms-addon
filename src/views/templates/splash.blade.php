@extends($extendUrl)

@section('content')

<!-- Jumbotron -->
<div class="jumbotron">
   <h1>{{ $page->content->title }}</h1>
   <p class="lead">{{ $page->content->body }}</p>
   <a class="btn btn-large btn-success" href="#">{{ Lang::get('cms::m.read-more') }}</a>
</div>

<hr>

<!-- Example row of columns -->
<div class="row-fluid">
   <div class="span4">
      <h2>Heading</h2>
      <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
      <p><a class="btn" href="#">View details &raquo;</a></p>
   </div>
   <div class="span4">
      <h2>Heading</h2>
      <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
      <p><a class="btn" href="#">View details &raquo;</a></p>
   </div>
   <div class="span4">
      <h2>Heading</h2>
      <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa.</p>
      <p><a class="btn" href="#">View details &raquo;</a></p>
   </div>
</div>


@stop
