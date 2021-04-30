@extends('layout.mainlayout_admin')
@section('content')	
<!-- Main Wrapper -->
<div class="main-wrapper login-body">
            <div class="login-wrapper">
            	<div class="container">
                	<div class="loginbox">
                    	<div class="login-left">
							<img class="img-fluid" src="{{asset('assets_admin/img/settings/'.$contact->logo) }}" alt="Logo">

                        </div>
                        <div class="login-right">
							<div class="login-right-wrap">
								<h1>تسجيل الدخول</h1>
								<p class="account-subtitle">الوصول إلى لوحة التحكم الخاصة بنا</p>
								
								<!-- Form -->
								<form method="POST" action="{{route('login')}}">
                       				 @csrf
									<div class="form-group">
										
										<input id="email" type="email" placeholder="البريد الإلكتروني" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

		                                @error('email')
		                                    <span class="invalid-feedback" role="alert">
		                                        <strong>{{ $message }}</strong>
		                                    </span>
		                                @enderror
									</div>
									<div class="form-group">
										
										<input id="password" type="password" placeholder="كلمة المرور" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
										@error('password')
		                                    <span class="invalid-feedback" role="alert">
		                                        <strong>{{ $message }}</strong>
		                                    </span>
		                                @enderror
									</div>
									<div class="form-group">
										<button class="btn btn-primary btn-block" type="submit">دخول</button>
									</div>
								</form>
								<!-- /Form -->								
								<!-- <div class="text-center forgotpass"><a href="forgot-password">Forgot Password?</a></div> -->
								<div class="login-or">
									<!-- <span class="or-line"></span> -->
									<!-- <span class="span-or">or</span> -->
								</div>
								  
								<!-- Social Login -->
								<!-- <div class="social-login">
									<span>Login with</span>
									<a href="#" class="facebook"><i class="fa fa-facebook"></i></a><a href="#" class="google"><i class="fa fa-google"></i></a>
								</div> -->
								<!-- /Social Login -->
								
								<!-- <div class="text-center dont-have">Don’t have an account? <a href="register">Register</a></div> -->
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<!-- /Main Wrapper -->
@endsection