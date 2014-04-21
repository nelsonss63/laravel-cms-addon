@extends('cms::layouts.edit')

@section('content')

<div class="pull-right">
    <a href="javascript:void(0);" onclick="$('#allSettings').toggle();$('#newSetting').toggle();$('#newSetting input:first').focus();" class="btn btn-primary">Create new setting</a>
</div>

<h1>Settings</h1>

{{ Former::horizontal_open()->secure() }}

<div id="allSettings">

    @foreach($settings as $setting)

        {{ Former::text($setting->name)->value($setting->value)->help(' <a href="'.route('removeSetting', array($setting->id)).'" onclick="return confirm(\'Confirm\'); return false;">x</a>') }}

    @endforeach

</div>

<div class="well" id="newSetting" style="display: none;">
    {{ Former::text('new_setting') }}
    {{ Former::text('new_setting_value') }}
</div>

{{ Former::submit()->class('btn btn-primary btn-block') }}

{{ Former::close() }}

@stop