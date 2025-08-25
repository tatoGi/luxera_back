<div class="form-group">
	{{ Form::label(trans('admin.'.$key), null, ['class' => 'control-label']) }}
	
	
	<select multiple="multiple" multiple class="form-control select2 select2-multiple  @error('type') danger @enderror " name="{{ $key }}[]" id="{{ $key }}">
		@foreach (directories($field['directory_type']) as $k => $directory)
			<option value="{{ $directory->id }}" @if(isset($post) && $post->{$key} !== null && in_array($directory->id, $post->{$key})) selected @endif >{{ $directory->title }}</option>
		@endforeach
	</select>
</div>