@php
    $key   = 'jabatan';
    $route = str_replace('_', '-', $key);
    $label = ucwords(str_replace('_', ' ', $key));
@endphp

@extends(config('theme.layouts.admin'),[
    'title' => $label,
    'breadcrumb' => [
        [
            'label' => 'Dashboard',
            'url' => route('dashboard')
        ],
        ['label' => "$label Management"],
        [
            'label' => $label
        ],
    ]

])

@section('content')

    @component(config('theme.components').'.card',['name' => "{$key}_card"])
        {!!
            DatatableBuilderHelper::render(route("{$route}.datatable"), [
                'columns' => [
                    'action',
                    'name','employment_status',
                    [
                        'attribute' => 'created_by.name',
                        'label' => 'Created By'
                    ],
                    [
                        'attribute' => 'updated_by.name',
                        'label' => 'Updated By'
                    ],
                ],
                'pluginOptions' => [
                    'columnDefs' => [
                        ['targets' => 0,'width' => '10px'],
                        ['targets' => [3,4],'searchable' => false]
                    ],
                ],
                'elOptions' => [
                    'id' => "{$key}_table",
                ],
                'toolbarLocation' => "#{$key}_card .card-toolbar",
                'titleLocation' => "#{$key}_card .card-title",
                'create' => [
                    //'text' => "Create {$label}",
                    'modal' => "modal_form_{$key}"
                    // 'url' => route("{$route}.create")
                ],
                'filter_form' => "{$key}_filter",
                'noWrap' => true,
            ])
        !!}
    @endcomponent

    @component(config('theme.components').'.modal-ajax-form',[
        'title' => "Create {$label}",
        'name' => "modal_form_{$key}",
        'table' => "{$key}_table",
        'additionalClass' => 'mw-650px',
        'size' => '',
        'url' => route("{$route}.save"),
    ])

        {{ Form::textInput('name',null,['formAlignment' => 'vertical', 'required' => 'required']) }}
        {{ Form::textInput('employment_status',null,['formAlignment' => 'vertical', 'required' => 'required']) }}

    @endcomponent

@endsection