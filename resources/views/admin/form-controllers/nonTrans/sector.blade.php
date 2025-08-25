<div class="form-group">
	{{ Form::label(trans('admin.'.$key), null, ['class' => 'control-label']) }}

	<select name="{{ $key }}[]" class="form-control select2" id="" multiple>
        @if(isset(getSectors()->posts))
		@foreach (getSectors()->posts as $item)
		<option value="{{$item->translate(app()->getlocale())->title}}" @if(isset($post->additional['sector']) && in_array($item->translate(app()->getlocale())->title, ($post->additional['sector']))) selected @endif>{{$item->translate(app()->getlocale())->title}}</option>
		@endforeach
        @endif
	</select>
</div>
