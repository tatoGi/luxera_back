<x-admin.admin-layout>
    <section class="container mx-auto p-6 font-mono">
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
            <div class="w-full overflow-x-auto">
                <div class="mt-1 mb-2">
                    <h1>Subscribers</h1>
                </div>
                
                <table class="w-full">
                   
                    <thead>
                        <tr class="text-md font-semibold tracking-wide text-left text-gray-900 bg-gray-100 uppercase border-b border-gray-600">
                            
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Created At</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach($subscribers as $sub)
                        <tr class="text-gray-700">
                            <td class="px-4 py-3 border">{{ $sub->email }}</td>
                            <td class="px-4 py-3 border">{{ $sub->created_at->format('m/d/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</x-admin.admin-layout>
