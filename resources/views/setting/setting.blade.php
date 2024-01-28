@extends(config('theme.layouts.admin'),[
    'title' => 'Setting',
    'breadcrumb' => [
        [
            'label' => 'Dashboard',
            'url' => route('dashboard')
        ],
        [
            'label' => 'Setting',
        ],
    ]

])

@section('content')

    <div class="card mb-5 mb-xl-10">

        <div class="card-body pt-0 pb-0">
            
            <!--begin::Navs-->
            <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                
                <!--begin::Nav item-->
                <li class="nav-item mt-2">
                    <a class="nav-link text-active-primary ms-0 me-10 py-5 active" data-bs-toggle="tab" href="#tabWebsite">Website</a>
                </li>
                <!--end::Nav item-->
                
            </ul>
            <!--begin::Navs-->
        </div>
        

    </div>

    <div class="tab-content">

        
        <div class="tab-pane fade show active" id="tabWebsite" role="tabpanel">
            
            <!--begin::Card-->
            <div class="card mb-5 mb-xl-10">

                <div class="card-header border-0">
                    <!--begin::Card title-->
                    <div class="card-title m-0">
                        <h3 class="fw-bold m-0">Website Configuration</h3>
                    </div>
                    <!--end::Card title-->
                </div>

                <!--begin::Card body-->
                <div class="card-body border-top pt-8 pb-4">

                    <form id="form-website" enctype="multipart/form-data">

                        {{ Form::textInput('website_name',  @$website_config['website_name'] ?? env('APP_NAME'), ['formAlignment' => 'vertical']) }}

                        {{ Form::textareaInput('website_description',  @$website_config['website_description'] ?? env('APP_DESCRIPTION'), ['formAlignment' => 'vertical', 'elOptions' => ['rows' => 3]]) }}

                        {{ 
                            Form::fileInput('website_logo', @$website_config['website_logo'] ?? asset(config('theme.assets.back-office').'media/logos/default.svg'),[
                                'pluginOptions' => [
                                    'showUpload' => false,
                                    'showPreview' => true,
                                    'dropZoneEnabled' => false,
                                    'allowedFileExtensions' => ['png','jpg', 'jpeg'],
                                ],
                                'formAlignment' => 'vertical',
                                'labelText' => 'Logo'
                            ])
                        }}

                        {{ 
                            Form::fileInput('website_logo_dark', @$website_config['website_logo_dark'] ?? asset(config('theme.assets.back-office').'media/logos/default-dark.svg'),[
                                'pluginOptions' => [
                                    'showUpload' => false,
                                    'showPreview' => true,
                                    'dropZoneEnabled' => false,
                                    'allowedFileExtensions' => ['png','jpg', 'jpeg'],
                                ],
                                'formAlignment' => 'vertical',
                                'labelText' => 'Logo (Dark Theme Mode)',
                                'inputContainerClass' => 'dark-theme',
                                'divContainerClass' => 'dark-theme',
                            ])
                        }}

                        {{ 
                            Form::fileInput('website_favicon', @$website_config['website_favicon'] ?? asset(config('theme.assets.back-office').'media/logos/favicon.ico'), [
                                'pluginOptions' => [
                                    'showUpload' => false,
                                    'showPreview' => true,
                                    'dropZoneEnabled' => false,
                                    'allowedFileExtensions' => ['ico'],
                                ],
                                'formAlignment' => 'vertical',
                                'labelText' => 'Favicon'
                            ])
                        }}

                        <div class="form-group mb-5 1">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class=" col-form-label">
                                        <span class="fw-bold fs-6">Footer</span>
                                    </label>
                                </div>
                                <div class="col-md-12">
                                        
                                    <div id="website_footer" name="website_footer">
                                        {!! @$website_config['website_footer'] ?? '2024 © Absensi V 1.0' !!}
                                    </div>
                              
                                </div>
                            </div>
                        </div>

                    </form>

                </div>

                <div class="card-footer d-flex justify-content-end mt-15 py-6 px-9">
                    <button type="button" id="form-website-submit" class="btn btn-primary">
                        <span class="indicator-label">Save Changes</span>
                        <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>

            </div>

        </div>


    </div>


@endsection

@push('additional-js')

<script type="text/javascript">
    var param = null;

    var quill = new Quill('#website_footer', {
        modules: {
            toolbar: [
                [{ 'font': [] }],
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                //[{ 'align': [false, 'center', 'right'] }],
                [{ 'color': [] }, { 'background': [] }],
                ['link', 'image'],
                ['clean']
            ]
        },
        placeholder: 'Type your text here...',
        theme: 'snow' // or 'bubble'
    });

    $('#form-website-submit').click(function(){

        var form = $('#form-website')[0];
        var formData = new FormData(form);

        formData.append('website_footer', quill.root.innerHTML);

        $.ajax({
            type: "POST",
            url:  '{{ route('setting.website-config') }}',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function(){   
                submitProcess('form-website-submit');
            },
            success: function (result) {
                if(result.success == true){
                    swaling({'text': 'Success to save setting', 'icon': 'success'})
                }else{
                    swaling({'text': result.message, 'icon': 'error'})
                    displayErrorInput($('#form-website'), result.errors);
                }
            },
            error: function(xhr, textStatus, errorThrown){
                console.log(errorThrown);
                toasting()
            }
        }).always(function(){
            submitProcess('form-website-submit', 1);
        });

    })


</script>

@endpush

@push('additional-css')

<style type="text/css">
    
</style>

@endpush