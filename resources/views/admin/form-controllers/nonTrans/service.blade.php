<div class="form-group">
	{{ Form::label(trans('admin.'.$key), null, ['class' => 'control-label']) }} 
	<select name="{{ $key }}[]" class="form-control select2" id="" multiple>
		@foreach (getService()->posts as $item)
		<option value="{{$item->id}}" @if(isset($post->service) && in_array($item->id, ($post->service))) selected @endif>{{$item->translate(app()->getlocale())->title}}</option>
		@endforeach
	</select>
</div>