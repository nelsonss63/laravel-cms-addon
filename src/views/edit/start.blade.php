@extends('cms::layouts.edit')

@section('content')

<h1>Editor area</h1>

<p>From here you can edit pages and menus.</p>

<h2>Integration into your own framework</h2>

<p>Go to settings, and change the extend_url_page value to your view file path. Example: layouts.public</p>
<p class="help-block"><strong>Default value</strong> cms::templates.page (the cms:: part is a custom prefix for this package view template)</p>

@stop

@stop