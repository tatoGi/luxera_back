

<ol class="dd-list">
  @foreach ($pages as $section)
  <li class="dd-item cursor-move border-2   border-solid rounded-2xl 
  @if (count($section->children) > 0 ) acordion @endif" data-id="{{ $section->id }}">
      <div class="dd-handle">
          {{ $section->title }}
      </div>
      <div class="change-icons flex items-center space-x-2">
        <a href="{{ route('admin.pages.management.manage', ['page' => $section, 'locale' => app()->getLocale()]) }}" class="fas fa-cog text-blue-500 hover:text-blue-700" title="{{ __('admin.Manage') }}"></a>
        <a href="{{ route('pages.edit', [app()->getlocale(), $section->id]) }}" class="fas fa-pencil-alt text-yellow-500 hover:text-yellow-700" title="{{ __('admin.Edit') }}"></a>
       
        
        <form action="{{ route('pages.destroy', [app()->getlocale(), $section->id]) }}" method="post" class="inline delete" onsubmit="return confirm('{{ __('Are you sure you want to delete this page?') }}');">
          @csrf 
          @method('DELETE')
          <button class="text-red-500 hover:text-red-700 p-0" type="submit" title="{{ __('admin.Delete') }}">
            <i class="fas fa-trash"></i>
          </button>
        </form>
      </div>
      
      @if (count($section->children) > 0 )
      @include('admin.pages.list', ['pages' => $section->children])
      @endif
  </li>
  @endforeach
</ol>


