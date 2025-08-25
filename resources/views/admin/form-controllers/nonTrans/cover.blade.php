<div class="form-group">
    <label data-cover="-">{{ trans('admin.'.$key) }}</label> <br>
	@if(isset($post) && isset($post->cover))
	<input type="hidden" name="old_cover" value="{{ $post->cover }}">
	@endif
    <input type="file" name="cover">
	
	@if(isset($post) && isset($post->cover))
	<br>
	<a style="margin-top: 10px; display: block;" href="/{{config('config.file_path').$post->cover}}" download="{{$post->cover}}">{{$post->cover}}</a>
	@endif
</div> 