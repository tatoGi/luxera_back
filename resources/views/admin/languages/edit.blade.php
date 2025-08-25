<x-admin.admin-layout>
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Edit Language: {{ $language->name }}</h1>
            <a href="/{{app()->getlocale()}}/admin/languages" class="text-indigo-600 hover:text-indigo-900">
                &larr; Back to Languages
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <form action="{{ route('admin.languages.update', ['locale' => app()->getLocale(), 'language' => $language]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Display Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $language->name) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="native_name" class="block text-sm font-medium text-gray-700">Native Name *</label>
                            <input type="text" name="native_name" id="native_name" value="{{ old('native_name', $language->native_name) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            @error('native_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700">Language Code *</label>
                            <input type="text" name="code" id="code" value="{{ old('code', $language->code) }}" 
                                   class="mt-1 block w-full bg-gray-100 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                   readonly>
                            <p class="mt-1 text-xs text-gray-500">ISO 639-1 code (2 letters) - Cannot be changed after creation</p>
                        </div>

                        <div class="flex items-end">
                            <div class="flex items-center h-5">
                                <input id="is_default" name="is_default" type="checkbox" 
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                       {{ old('is_default', $language->is_default) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_default" class="font-medium text-gray-700">Set as default language</label>
                                <p class="text-gray-500">This will be the default language for new users</p>
                            </div>
                        </div>

                        <div class="flex items-end">
                            <div class="flex items-center h-5">
                                <input id="is_active" name="is_active" type="checkbox" 
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                       {{ old('is_active', $language->is_active) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_active" class="font-medium text-gray-700">Active</label>
                                <p class="text-gray-500">Make this language available on the site</p>
                            </div>
                        </div>

                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700">Sort Order</label>
                            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $language->sort_order) }}" 
                                   class="mt-1 block w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <p class="mt-1 text-xs text-gray-500">Lower numbers appear first</p>
                        </div>
                    </div>

                    <div class="mt-8 pt-5 border-t border-gray-200">
                        <div class="flex justify-between">
                           
                            <div>
                                <a href="{{ route('admin.languages.index', ['locale' => app()->getLocale()]) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cancel
                                </a>
                                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Translations Section -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Translations</h2>
                
                <div class="mb-4">
                    <div class="flex justify-between items-center">
                        <div class="flex space-x-2">
                            <select id="translation-group" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">All Groups</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group }}">{{ $group }}</option>
                                @endforeach
                            </select>
                            <input type="text" id="translation-search" placeholder="Search translations..." 
                                   class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div class="flex space-x-2">
                            <button id="add-translation" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Add New Translation
                            </button>
                            <button id="export-translations" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Export to Files
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow overflow-hidden sm:rounded-md">
                    <ul id="translations-list" class="divide-y divide-gray-200">
                        @foreach($translations as $group => $groupTranslations)
                            <li class="group">
                                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                                    <h3 class="text-sm font-medium text-gray-900">{{ $group }}</h3>
                                </div>
                                <ul class="divide-y divide-gray-200">
                                    @foreach($groupTranslations as $translation)
                                        <li class="translation-item hover:bg-gray-50" data-group="{{ $translation->group }}">
                                            <div class="px-4 py-3 flex items-center justify-between">
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-indigo-600 truncate">{{ $translation->key }}</p>
                                                </div>
                                                <div class="ml-4 flex-shrink-0">
                                                    <button type="button" class="edit-translation mr-2 text-sm font-medium text-indigo-600 hover:text-indigo-900" 
                                                            data-id="{{ $translation->id }}" 
                                                            data-key="{{ $translation->key }}" 
                                                            data-group="{{ $translation->group }}" 
                                                            data-value="{{ $translation->value }}">
                                                        Edit
                                                    </button>
                                                    <button type="button" class="delete-translation text-sm font-medium text-red-600 hover:text-red-900" 
                                                            data-id="{{ $translation->id }}">
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Translation Modal -->
    <div id="translation-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Edit Translation</h3>
                <div class="mt-2 px-7 py-3">
                    <form id="translation-form" class="space-y-4">
                        @csrf
                        <input type="hidden" id="translation-id" name="id">
                        <div>
                            <label for="translation-key" class="block text-sm font-medium text-gray-700">Key</label>
                            <input type="text" id="translation-key" name="key" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        </div>
                        <div>
                            <label for="translation-group" class="block text-sm font-medium text-gray-700">Group</label>
                            <select id="translation-group-select" name="group" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                @foreach($groups as $group)
                                    <option value="{{ $group }}">{{ $group }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="translation-value" class="block text-sm font-medium text-gray-700">Value</label>
                            <textarea id="translation-value" name="value" rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="save-translation" class="px-4 py-2 bg-indigo-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        Save
                    </button>
                    <button id="cancel-translation" class="ml-3 px-4 py-2 bg-white text-gray-700 text-base font-medium rounded-md shadow-sm border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal elements
            const modal = document.getElementById('translation-modal');
            const modalTitle = document.getElementById('modal-title');
            const translationForm = document.getElementById('translation-form');
            const translationId = document.getElementById('translation-id');
            const translationKey = document.getElementById('translation-key');
            const translationGroup = document.getElementById('translation-group-select');
            const translationValue = document.getElementById('translation-value');
            const saveButton = document.getElementById('save-translation');
            const cancelButton = document.getElementById('cancel-translation');
            const addButton = document.getElementById('add-translation');
            const groupFilter = document.getElementById('translation-group');
            const searchInput = document.getElementById('translation-search');
            const translationsList = document.getElementById('translations-list');

            // Show modal for editing a translation
            document.querySelectorAll('.edit-translation').forEach(button => {
                button.addEventListener('click', function() {
                    translationId.value = this.dataset.id;
                    translationKey.value = this.dataset.key;
                    translationGroup.value = this.dataset.group;
                    translationValue.value = this.dataset.value;
                    modalTitle.textContent = 'Edit Translation';
                    modal.classList.remove('hidden');
                });
            });

            // Show modal for adding a new translation
            addButton.addEventListener('click', function() {
                translationForm.reset();
                translationId.value = '';
                modalTitle.textContent = 'Add New Translation';
                modal.classList.remove('hidden');
            });

            // Close modal
            function closeModal() {
                modal.classList.add('hidden');
            }

            // Save translation
            saveButton.addEventListener('click', function() {
                const formData = new FormData(translationForm);
                const url = '{{ route('admin.languages.update.translations', ['locale' => app()->getLocale(), 'language' => $language]) }}';
                
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        translations: [{
                            id: formData.get('id') || null,
                            key: formData.get('key'),
                            group: formData.get('group'),
                            value: formData.get('value')
                        }]
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Error saving translation');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error saving translation');
                });
            });

            // Delete translation
            document.querySelectorAll('.delete-translation').forEach(button => {
                button.addEventListener('click', function() {
                    if (confirm('Are you sure you want to delete this translation?')) {
                        const translationId = this.dataset.id;
                        const url = '{{ route('admin.languages.delete.translation', ['locale' => app()->getLocale(), 'language' => $language, 'translation' => '__TRANSLATION_ID__']) }}'.replace('__TRANSLATION_ID__', translationId);

                        fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.closest('li').remove();
                            } else {
                                alert('Error deleting translation');
                            }
                        });
                    }
                });
            });

            // Filter translations by group
            groupFilter.addEventListener('change', filterTranslations);
            searchInput.addEventListener('input', filterTranslations);

            function filterTranslations() {
                const groupFilterValue = groupFilter.value.toLowerCase();
                const searchValue = searchInput.value.toLowerCase();
                
                document.querySelectorAll('.translation-item').forEach(item => {
                    const group = item.dataset.group.toLowerCase();
                    const key = item.querySelector('.text-indigo-600').textContent.toLowerCase();
                    const matchesGroup = !groupFilterValue || group === groupFilterValue;
                    const matchesSearch = !searchValue || 
                                         key.includes(searchValue) || 
                                         group.includes(searchValue);
                    
                    if (matchesGroup && matchesSearch) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }

            // Export translations to files
            const exportButton = document.getElementById('export-translations');
            exportButton.addEventListener('click', function() {
                if (confirm('Export database translations to filesystem files? This will update the translation files.')) {
                    const url = '{{ route('admin.languages.export', ['locale' => app()->getLocale(), 'language' => $language]) }}';
                    
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Translations exported successfully! The translation files have been updated.');
                        } else {
                            alert('Error exporting translations');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error exporting translations');
                    });
                }
            });

            // Close modal when clicking cancel or outside
            cancelButton.addEventListener('click', closeModal);
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    closeModal();
                }
            });
        });
    </script>
</x-admin.admin-layout>
