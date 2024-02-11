@php
    $key   = 'absensi';
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
                    'type',
                    [
                        'attribute' => 'user.name',
                        'label' => 'User Name'
                    ],
                    'ip_address','mac_address',
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
                // 'create' => [
                //     //'text' => "Create {$label}",
                //     'modal' => "modal_form_{$key}"
                //     // 'url' => route("{$route}.create")
                // ],
                'filter_form' => "{$key}_filter",
                'noWrap' => true,
            ])
        !!}
    @endcomponent

    @component(config('theme.components').'.filter',['name' => "{$key}_filter",'buttonLocation' => "#{$key}_card .card-toolbar"])
        {{ 
            Form::select2Input('user_id',null,route('user.list'),[
                'labelText'     => 'User',
                'formAlignment' => 'vertical',
                'ajaxParams'    => ['exclude_master' => true],
                'elOptions'     => [
                    'id' => 'select2-filter-user_id'
                ],
            ]) 
        }}

        {{
            Form::dateInput('month', null, [
                'formAlignment' => 'vertical',
                'elOptions'     => [
                    'id' => 'input-filter-month'
                ],
                'pluginOptions' => [
                    'plugins' => '[
                        new monthSelectPlugin({
                          shorthand: true, //defaults to false
                          dateFormat: "m.y", //defaults to "F Y"
                          altFormat: "F Y", //defaults to "F Y"
                          theme: "dark" // defaults to "light"
                        })
                    ]'
                ],
            ])
        }}

        {{
            Form::dateInput('start_date', null, [
                'formAlignment' => 'vertical',
                'elOptions'     => [
                    'id' => 'input-filter-start_date'
                ],
            ])
        }}

        {{
            Form::dateInput('end_date', null, [
                'formAlignment' => 'vertical',
                'elOptions'     => [
                    'id' => 'input-filter-end_date'
                ],
            ])
        }}
    @endcomponent

    @component(config('theme.components').'.modal-ajax-form',[
        'title' => "Create {$label}",
        'name' => "modal_form_{$key}",
        'table' => "{$key}_table",
        'additionalClass' => 'mw-650px',
        'size' => '',
        'url' => route("{$route}.save"),
    ])

        {{ 
            Form::select2Input('user_id',null,route('user.list'),[
                'labelText' => 'User',
                'formAlignment' => 'vertical', 
                'required' => 'required',
                'ajaxParams' => ['exclude_master' => 1]
            ]) 
        }}
        {{ Form::textInput('mac_address',null,['formAlignment' => 'vertical', 'required' => 'required']) }}
        {{ Form::textInput('ip_address',null,['formAlignment' => 'vertical', 'required' => 'required']) }}


        {{
            Form::dateInput('date', null, [
                'formAlignment' => 'vertical',
                'required' => 'required',
            ])
        }}
    @endcomponent

@endsection