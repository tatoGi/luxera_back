

<div class="form-group">
    {{ Form::label($key,  trans('admin.'.$key), ['class' => 'control-label']) }}
    {{ Form::text($locale.'['.$key.']',  null , array_merge(['class' => 'form-control', 'data-role' => "tagsinput"])) }}
</div>

