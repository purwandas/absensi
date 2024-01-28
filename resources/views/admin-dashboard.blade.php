@extends(config('theme.layouts.admin'),[
    'title' => 'Dashboard',
    'breadcrumb' => [
        [
            'label' => 'Dashboard',
            'url' => route('admin.dashboard')
        ],
        [
            'label' => 'Dashboard'
        ],
    ]

])

@section('content')

    <div class="alert alert-warning text-dark">
        Under Construction
    </div>

@endsection
