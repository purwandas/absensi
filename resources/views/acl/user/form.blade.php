@extends(config('theme.layouts'),[
    'title' => 'User Form',
    'breadcrumb' => [
        [
            'label' => 'Dashboard',
            'url' => route('dashboard')
        ],
        [
            'label' => 'User',
            'url' => route('user.index')
        ],
        [
            'label' => @$data ? 'Edit' : 'Create'
        ],
    ]

])

@section('content')
	@component(config('theme.components').'.card',['name' => 'form_user_card','headers' => false])
		@component(config('theme.components').'.ajax-form',[
	        'name' => 'user',
	        'url' => route('user.save'),
	        'redirect' => [
	        	'url' => route('user.index'),
	        ]
	    ])

	        {{ Form::textInput('name',@$data->name) }}

	        {{ Form::emailInput('email',@$data->email) }}

	        {{ 
	            Form::select2Input('role_id',@$data ? [@$data->role->id,@$data->role->name] : null,route('role.list'),[
	                'labelText' => 'Role',
	            ]) 
	        }}

	        {{ Form::passwordInput('password',null) }}

	    @endcomponent
		
	@endcomponent
@endsection