<x-admin.admin-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    {{ $pageTypeConfig['name'] ?? 'Posts' }} - {{ $page->title }}
                </h1>
                <p class="text-gray-600 mt-1">Manage posts for this page</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.pages.management.manage', ['locale' => app()->getLocale(), 'page' => $page->id]) }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    {{ __('admin.Back to Page') }}
                </a>
                <a href="{{ route('admin.pages.posts.create', ['locale' => app()->getLocale(), 'page' => $page->id]) }}"
                   class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    {{ __('admin.Add Post') }}
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            @if($posts->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('admin.Title') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('admin.Status') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('admin.Sort Order') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('admin.Created') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('admin.Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($posts as $post)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        @php
                                            // Try to get title or question for current locale
                                            $title = $post->getAttributeForLocale('title', app()->getLocale()) 
                                                  ?: $post->getAttributeForLocale('question', app()->getLocale()) 
                                                  ?: 'Untitled';
                                        @endphp
                                        {{ Str::limit($title, 60) }}
                                    </div>
                                    @php
                                        $excerpt = $post->getAttributeForLocale('excerpt', app()->getLocale()) 
                                                ?: $post->getAttributeForLocale('summary', app()->getLocale());
                                    @endphp
                                    @if($excerpt)
                                        <div class="text-sm text-gray-500 mt-1">
                                            {{ Str::limit($excerpt, 100) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $post->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $post->active ? __('admin.Active') : __('admin.Inactive') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $post->sort_order }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $post->created_at ? $post->created_at->format('M d, Y H:i') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.pages.posts.edit', ['locale' => app()->getLocale(), 'page' => $page->id, 'post' => $post->id]) }}"
                                       class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        <i class="fas fa-edit"></i> {{ __('admin.Edit') }}
                                    </a>
                                    <form method="POST" action="{{ route('admin.pages.posts.destroy', ['locale' => app()->getLocale(), 'page' => $page->id, 'post' => $post->id]) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('{{ __('admin.Are you sure?') }}')">
                                            <i class="fas fa-trash"></i> {{ __('admin.Delete') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-file-alt text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('admin.No posts yet') }}</h3>
                    <p class="text-gray-500 mb-6">{{ __('admin.Create your first post to get started') }}</p>
                    <a href="{{ route('admin.pages.posts.create', ['locale' => app()->getLocale(), 'page' => $page->id]) }}"
                       class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg inline-flex items-center transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        {{ __('admin.Create First Post') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-admin.admin-layout>
