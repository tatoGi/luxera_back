<x-admin.admin-layout>
<div class="flex flex-wrap my-5 -mx-2">

    



    <div class="flex justify-between w-full px-2">

        <h3 class="text-xl font-bold text-center ">{{ __('admin.pages') }}</h3>

        <a href="/{{ app()->getlocale() }}/admin/pages/create" class="btn  flex overflow-hidden relative w-40 bg-blue-500 text-white py-2 px-4 rounded-xl font-bold uppercase 

        before:block before:absolute before:h-full before:w-1/2 before:rounded-full

        before:bg-orange-400 before:top-0 before:left-1/4 before:transition-transform before:opacity-0 

        before:hover:opacity-100 hover:text-orange-200 hover:before:animate-ping transition-all duration-300"

        style="font-size: 0.8rem;" >

            <span class="relative">{{ __('admin.Create_page') }}</span>

            <i class="material-icons-outlined ml-2">add</i>

        </a>

    </div>





     <div class="dd !w-full !max-w-full mt-5" id="nestable" data-route="/{{ app()->getLocale() }}/admin/pages/arrange">

  

       @include('admin.pages.list', ['pages' => $pages])

    </div>

    <!-- End Nestable Container -->

</div>





</x-admin.admin-layout>


