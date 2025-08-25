<div class="form-group">
    <label>{{ trans('admin.' . $key) }}</label>
    <br>
    <div class="upload-box" data-name="{{ $locale }}[files][{{ $key }}]"
        data-action="/{{ app()->getLocale() . '/admin/upload/image?_token=' . csrf_token() }}"
        data-delete="/{{ app()->getLocale() . '/admin/upload/image/delete?_token=' . csrf_token() }}">
        {{ Form::hidden('thumb', null) }}
        <ul class="image-previews">

            @if (isset($post) && isset($post->translate($locale)->{$key}))
                @foreach ($post->translate($locale)->{$key} as $file)
                    @if ($file !== null)
                        <li class="old">
                            <div class="close-it"></div>
                            <input type="hidden" name="{{ $locale }}[files][{{ $key }}][file][]" value="
                                {{ $file['file'] }}">
                            <img
                                src="{{ '/' . config('config.image_path') . config('config.thumb_path') . $file['file'] }}">
                            <input type="text" name="{{ $locale }}[files][{{ $key }}][desc][]"
                                value="{{ $file['name'] }}" class="form-control" placeholder="description">
                        </li>
                    @endif
                @endforeach
            @endif
        </ul>
    </div>
</div>
