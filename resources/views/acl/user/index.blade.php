@extends(config('theme.layouts.admin'),[
    'title' => 'User',
    'breadcrumb' => [
        [
            'label' => 'Dashboard',
            'url' => route('dashboard')
        ],
        ['label' => 'User Management'],
        [
            'label' => 'User'
        ],
    ]

])

@section('content')

    @component(config('theme.components').'.filter',['name' => 'user_filter','buttonLocation' => '#user_card .card-toolbar'])
        {{ 
            Form::select2Input('role_id',null,route('role.list'),[
                'labelText' => 'Role',
                'formAlignment' => 'vertical',
                'elOptions' => [
                    'id' => 'select2-filter-role_id'
                ],
                'ajaxParams' => ['exclude_master' => true]
            ]) 
        }}
    @endcomponent

    @component(config('theme.components').'.card',['name' => 'user_card'])
        {!!
            DatatableBuilderHelper::render(route('user.datatable'), [
                'columns' => [
                    'action',
                    'name','email','active',
                    [
                        'attribute' => 'updated_at',
                        'label' => 'Last Updated'
                    ],
                    [
                        'attribute' => 'role.name',
                        'label' => 'Role'
                    ],
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
                        ['targets' => 3,'className' => 'text-center'],
                        ['targets' => [6,7],'searchable' => false]
                    ],
                ],
                'elOptions' => [
                    'id' => 'user_table',
                ],
                'toolbarLocation' => '#user_card .card-toolbar',
                'titleLocation' => '#user_card .card-title',
                'create' => [
                    //'text' => 'Create User',
                    'modal' => 'modal_form_user'
                    // 'url' => route('user.create')
                ],
                'filter_form' => 'user_filter',
                'export' => [
                    'url' => route('user.export'),
                    'title' => 'User'
                ],
                'import' => [
                    'url' => route('user.import'),
                    'template' => route('user.download-template'),
                    'title' => 'User'
                ],
                'job_trace' => [
                    'title' => 'User',
                    'filters' => [
                        App\Exports\ACL\Users\UserExport::class,
                        App\Imports\ACL\Users\UserImport::class
                    ],
                ],
                'noWrap' => true,
            ])
        !!}
    @endcomponent

    @component(config('theme.components').'.modal-ajax-form',[
        'title' => 'Create User',
        'name' => 'modal_form_user',
        'table' => 'user_table',
        'additionalClass' => 'mw-650px',
        'size' => '',
        'url' => route('user.save'),
    ])

        {{ Form::textInput('name',null,['formAlignment' => 'vertical', 'required' => 'required']) }}

        {{ Form::emailInput('email',null,['formAlignment' => 'vertical', 'required' => 'required']) }}

        {{ 
            Form::select2Input('role_id',null,route('role.list'),[
                'labelText' => 'Role',
                'formAlignment' => 'vertical', 
                'required' => 'required',
                'ajaxParams' => ['exclude_master' => 1]
            ]) 
        }}

        {{ Form::passwordInput('password',null,['formAlignment' => 'vertical', 'withEyeIcon' => true]) }}

        {{ Form::passwordInput('password_confirmation',null,['formAlignment' => 'vertical', 'withEyeIcon' => true]) }}

        {{ 
            Form::switchInput('active', null, [
                'elOptions' => [
                    'checked' => 'checked'
                ],
                'formAlignment' => 'vertical',
                'switchText' => '<span id="switchText_active"></span>'
            ]) 
        }}

    @endcomponent

@endsection

@push('additional-js')

<script type="text/javascript">

    $('#modal_form_user').on('shown.bs.modal', function (e) {
        if(!$('input[name="id"][type="hidden"]').val()) $('#active').prop('checked', true).trigger('change')
    })

    $('#active').on('change', function(){
        $('#switchText_active').html('')

        if(!$(this).prop('checked') && !$('input[name="id"][type="hidden"]').val()){
            $('#switchText_active').html('User harus melakukan aktifasi di email yang terdaftar terlebih dahulu')
        }
    })

</script>

@endpush
