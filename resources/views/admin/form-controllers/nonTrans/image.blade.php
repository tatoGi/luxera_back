<div class="form-group mt-20">
    <div class="row">
        <div class="col-lg-6">
            <label>{{ trans('admin.' . $key) }}</label> <br>
            <input type="file" name="thumb">
        </div>
        @if (isset($post) && isset($post->thumb))
            <div class="col-lg-6 imagePreview">
                <img src="{{ '/' . config('config.image_path') . config('config.thumb_path') . $post->thumb }}"
                    class="slide_image">
                <button class="delete-image" data-id="{{ $post->id }}" data-token="{{ csrf_token() }}"
                    data-route="{{ route('post.delete-image', [app()->getLocale(), $post->id]) }}"
                    delete="{{ $post->thumb }}">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </button>
            </div>
        @endif
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
        $('.delete-image').on('click', function(e) {
            e.preventDefault(); // Prevent the default form submission behavior
            const imageName = $(this).data('image');
            var Url = $(this).data('route');
            var lang = $(this).data("lang");
            var TOKEN = $(this).data("token");
            var id = $(this).data("id");
            if (confirm("დოკუმენტის წაშლა!?")) {
                $.ajax({
                    url: Url,
                    method: 'DELETE',
                    data: {
                        image_name: imageName,
                        id: id,
                        _token: TOKEN,
                        lang: lang
                    },
                    success: function(response) {
                        // Handle success, e.g., remove the image and related elements from the DOM
                        $('.slide_image').remove();
                        $('.delete-image').remove();
                        $('.imagePreview')
                            .hide(); // Add this line to hide the div when the image is removed
                    },
                    error: function(error) {
                        console.error('Error deleting image:', error);
                    }
                });
                $(this).parents('.imagePreview').hide('slow');
            }
        });
    });
</script>
<style>
    .delete-image {
        top: 0;
        right: -30px;
        color: red;
        background-color: transparent;
        border: none;
        cursor: pointer;
        font-size: 15px;
    }


    .delete-image:hover {
        color: darkred;
    }
</style>