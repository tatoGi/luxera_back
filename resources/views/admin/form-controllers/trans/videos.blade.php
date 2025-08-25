<div class="form-group">
    
    {{ Form::label(trans('admin.'.$key), null, ['class' => 'control-label']) }}
    
	<div class="add-card"> 
		<button class="btn btn-primary waves-effect waves-light mr-1 add-video-card" style="margin: 15px 0px;">{{ trans('admin.add') }}</button>
		<div class="video-cards">
			@if (isset($post) && isset($post->translate($locale)->{$key}))
			
				@foreach ($post->translate($locale)->{$key} as $item)
					@if ($item !== null)
					
						<div class="video-card">
							<input class="form-control" name="{{ $locale }}[{{ $key }}][]" type="text" value="{{ $item }}">
							<button class="btn btn-danger waves-effect waves-light mr-1 remove-video-card"  >{{ trans('admin.remove') }}</button>
						</div>
					@endif
				@endforeach
			@else
				<div class="video-card">
					<input class="form-control" name="{{ $locale }}[{{ $key }}][]" type="text">
					<button class="btn btn-danger waves-effect waves-light mr-1 remove-video-card"  >{{ trans('admin.remove') }}</button>
				</div>
			@endif
		</div>

		
		<div class="hidden-video-card">
			<input class="form-control" name="{{ $locale }}[{{ $key }}][]" type="text">
			<button class="btn btn-danger waves-effect waves-light mr-1 remove-video-card"  >{{ trans('admin.remove') }}</button>
		</div>
		
	</div>

</div>