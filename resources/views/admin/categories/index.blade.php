<x-admin.admin-layout>



<div class="flex flex-wrap my-5 -mx-2">

    <div class="flex justify-end w-full px-2">

        <a href="/{{ app()->getLocale() }}/admin/categories/create" class="btn  flex overflow-hidden relative w-32 bg-blue-500 text-white py-2 px-4 rounded-xl font-bold uppercase 

        before:block before:absolute before:h-full before:w-1/2 before:rounded-full

        before:bg-orange-400 before:top-0 before:left-1/4 before:transition-transform before:opacity-0 

        before:hover:opacity-100 hover:text-orange-200 hover:before:animate-ping transition-all duration-300"

        style="font-size: 0.8rem;" >

            <span class="relative">Create Category</span>

            <i class="material-icons-outlined ml-2">add</i>

        </a>

    </div>

    

    

    <div class="flex justify-center w-full h-full min-h-screen">

        <div class="overflow-auto lg:overflow-visible w-full h-full ">

            <table class="table text-gray-200 border-separate space-y-6 text-lg w-full h-full">

                <thead class="bg-gray-200 text-gray-800">

                    <tr>

                        <th class="p-3 text-center">Icon</th> <!-- New th for icon -->

                        <th class="p-3 text-center">Title</th>

                        <th class="p-3 text-center">Description</th>

                        <th class="p-3 text-center">Parent Category</th>

                        <th class="p-3 text-center">Status</th>

                        <th class="p-3 text-center">Action</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($categories as $category)

                    <tr class="bg-gray-200 text-gray-800">

                        <td class="p-3">

                            <!-- Add your icon here -->

                            

                            @if($category->icon)
                                <img src="{{ asset('storage/' . $category->icon) }}" alt="Category Icon" class="w-12 h-12 object-cover rounded-lg border mx-auto">
                            @else
                                <span class="text-gray-400 text-sm">No image</span>
                            @endif

                        </td>

                        <td class="px-3 text-center text-gray-800">{{ $category->title }}</td>

                        <td class="border px-4 py-2 text-gray-800">{!!$category->description !!}</td>



                        <td class="p-3 text-center text-gray-800">{{ $category->parent ? $category->parent->title : 'N/A' }}</td>

                        <td class="p-3 text-center text-gray-800  font-bold">{{ $category->active ? 'Active' : 'Inactive' }}</td>

                        <td class="p-3 text-center text-gray-800">

                            <a href="/{{ app()->getLocale() }}/admin/categories/{{ $category->id }}/edit" class="text-blue-400 hover:text-gray-100 mx-2">

                                <i class="fas fa-pencil-alt  !text-base"></i>

                            </a>

                            <form action="{{ route('categories.destroy', [app()->getlocale(), $category->id]) }}" method="post" class="inline delete" onsubmit="return confirm('Do you want to delete this product?');">

                                @csrf 

                                @method('DELETE')

                                <button class="bg-red-500  !text-sm text-white py-1 px-2 rounded" type="submit">

                                    <i class="fas fa-trash !text-sm"></i> {{ __('admin.Delete') }}

                                </button>

                            </form>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

            

<div class="flex justify-center w-full">

    {{ $categories->links('admin.pagination.pagination') }}

    <!-- Pagination links -->

</div>

        </div>

    </div>

</div>





<style>

    .table {

        border-spacing: 0 15px;

    }



    i {

        font-size: 1.5rem !important;

    }



    .table tr {

        border-radius: 20px;

    }



</style> 

<script>

    document.addEventListener('DOMContentLoaded', function () {

        var deleteLinks = document.querySelectorAll('[data-confirm]');



        for (var i = 0; i < deleteLinks.length; i++) {

            deleteLinks[i].addEventListener('click', function(event) {

                event.preventDefault();



                var choice = confirm(this.getAttribute('data-confirm'));



                if (choice) {

                    window.location.href = this.getAttribute('href');

                }

            });

        }

    });

</script>

</x-admin.admin-layout>

