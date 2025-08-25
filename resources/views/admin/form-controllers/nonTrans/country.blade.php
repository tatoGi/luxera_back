<div class="form-group">
	{{ Form::label(trans('admin.'.$key), null, ['class' => 'control-label']) }} 
	<select name="{{ $key }}" class="form-control select2" id="">
		@foreach (getMunicipality()->posts as $item)
		<option value="{{$item->translate(app()->getlocale())->title}}"  @if(isset($post->municipality) && ($item->translate(app()->getlocale())->title == $post->municipality)) selected @endif>{{$item->translate(app()->getlocale())->title}}</option>
		@endforeach
	</select>
</div>