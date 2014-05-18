@extends($extendUrl)


@section($sectionName)

    {{-- On Page Edit Link for Admin And Editors --}}
    @if(\Cms\Models\User::check() AND \Cms\Models\User::getUser()->admin AND (!empty($allow_edit_page) AND $allow_edit_page === TRUE))
    <div style="float: right; margin-top: 3px;">
        <a href="{{ route('editPage', array($page->id)) }}"><img src="/packages/cednet/laravel-cms-addon/css/images/pen-icon.png" /></a>
    </div>
    @endif

   <h1>{{ $page->content->title }}</h1>
   <p>{{ $page->content->body }}</p>

@stop
