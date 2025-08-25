<div class="form-group">
    <label data-icon="-">{{ trans('admin.'.$key) }}</label> <br>

    <input type="file" name="{{ $locale }}[image]" @if(isset($banner) && isset($banner->translate($locale)->image))
    value="{{$banner->translate($locale)['image'] }}"
     @endif>

    @if(isset($banner) && isset($banner->translate($locale)->image))
    <br>
    <div class="col-md-8 dfie d-flex">
        <img src="{{ image($banner->translate($locale)['image']) }}" alt="img" style="width: 25%">

        <span class="delete-file" data-id="{{$banner->id}}" data-locale="{{ $locale }}" data-token="{{ csrf_token() }}"
            data-route="/{{ app()->getLocale() }}/banners/{{ $banner->id }}/delete-image"
            delete="{{$banner->translate($locale)['image']}}">X</span>


    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger" role="alert">
        {{ session('error') }}
    </div>
    @endif
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        // Delete file
        $('.delete-file').click(function (e) {
            e.preventDefault();
            var bannerId = $(this).data('id');
            var token = $(this).data('token');
            var route = $(this).data('route');
            var locale = $(this).data('locale');
            if (confirm('Are you sure you want to delete the image?')) {
                $.ajax({
                    url: route,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: token,
                        banner_id: bannerId,
                        locale: locale // Pass the locale parameter to the function
                    },
                    success: function (data) {
                        // Remove image from DOM
                        $('.delete-file[data-id=' + bannerId + '][data-locale=' + locale +
                                ']').closest('.dfie')
                            .remove();
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            }
        });
    });

</script>
<script>
    $(document).ready(function() {
        $('input[name="{{ $locale }}[image]"]').change(function() {
            var fileSize = this.files[0].size;
            var maxSize = 2097152; // Maximum size is 2MB
            if (fileSize > maxSize) {
                alert('File size is greater than 2MB.');
                $('input[name="{{ $locale }}[image]"]').val('');
            }
        });
    });
</script>

@endpush
@push('styles')
<style>
    .alt-text {
        display: block;
        width: 100%;
        height: calc(1.5em + 0.9rem + 2px);
        padding: 0.45rem 0.9rem;
        font-size: .9rem;
        font-weight: 400;
        line-height: 1.5;
        color: #6c757d;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 0.2rem;
    }
    .delete-file{
        margin-left: 10px;
    }
    .dfie{
        margin-top: 10px;
    }
</style>
@endpush
