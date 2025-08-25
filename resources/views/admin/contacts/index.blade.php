<x-admin.admin-layout>
    <section class="container mx-auto p-6 font-mono">
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
            <div class="w-full overflow-x-auto">
                <table class="w-full">
                   
                    <thead>
                        <tr class="text-md font-semibold tracking-wide text-left text-gray-900 bg-gray-100 uppercase border-b border-gray-600">
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Subject</th>
                            <th class="px-4 py-3">Massage</th>
                            <th class="px-4 py-3">Created At</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach($contacts as $contact)
                        <tr class="text-gray-700">
                            <td class="px-4 py-3 border">{{ $contact->name }}</td>
                            <td class="px-4 py-3 border">{{ $contact->email }}</td>
                            <td class="px-4 py-3 border">{{ $contact->subject }}</td>
                            <td class="px-4 py-3 border">{{ $contact->message }}</td>
                            <td class="px-4 py-3 border">{{ $contact->created_at->format('m/d/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</x-admin.admin-layout>
