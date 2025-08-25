@extends('admin.layouts.app')
@push('name')
@endpush
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card-box">
            <div style="display: flex; align-items:center; justify-content: space-between; padding:20px 0">
                <h4 class="mt-0 header-title float-left">{{ $section [app()->getLocale()]->title }}</h4>

               
                <a href="/{{ app()->getLocale() }}/admin/section/{{ $section->id }}/posts/create" type="button"
                    class="float-right btn btn-info waves-effect width-md waves-light">{{ trans('admin.add_post') }}</a>
              
            </div>

            <div class="container-fluid">

                <div class="row">
                    @foreach ($posts as $post)

                    <div class="col-xl-4 col-md-6">
                        <div class="card-box">
                            <div class="dropdown float-right">
                                <a href="#" class="dropdown-toggle arrow-none card-drop" data-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="mdi mdi-dots-vertical"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    @if (count($post->submissions) > 0)
                                    <a  href="/{{ app()->getLocale() }}/admin/submissions?post_id={{ $post->id }}"  style="padding: 0.375rem 1.2rem;
                                        color: #35b8e0;">
                                        <i class="mdi  noti-icon {{ count($notifications) > 0 ? 'mdi-email pos-rel' : 'mdi-email-open'  }}">
                                            @if (count($notifications) > 0 )
                                            <span class="badge badge-danger rounded-circle noti-icon-badge sidebar-badge">{{ count($notifications) }}</span>
                                     
                                            @endif
                                        </i>
                                        <span > {{ trans('admin.submissions') }} </span>
                                    </a>
                     
                                            @endif
                                    <a style="color: #35b8e0"
                                        href="{{ route('post.edit', [app()->getLocale(), $post->id]) }}"
                                        class="dropdown-item">{{ trans('admin.edit') }}</a>
                                   
                                    <a style="color: #ff3535"
                                        href="{{ route('post.destroy', [app()->getLocale(), $post->id]) }}"
                                        data-confirm="დარწმუნებული ხართ რომ გსურთ პოსტის წაშლა?"
                                        class="dropdown-item delete">{{ trans('admin.delete') }}</a>


                                </div>
                            </div>

                            <h4 class="header-title mt-0 ">{{ $post->title }} <br> </h4>

                            @if ($post->thumb == null && isset(json_decode($post->locale_additional)->youtube_Link))
                            <img class="img-fluid card-image"
                                src="{{ getVideoImage(json_decode($post->locale_additional)->youtube_Link) }}"
                                alt="Card image cap">
                            @else
                            @if ($post->thumb != null)
                            <img class="img-fluid card-image" src="/uploads/img/thumb/{{ $post->thumb }}"
                                alt="Card image cap">
                            @endif
                            @endif
            
                           
                               

                            
                        </div>

                    </div><!-- end col -->
                    @endforeach
                    <div class="col-lg-12">
                        {{ $posts->links('admin.layouts.pagination') }}
                    </div>
                </div>
                <!-- end row -->
            </div>
        </div>
    </div>
</div>

<script>
    var deleteLinks = document.querySelectorAll('.delete');

    for (var i = 0; i < deleteLinks.length; i++) {
        deleteLinks[i].addEventListener('click', function (event) {
            event.preventDefault();

            var choice = confirm(this.getAttribute('data-confirm'));

            if (choice) {
                window.location.href = this.getAttribute('href');
            }
        });
    }

</script>
@endsection
