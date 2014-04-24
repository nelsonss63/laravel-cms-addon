@extends($extendUrl)


@section($sectionName)

   <h1>{{ $page->content->title }}</h1>
   <p>{{ $page->content->body }}</p>

@stop
