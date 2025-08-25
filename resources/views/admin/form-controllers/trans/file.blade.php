<div class="form-group">
    <label >{{ trans('admin.'.$key) }}</label> <br>
	
	@if(isset($post) && isset($post->translate($locale)->locale_additional['file'] ))

	<input type="hidden" name="{{ $locale }}[file]" value="{{$post->translate($locale)->file}}">
	<input type="hidden" name="{{ $locale }}[file]" value="{{$post->translate($locale)->filename}}">
	@endif
    <input type="file" name="{{ $locale }}[file]" @if(isset($post) && isset($post->translate($locale)->file)) value="{{$post->translate($locale)->locale_additional['file'] }}" @endif>

	@if(isset($post) && isset($post->translate($locale)->file))
	<br>
    <div class="col-md-8 dfile d-flex">
	<a style="margin-top: 10px; display: block;" href="/{{ config('config.file_path').$post->translate($locale)->locale_additional['file'] }}" download="{{ $post->translate($locale)->locale_additional['file'] }}">{{ $post->translate($locale)->locale_additional['file'] }}</a>
    <span class="deletefile" data-lang="{{ $locale }}" data-id="{{ $post->id }}" data-token="{{ csrf_token() }}"
        data-route="/{{ app()->getLocale() }}/admin/section/posts/DeleteFile/{{ $post->id }}"
        delete="{{ $post->translate($locale)->locale_additional['file'] }}">X</span>
    </div>
	@endif
</div>
