@extends('cms::layouts.page')

@section('content')

<strong>{{ Lang::get('cms::m.search-result-for') }}</strong>
<span class="search_keywords">{{ $q }}</span>

@if(count($result) > 0)
@foreach($result as $count => $page)
<div class="search_result">
   <h2><a href="{{ $page->url }}">{{ $page->content->title }}</a></h2>
   <p>{{ substr($page->content->body,0,100) }}... [ <a href="{{ $page->url }}">{{ Lang::get('cms::m.read-more') }}</a> ]</p>
</div>
@endforeach
@else
   <div class="alert alert-info">{{ Lang::get('cms::m.no-search-result') }}</div>
@endif

@stop