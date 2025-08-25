<div class="form-group">
    
    {{ Form::label(trans('admin.'.$key), null, ['class' => 'control-label']) }}
    <br>
    {{ Form::hidden($locale.'['.$key.']', '0') }}
    {{ Form::checkbox($locale.'['.$key.']', 1,  null, [
        'data-plugin' => 'switchery', 
        'data-color'=>'#3bafda', 
        'id' =>  $locale.'-'.$key
    ]) }}

</div>





