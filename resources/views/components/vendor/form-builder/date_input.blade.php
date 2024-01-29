@php
if (!is_array($attributes)) $attributes = [];
$config = FormBuilderHelper::setupDefaultConfig($name, $attributes);

$config['elOptions']['readonly'] = true;

$config['pluginOptions'] = array_merge([
	"altInput" => true,
    "altFormat" => "F j, Y",
    "dateFormat" => "Y-m-d",
    "disableMobile"=> true
],$config['pluginOptions'] ?? []);

if($config['elOptions']['class']) $config['elOptions']['class'] .= ' form-control-solid';

$plugins = NULL;
if (isset($config['pluginOptions']['plugins'])) {
	$plugins = $config['pluginOptions']['plugins'];
	unset($config['pluginOptions']['plugins']);
}

if($config['elOptions']['class']) $config['elOptions']['class'] .= ' form-control-solid';

@endphp

<div class="{{ $config['divContainerClass'] }} {{ !$errors->has($name) ?: 'has-danger' }}">
	@if ($config['useLabel'])
	<div class="row">
		<div class="{{ $config['labelContainerClass'] }}">
			<label class="{{ @$config['required'] ? 'required' : '' }} col-form-label">
				{!! $config['labelText'] !!}
			</label>
		</div>
		<div class="{{ $config['inputContainerClass'] }}">
	@endif
			@if (!empty($config['addonsConfig']))
			<div class="input-group">
				@if ($config['addonsConfig']['position'] === 'left')
				<span class="input-group-addon addon-left-side">{{ $config['addonsConfig']['text'] }}</span>
				@endif
			@endif

				{{ Form::text($name, $value, $config['elOptions']) }}

			@if (!empty($config['addonsConfig']))
				@if ($config['addonsConfig']['position'] === 'right')
				<span class="input-group-addon addon-right-side">{{ $config['addonsConfig']['text'] }}</span>
				@endif
			</div>
			@endif

			<div class="error-container">
				@if($errors->has($name))
	            <div class="form-control-feedback">{{ $errors->first($name) }}</div>
				@endif
			</div>

            {!! @$config['info'] !!}

	@if ($config['useLabel'])
		</div>
	</div>
	@endif
</div>


@section('date-input-css')
	@if($plugins)
	<link rel="stylesheet" type="text/css" href="{{asset("css/monthSelectPlugin.css")}}">
	@endif
@endsection
@section('date-input-js')
	@if($plugins)
	<script src="{{asset("js/monthSelectPlugin.js")}}"></script>
	@endif
@endsection
@push('additional-js')
	<script type="text/javascript">
		var datepicker_{{ $name }} = {!! json_encode($config['pluginOptions']) !!}
		$(document).ready(function() {
			@if($plugins)
			datepicker_{{ $name }}.plugins = {!! $plugins !!};
			@endif
			$("#{{ $config['elOptions']['id'] }}").flatpickr(datepicker_{{ $name }});
		});
	</script>
@endpush