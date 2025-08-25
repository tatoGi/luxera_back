
<div class="form-group">
    {{ Form::label($key,  trans('admin.'.$key),  ['class' => 'control-label iconify', 'data-icon' => isset($field['data-icon']) ? $field['data-icon'] : null]) }}
    {{ Form::textarea($locale.'['.$key.']', null, ['class' => 'form-control ckeditor']) }}
</div>

