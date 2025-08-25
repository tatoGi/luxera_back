<div class="form-group">
    {{ Form::label($key,  trans('admin.'.$key),  ['class' => 'control-label iconify', 'data-icon' => isset($field['data-icon']) ? $field['data-icon'] : null]) }}
    {{ Form::text($locale.'['.$key.']',  null, array_merge(
        ['class' => 'form-control unique-slug', isset($field['required']) && $field['required'] ? 'required' : 
        '', 'data-id' => isset($banner) ? $banner->translate($locale)->banner_id : null, 'data-locale' => $locale, 'id' => $locale.'-slug']
    )) }}
  <div class="print-error-msg">
    <ul></ul>
</div>
</div>
