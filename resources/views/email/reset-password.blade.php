<!DOCTYPE html>
<head>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
</head>

<style>
	html,body { padding:0; margin:0; font-family: Inter, Helvetica, "sans-serif"; } 
	a:hover { color: #009ef7; }
	.text-center { text-align: center; }
	.d-flex { display: flex; }
	.flex-column { flex-direction: column !important; }
	.flex-column-fluid { flex: 1 0 auto; }
	.flex-root { flex: 1; }
	.justify-content-center { justify-content: center; }
	.px-10 { padding-right: 2.5rem !important; padding-left: 2.5rem !important; }
	.py-10 { padding-top: 2.5rem !important; padding-bottom: 2.5rem !important; }
	.text-gray-600 { color: #7E8299 !important; }
	.fw-semibold { font-weight: 500 !important; }
	.fs-4 { font-size: 1.25rem !important; }
	.btn.btn-primary {
	    color: #ffffff;
	    background-color: #7239ea;
	    border: 0;
    	padding: calc(0.775rem + 1px) calc(1.5rem + 1px);
    	box-shadow: none;
    	outline: none !important;
    	text-decoration: none;
    	border-radius: 0.475rem;
	}
	.btn.btn-primary:hover{
		background-color: #5014d0;
	}
</style>

<body style="background-color:#D5D9E2;">
	
	<div class="d-flex flex-column flex-root">
		<div class="d-flex flex-column flex-column-fluid">

			<div class="flex-column-fluid px-10 py-10">

				<div class="text-center">
					<a href="{{ @$website_url }}" target="_blank">
						<img src="{{asset(config('theme.assets.back-office').'media/email/logo-2.svg')}}" style="height: 40px" />
					</a>
				</div>

				<div style="line-height: 1.5; min-height: 100%; margin:0; padding:0; width:100%;">

					<div style="
						background-color:#ffffff; 
						padding: 45px 0 34px 0; 
						border-radius: 24px; 
						margin:30px auto; 
						max-width: 600px;
						font-size: 0.9rem;
						color: #7E8299;
					">

						<div style="text-align:center; margin:10px 60px 10px 60px">

							<div style="text-align: left;">
								<div style="font-size: 1.2rem; color:#181C32; font-weight: 600; margin-bottom: 15px;">Hello!</div>
								<div>You are receiving this email because we received a password reset request for your account.</div>
							</div>

							<div style="margin-top: 50px; margin-bottom: 50px;">
								<a href="{{ @$url }}" target="_blank" class="btn btn-primary" >Reset Password</a>
							</div>

							<div style="text-align: left;">
								<div style="margin-bottom: 15px;">This password reset link will expire in 60 minutes.</div>
								<div style="margin-bottom: 15px;">If you did not request a password reset, no further action is required.</div>
								<div>Regards,</div>
								<div style="color: #181C32; font-weight: 600">{{ @$regards ?? 'Team' }}</div>
							</div>

							<hr style="opacity: 0.25; margin-bottom: 25px; margin-top: 25px;">

							<div style="text-align: left; overflow-wrap: break-word; font-size: 0.8rem;">
								<div>If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser: <a style="color: #7239ea;" target="_blank" href="{{ @$url }}">{{ @$url }}</a></div>
							</div>

						</div>

					</div>

				</div>

				<div class="text-center" style="font-size: 0.9rem; color: #7E8299;">
					@if($footer = setting('website_footer'))
						{!! $footer !!}
					@else
						<div class="text-dark order-2 order-md-1">
							<span class="text-muted fw-semibold me-1">2023&copy;</span>
							<a href="https://keenthemes.com" target="_blank" class="text-gray-800 text-hover-primary">Absensi V 1.0</a>
						</div>
					@endif
				</div>

			</div>

		</div>
	</div>

</body>