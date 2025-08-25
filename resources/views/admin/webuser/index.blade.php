<x-admin.admin-layout>
    <section class="container mx-auto p-6 font-mono">
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
            <div class="w-full overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-md font-semibold tracking-wide text-left text-gray-900 bg-gray-100 uppercase border-b border-gray-600">
                            <th class="px-4 py-3">First Name</th>
                            <th class="px-4 py-3">Last Name</th>
                            <th class="px-4 py-3">Address</th>
                            <th class="px-4 py-3">Phone</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Created At</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach($webUsers as $webUser)
                        <tr class="text-gray-700">
                            <td class="px-4 py-3 border">{{ $webUser->first_name }}</td>
                            <td class="px-4 py-3 border">{{ $webUser->last_name }}</td>
                            <td class="px-4 py-3 border">{{ $webUser->address }}</td>
                            <td class="px-4 py-3 border">{{ $webUser->phone }}</td>
                            <td class="px-4 py-3 border">{{ $webUser->email }}</td>
                            <td class="px-4 py-3 border">{{ $webUser->created_at->format('m/d/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</x-admin.admin-layout>
