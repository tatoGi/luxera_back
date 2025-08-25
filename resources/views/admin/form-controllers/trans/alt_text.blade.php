<div class="form-group">
    {{ Form::label($key,  trans('admin.'.$key),  ['class' => 'control-label iconify', 'data-icon' => isset($field['data-icon']) ? $field['data-icon'] : null]) }}
    {{ Form::text($locale.'['.$key.']',  null,   array_merge(  ['class' => 'form-control', isset($field['required']) && $field['required'] ? 'required' : ''])) }}
  
</div>
<style>
    [data-icon]:before {
        float: right;
        color:red;
        font-size: 24px;
    }
</style>