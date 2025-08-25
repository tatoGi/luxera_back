<div class="form-group">
    <label data-icon="-">{{ trans('admin.'.$key) }}</label> <br>
	@if(isset($post) && isset($post->icon))
	<input type="hidden" name="old_icon" value="{{ $post->icon }}">
	@endif
    <input type="file" name="icon">
	
	@if(isset($post) && isset($post->icon))
	<br>
	<a style="margin-top: 10px; display: block;" href="/{{config('config.file_path').$post->icon}}" download="{{$post->icon}}">{{$post->icon}}</a>
	@endif
</div> 
