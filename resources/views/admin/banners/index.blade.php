<div class="w-full py-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-800">{{ isset($page_id) ? 'Page Banners' : 'Banners' }}</h2>
        <a href="/{{ app()->getLocale() }}/admin/banners/create/{{ $page_id ?? '' }}"
           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
            <i class="material-icons-outlined mr-2">add</i>
            {{ __('admin.Create New Banner') }}
        </a>
    </div>
    @if(session('message'))
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin.Icon') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin.Title') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin.Description') }}</th>
                        @if(!isset($page_id))<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin.Parent Banner') }}</th>@endif
                        @if(isset($page_id))<th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin.Sort Order') }}</th>@endif
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin.Status') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin.Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($banners as $banner)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($banner->thumb)
                                <img src="{{ asset('storage/' . $banner->thumb) }}"
                                     alt="{{ $banner->title }}"
                                     class="w-12 h-12 rounded-lg object-cover mx-auto">
                            @else
                                <span class="text-gray-400">No image</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $banner->title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{!! Str::limit($banner->translate(app()->getlocale())->desc, 50) !!}</td>
                        @if(!isset($page_id))
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $banner->parent ? $banner->parent->title : 'None' }}</td>
                        @endif
                        @if(isset($page_id))
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $banner->pivot->sort ?? 'N/A' }}</span>
                        </td>
                        @endif
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $banner->status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $banner->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('banners.edit', ['banner' => $banner->id, 'locale' => app()->getLocale()] + (isset($page_id) ? ['page_id' => $page_id] : [])) }}"
                                   class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition-colors"
                                   title="{{ __('admin.Edit') }}">
                                    <i class="material-icons-outlined text-sm">edit</i>
                                </a>
                                @if(isset($page_id))
                                    <!-- Detach from page -->
                                    <button type="button" 
                                            class="text-orange-600 hover:text-orange-900 p-2 rounded-lg hover:bg-orange-50 transition-colors detach-banner"
                                            data-banner-id="{{ $banner->id }}"
                                            data-page-id="{{ $page_id }}"
                                            title="Remove from Page">
                                        <i class="material-icons-outlined text-sm">remove_circle</i>
                                    </button>
                                @endif
                                @if(!isset($page_id))
                                    <!-- Delete banner entirely -->
                                    <form action="{{ route('banners.destroy', ['banner' => $banner->id, 'locale' => app()->getLocale()]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-colors"
                                                onclick="return confirm('Are you sure you want to delete this banner?')"
                                                title="Delete">
                                            <i class="material-icons-outlined text-sm">delete</i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ isset($page_id) ? '6' : '6' }}" class="px-6 py-8 text-center text-gray-500">
                            {{ isset($page_id) ? 'No banners attached to this page' : 'No banners found' }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
            @include('admin.pagination.pagination', ['paginator' => $banners])
        </div>

    </div>

    @if(isset($page_id) && isset($availableBanners) && $availableBanners->count() > 0)
    <!-- Available Banners to Attach -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden mt-6">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h5 class="text-lg font-medium text-gray-900">Available Banners to Attach</h5>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Icon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($availableBanners as $banner)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($banner->thumb)
                                <img src="{{ asset('storage/' . $banner->thumb) }}"
                                     alt="{{ $banner->title }}"
                                     class="w-12 h-12 rounded-lg object-cover mx-auto">
                            @else
                                <span class="text-gray-400">No image</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $banner->title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{!! Str::limit($banner->translate(app()->getlocale())->desc, 50) !!}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $banner->status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $banner->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('banners.edit', ['banner' => $banner->id, 'locale' => app()->getLocale(), 'page_id' => $page_id]) }}"
                                   class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition-colors"
                                   title="{{ __('admin.Edit') }}">
                                    <i class="material-icons-outlined text-sm">edit</i>
                                </a>
                                <button type="button" 
                                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-sm flex items-center space-x-1 attach-banner transition-colors"
                                        data-banner-id="{{ $banner->id }}"
                                        data-page-id="{{ $page_id }}"
                                        title="Attach to Page">
                                    <i class="material-icons-outlined text-sm">add_circle</i>
                                    <span>Attach</span>
                                </button>
                                <form action="{{ route('banners.destroy', ['banner' => $banner->id, 'locale' => app()->getLocale(), 'page_id' => $page_id]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-colors"
                                            onclick="return confirm('Are you sure you want to delete this banner? This action cannot be undone.')"
                                            title="Delete Banner">
                                        <i class="material-icons-outlined text-sm">delete</i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
            @include('admin.pagination.pagination', ['paginator' => $availableBanners])
        </div>
    </div>
    @endif
</div>

@if(isset($page_id))
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Attach banner functionality
    document.querySelectorAll('.attach-banner').forEach(button => {
        button.addEventListener('click', function() {
            const bannerId = this.dataset.bannerId;
            const pageId = this.dataset.pageId;
            const locale = '{{ app()->getLocale() }}';
            
            if (confirm('Are you sure you want to attach this banner to the page?')) {
                fetch(`/${locale}/admin/pages/${pageId}/banners/attach`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        banner_id: bannerId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while attaching the banner.');
                });
            }
        });
    });
    
    // Detach banner functionality
    document.querySelectorAll('.detach-banner').forEach(button => {
        button.addEventListener('click', function() {
            const bannerId = this.dataset.bannerId;
            const pageId = this.dataset.pageId;
            const locale = '{{ app()->getLocale() }}';
            
            if (confirm('Are you sure you want to remove this banner from the page?')) {
                fetch(`/${locale}/admin/pages/${pageId}/banners/${bannerId}/detach`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while detaching the banner.');
                });
            }
        });
    });
});
</script>
@endif
