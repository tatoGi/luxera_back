
<div class="form-group">
    {{ Form::label(trans('admin.'.$key), null, ['class' => 'control-label']) }}
    <select name="{{ $key }}[]" class="form-control select2" id="" multiple>
      
        @if(getCoalitionBanner()->coalition != '')
       
        @foreach (getCoalitionBanner()->coalition as $ke3 => $item)
      
        <option value="{{ $item->id }}"
             @if(isset($post->coalition_banner) &&   in_array($item->id, $post->coalition_banner))
         
            selected @endif>
           
            {{$item[app()->getlocale()]->title}}
          
        </option>
        @endforeach
        @endif
    </select>
</div>