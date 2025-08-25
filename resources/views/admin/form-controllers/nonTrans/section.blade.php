<div class="form-group">
	{{ Form::label(trans('admin.'.$key), null, ['class' => 'control-label']) }}
	
	
	<select  class="form-control select2 @error('type') danger @enderror " name="{{ $key }}" id="{{ $key }}">
		@foreach (getSectionsWithTypes($field['types']) as $k => $sec)
		
			<option value="{{ $sec->id }}" @if(isset($post) && $post->section_id == $sec->id) selected @else @if(isset($section) && $section->id == $sec->id) selected @endif  @endif  >{{ $sec->title }}</option>
		@endforeach
	</select>
</div>
