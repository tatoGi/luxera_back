<div class="form-group">
    <div class="custom-file">
      
        @if (!empty($settings['eu_vs_disinfo_logo']['value']))
            <div class="upload-logo">
                <div class="input-group-append">
                    <div class="img">
                        <img src="{{ asset(config('config.icon_path') . $settings['eu_vs_disinfo_logo']['value']) }}" alt="Logo">
                    </div>
                    <button class="btn btn-danger delete-file" type="button" style="height: 40px;"
                        data-file="{{ $settings['eu_vs_disinfo_logo']['value'] }}" data-locale="{{ $locale }}">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        @endif
        <input type="file" name="nonTranslatables[{{ $key1 }}]" class="custom-file-input" id="logo_{{ $locale }}">
        <label class="custom-file-label" for="logo_{{ $locale }}">{{ trans('admin.'.$key1) }} {{ trans('admin.choose_file') }}</label>
    </div> 
</div>