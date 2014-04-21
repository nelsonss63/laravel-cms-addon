@extends($extendUrl)


@section('content')

   <h1>{{ $page->content->title }}</h1>
   <p>{{ $page->content->body }}</p>


@stop
