@php
	$key   = 'jabatan';
	$label = ucwords(str_replace('-', ' ', $key));
@endphp

@extends(config('theme.layouts'),[
    'title' => "$label Form",
    'breadcrumb' => [
        [
            'label' => 'Dashboard',
            'url' => route('dashboard')
        ],
        [
            'label' => $label,
            'url' => route("$key.index")
        ],
        [
            'label' => @$data ? 'Edit' : 'Create'
        ],
    ]

])

@section('content')
	@component(config('theme.components').'.card',['name' => "form_{$key}_card",'headers' => false])
		@component(config('theme.components').'.ajax-form',[
	        'name' => $key,
	        'url' => route("$key.save"),
	        'redirect' => [
	        	'url' => route("$key.index"),
	        ]
	    ])

	        {{ Form::textInput('name',@$data->name) }}

	    @endcomponent
		
	@endcomponent
@endsection