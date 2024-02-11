@php
	$key   = 'absensi';
	$label = ucwords(str_replace('-', ' ', $key));
	$route = $key;
@endphp

@extends(config('theme.layouts.admin'),[
    'title' => "$label Form",
    'breadcrumb' => [
        [
            'label' => 'Dashboard',
            'url' => route('dashboard')
        ],
        [
            'label' => $label,
            'url' => route("$key.view")
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
	        	'url' => route("$key.view"),
	        ]
	    ])

	        <div class="col-xl-12">
			    {{ 
                    Form::select2Input('type', @$data->type, [
						"Masuk"  => "Masuk",
						"Pulang" => "Pulang",
                    ], [
                        'formAlignment' => 'vertical',
                        'pluginOptions' => ['allowClear' => false],
                        'required'      => 'required',
                    ]) 
                }}
            </div>

	        <div class="col-xl-12">
				<div id="map" style="width: 100%; height: 450px;"></div>
				<p class="no-browser-support" style="display: none;">
					Sorry, the Geolocation API isn't supported in Your browser.
				</p>

				<input type="text" id="inputLatitude" name="latitude" class="col-xl-6" style="display: none;">
				<input type="text" id="inputLongitude" name="longitude" class="col-xl-6" style="display: none;">
			</div>

	    @endcomponent
	@endcomponent

	@push('additional-js')
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
		     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
		     crossorigin=""/>
		  <!-- Make sure you put this AFTER Leaflet's CSS -->
		 <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
		     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
		     crossorigin=""></script>

		<script type="text/javascript">

			if (!navigator.geolocation) {
		  
			    $('.no-browser-support').css("display","block");
		  
		  	} else {
		        var coords = navigator.geolocation.getCurrentPosition(function(position){

			        var latitude = position.coords.latitude;
			        var longitude = position.coords.longitude;

			        $("#inputLatitude").val(latitude);
			        $("#inputLongitude").val(longitude);

					var map = L.map('map').setView([latitude, longitude], 19);

					L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
					    minZoom: 4,
					    maxZoom: 19,
					    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
					}).addTo(map);

					var greenIcon = new L.icon({
			                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
			                    // iconUrl: 'https://cdn-icons-png.flaticon.com/128/149/149059.png',
			                    iconSize: [25, 41],
			                    iconAnchor: [12, 41],
			                    popupAnchor: [1, -34],
			                });

					// var marker = L.marker([-6.2298556,106.81871]).addTo(map);
					// var markerLocation = location;
					// markerLocation = [-6.3053244,107.2014531];
			            L.marker([latitude, longitude],{icon: greenIcon}).addTo(map);

			           var popup = L.popup();
		        });
		    }


		    $('.btn-submit').click(function(){

		        var form = $('#form-general')[0];
		        var formData = new FormData(form);

		        $.ajax({
		            type: "POST",
		            url:  '{{ route("$route.save") }}',
		            data: formData,
		            cache: false,
		            contentType: false,
		            processData: false,
		            beforeSend: function(){   
		                submitProcess('form-general-submit');
		                submitProcess('form-general-submit-2');
		            },
		            success: function (result) {
		                if(result.success == true){
		                	@if(@$data)
		                    	swaling({'text': 'Success to save data', 'icon': 'success'})
		                	@else

		                		Swal.fire({
						            text: 'Success to save data!',
						            icon: 'success',
						            showCancelButton: false,
						            buttonsStyling: false,
						            confirmButtonText: "Ok, got it",
						            customClass: {
						                confirmButton: "btn btn-primary",
						            }
						        }).then(function() {
		                			$(location).attr('href', '{{ route("$route.view") }}');
						        })

		                	@endif
		                }else{
		                    swaling({'text': result.message, 'icon': 'error'})
		                    displayErrorInput($('#form-general'), result.errors);
		                }
		            },
		            error: function(xhr, textStatus, errorThrown){
		                console.log(errorThrown);
		                toasting()
		            }
		        }).always(function(){
		            submitProcess('form-general-submit', 1);
		            submitProcess('form-general-submit-2', 1);
		        });

		    })

		</script>

	@endpush
@endsection