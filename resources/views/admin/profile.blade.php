@extends('layout.mainlayout_admin')
@section('content')	
<!-- Page Wrapper -->
<div class="page-wrapper">
                <div class="content container-fluid">
					
					<!-- Page Header -->
					<div class="page-header">
						<div class="row">
							<div class="col">
								<h3 class="page-title">المعلومات الشخصية</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item"><a href="index">Dashboard</a></li>
									<li class="breadcrumb-item active">البروفايل</li>
								</ul>
							</div>
						</div>
						@if(session()->has('message'))
                            @include('admin.includes.alerts.success')
                        @endif
                        @if ($errors->any())
						    <div class="alert alert-warning">
						        <ul>
						        	@foreach ($errors->all() as $error)
						            	<li>{{ $error }}</li>
						        	@endforeach
						        </ul>
						     </div>
						@endif

						@if(Session::has('error'))
                               <div class="row mr-2 ml-2" >
                                    <button type="text" class="btn btn-lg btn-block btn-outline-danger mb-2" id="type-error">
                                       {{Session::get('error')}}
                                    </button>
                                </div>
                            @endif 
					</div>
					<!-- /Page Header -->
					
					<div class="row">
						<div class="col-md-12">
							<div class="profile-header">
								<div class="row align-items-center">
									<div class="col-auto profile-image">
										<a href="#">
											<img class="rounded-circle" alt="User Image" src="{{asset('assets_admin/img/users/'.$users->photo) }}">
										</a>
									</div>
									<div class="col ml-md-n2 profile-user-info">
										<h4 class="user-name mb-0">{{$users->name}}</h4>
										<h6 class="text-muted">{{$users->email}}</h6>
										<div class="user-Location"><i class="fa fa-map-marker"></i> {{$users->address}}</div>
										<!-- <div class="about-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</div> -->
									</div>
									<!-- <div class="col-auto profile-btn">
										
										<a href="" class="btn btn-primary">
											Edit
										</a>
									</div> -->
								</div>
							</div>
							<div class="profile-menu">
								<ul class="nav nav-tabs nav-tabs-solid">
									<li class="nav-item">
										<a class="nav-link active" data-toggle="tab" href="#per_details_tab">من أنا</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#password_tab">كلمة المرور</a>
									</li>
								</ul>
							</div>	
							<div class="tab-content profile-tab-cont">
								
								<!-- Personal Details Tab -->
								<div class="tab-pane fade show active" id="per_details_tab">
								
									<!-- Personal Details -->
									<div class="row">
										<div class="col-lg-12">
											<div class="card">
												<div class="card-body">
													<h5 class="card-title d-flex justify-content-between">
														<span>البيانات الشخصية	</span> 
														<a class="edit-link" data-toggle="modal" href="#edit_personal_details"><i class="fa fa-edit mr-1"></i>تعديل</a>
													</h5>
													<div class="row">
														<p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">الاسم</p>
														<p class="col-sm-10">{{$users->name}}</p>
													</div>
													<div class="row">
														<p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">تاريخ الميلاد</p>
														<p class="col-sm-10">{{$users->dateOfBirth}}</p>
													</div>
													<div class="row">
														<p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">البريد الالكتروني</p>
														<p class="col-sm-10">{{$users->email}}</p>
													</div>
													<div class="row">
														<p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">الهاتف</p>
														<p class="col-sm-10">{{$users->mobile}}</p>
													</div>
													<div class="row">
														<p class="col-sm-2 text-muted text-sm-right mb-0">العنوان</p>
														<p class="col-sm-10">{{$users->address}}</p>
																
													</div>
												</div>
											</div>
											
											<!-- Edit Details Modal -->
											<div class="modal fade" id="edit_personal_details" aria-hidden="true" role="dialog">
												<div class="modal-dialog modal-dialog-centered" role="document" >
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title">البيانات الشخصية</h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<div class="modal-body">
															<form action="{{url('profile/update')}}" method="POST" 
								                                name="le_form"  enctype="multipart/form-data">
								                                @csrf
																<div class="row form-row">           
																 
																	<input type="hidden" name="id" value="{{Auth::user()->id}}">
																	<div class="col-12 col-sm-12">
																		<div class="form-group">
																			<label> الاسم</label>
																			<input type="text" name="name" class="form-control" value="John">
																		</div>
																	</div>
																	
																	<div class="col-12">
																		<div class="form-group">
																			<label>تاريخ الميلاد</label>
																			<div class="cal-icon">
																				<input type="date" name="dateOfBirth" class="form-control" value="{{Auth::user()->dateOfBirth}}">
																			</div>
																		</div>
																	</div>
																	<!-- <div class="col-12 col-sm-6">
																		<div class="form-group">
																			<label>Email ID</label>
																			<input type="email" class="form-control" value="johndoe@example.com">
																		</div>
																	</div> -->
																	<div class="col-12 col-sm-12">
																		<div class="form-group">
																			<label>الهاتف</label>
																			<input type="text" name="mobile" value="{{Auth::user()->mobile}}" class="form-control">
																		</div>
																	</div>
																	<!-- <div class="col-12">
																		<h5 class="form-title"><span>Address</span></h5>
																	</div> -->
																	<div class="col-12">
																		<div class="form-group">
																		<label>العنوان</label>
																			<input type="text" name="address" class="form-control"
																			 value="{{Auth::user()->address}}">
																		</div>
																	</div>
																	<div class="col-12 col-sm-12">
																		<div class="form-group">
																			<label>الصورهة الشخصية</label>
																			<input type="hidden" name="url" value="{{Auth::user()->photo}}" >
																			<input type="file" name="photo" class="form-control" value="Miami">
																		</div>
																	</div>
																	
																</div>
																<button type="submit" class="btn btn-primary btn-block">حفظ التغيير </button>
															</form>
														</div>
													</div>
												</div>
											</div>
											<!-- /Edit Details Modal -->
											
										</div>

									
									</div>
									<!-- /Personal Details -->

								</div>
								<!-- /Personal Details Tab -->
								
								<!-- Change Password Tab -->
								<div id="password_tab" class="tab-pane fade">
								
									<div class="card">
										<div class="card-body">
											<h5 class="card-title">تغيير كلمة المرور</h5>
											<div class="row">
												<div class="col-md-10 col-lg-6">
															<form action="{{route('user.changepassword')}}" method="POST" 
	                                name="le_form"  enctype="multipart/form-data">
	                                @csrf
	                                					<input type="hidden" name="id" value="{{Auth::user()->id}}">
														<div class="form-group">
															<label>كلمة المرور الحالية</label>
															<input type="password" name="current-password" class="form-control">
														</div>
														<div class="form-group">
															<label>كلمة المرور الجديدة</label>
															<input type="password" name="new-password" class="form-control">
														</div>
														<!-- <div class="form-group">
															<label>Confirm Password</label>
															<input type="password" class="form-control">
														</div> -->
														<button class="btn btn-primary" type="submit">حفظ التغيير</button>
													</form>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- /Change Password Tab -->
								
							</div>
						</div>
					</div>
				
				</div>			
			</div>
			<!-- /Page Wrapper -->
		
        </div>
		<!-- /Main Wrapper -->
@endsection