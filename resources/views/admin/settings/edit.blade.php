<x-admin.admin-layout>

<div class="row">
    <div class="col-xl-12">
        <div class="card-box">

            <form method="POST" action="{{ route('settings.update', app()->getLocale()) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Include your form fields here -->
                <div class="form-group">
                  
                    @include('admin.settings.form')
                </div>
            </form>

        </div>
    </div>
</div>
</x-admin.admin-layout>
