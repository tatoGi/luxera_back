<div class="form-group">
    <label >{{ trans('admin.'.$key) }}</label> <br>
	
	@if(isset($post) && isset($post->translate($locale)->locale_additional['image'] ))

	<input type="hidden" name="{{ $locale }}[image]" value="{{$post->translate($locale)->image}}">
	<input type="hidden" name="{{ $locale }}[image]" value="{{$post->translate($locale)->imagename}}">
	@endif
    <input type="file" name="{{ $locale }}[image]" @if(isset($post) && isset($post->translate($locale)->image)) value="{{$post->translate($locale)->locale_additional['image'] }}" @endif>

	@if(isset($post) && isset($post->translate($locale)->image))
	<br>
    <div class="col-md-8 dfile d-flex">
	<a style="margin-top: 10px; display: block;" href="/{{ config('config.image_path').$post->translate($locale)->locale_additional['image'] }}" download="{{ $post->translate($locale)->locale_additional['image'] }}">{{ $post->translate($locale)->locale_additional['image'] }}</a>
    <span class="deletefile" data-lang="{{ $locale }}" data-id="{{ $post->id }}" data-token="{{ csrf_token() }}"
        data-route="/{{ app()->getLocale() }}/admin/section/posts/DeleteFile/{{ $post->id }}"
        delete="{{ $post->translate($locale)->locale_additional['image'] }}">X</span>
    </div>
	@endif
</div>
