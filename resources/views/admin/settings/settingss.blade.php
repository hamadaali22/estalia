@extends('layout.mainlayout_admin')
@section('content')	
<!-- Page Wrapper -->
<div class="page-wrapper">
                <div class="content container-fluid">
				
					<!-- Page Header -->
					<div class="page-header">
						<div class="row">
							<div class="col-sm-12">
								<h3 class="page-title">اعدادات عامة</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item"><a href="index">Dashboard</a></li>
									<!-- <li class="breadcrumb-item"><a href="javascript:(0)">Settings</a></li> -->
									<li class="breadcrumb-item active">الاعدادات</li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /Page Header -->
					
					<div class="row">
						
						<div class="col-12">
							
							<!-- General -->
							
								<div class="card">
									<!-- <div class="card-header">
										<h4 class="card-title">General</h4>
									</div> -->
									<div class="card-body">
										<form action="{{url('settings/update')}}" method="POST" 
								                name="le_form"  enctype="multipart/form-data">
								                                @csrf
											<input type="hidden" name="id" value="{{Auth::user()->id}}">
											<div class="form-group">
												<label> العنوان عربي </label>
												<input type="text" name="title_ar" class="form-control" value="{{$contactInfo->title_ar}}">
											</div>
											<div class="form-group">
												<label>العنوان انجليزي</label>
												<input type="text" name="title_en" class="form-control" value="{{$contactInfo->title_en}}">
											</div>
											<div class="form-group">
												<label>رقم التواصل</label>
												<input type="text" name="phone" class="form-control" value="{{$contactInfo->phone}}">
											</div>
											<div class="form-group">
												<label>البري الإلكتروني</label>
												<input type="text" name="email" class="form-control" value="{{$contactInfo->email}}">
											</div>
											<div class="form-group">
												<label>العنوان بالعربي</label>
												<input type="text" name="address_ar" class="form-control" value="{{$contactInfo->address_ar}}">
											</div>
											<div class="form-group">
												<label>العنوان بالانجليزي</label>
												<input type="text" name="address_en" class="form-control" value="{{$contactInfo->address_en}}">
											</div>
											<div class="form-group">
												<label>الموقع</label>
												<input type="text" name="location" class="form-control" value="{{$contactInfo->location}}">
											</div>
											<!-- <div class="form-group">
												<label>mesion Image</label>
												<input type="file" class="form-control">
												<small class="text-secondary">Recommended image size is <b>150px x 150px</b></small>
											</div> -->
											<div class="form-group ">
												<label>Mesion Ar</label>
												<input type="text" name="mesion_ar" class="form-control" value="{{$contactInfo->mesion_ar}}">
											</div>
											<div class="form-group">
												<label>Vesion en</label>
												<input type="text" name="mesion_en" class="form-control" value="{{$contactInfo->mesion_en}}">
											</div>
											
											<div class="form-group">
												<label>Vesion Ar</label>
												<input type="text" name="vesion_ar" class="form-control" value="{{$contactInfo->vesion_ar}}">
											</div>
											<div class="form-group">
												<label>Vesion En</label>
												<input type="text" name="vesion_en" class="form-control" value="{{$contactInfo->vesion_en}}">
											</div>
											
											<div class="form-group">
												<label>الوصف عربي</label>
												<input type="text" name="description_ar" class="form-control" value="{{$contactInfo->description_ar}}">
											</div>
											<div class="form-group">
												<label>الوصف انجليزي</label>
												<input type="text" name="description_en" class="form-control" value="{{$contactInfo->description_en}}">
											</div>
											<div class="form-group">
												<label>الاصدار</label>
												<input type="text" name="version" class="form-control" value="{{$contactInfo->version}}">
											</div>
											<div class="form-group row">
												
												<div class="col-md-2">
													
													<img class="avatar-img" src="{{asset('assets_admin/img/settings/'.$contactInfo->mesion_image) }}" alt="Speciality" width="120" height="100">
												</div>	
												<div class="col-md-10">
													<label>Mesion Image</label>
													<input type="file" name="mesion_image" class="form-control">
													<input type="hidden" name="url4" value="{{$contactInfo->mesion_image}}">
												</div>	
												
											</div>

											<div class="form-group row">
												<div class="col-md-2">
													<img class="avatar-img" src="{{asset('assets_admin/img/settings/'.$contactInfo->vesion_image) }}" alt="Speciality" width="120" height="100">
												</div>	
												<div class="col-md-10">
													<label> vesion image</label>
													<input type="file" name="vesion_image" class="form-control">
													<input type="hidden" name="url3" value="{{$contactInfo->vesion_image}}">			
												</div>	

											</div>
											
											<div class="form-group row">												
												<div class="col-md-2">
													<img class="avatar-img" src="{{asset('assets_admin/img/settings/'.$contactInfo->logo) }}" alt="Speciality" width="120" height="100">
												</div>
												<div class="col-md-10">
													<label>الشعار</label>
													<input type="file" name="logo" class="form-control">
													<!-- <small class="text-secondary">Recommended image size is <b>150px x 150px</b></small> -->
													<input type="hidden" name="url" value="{{$contactInfo->logo}}">	
												</div>												
											</div>
											<div class="form-group row">
												<div class="col-md-2">
													<img class="avatar-img" src="{{asset('assets_admin/img/settings/'.$contactInfo->favicon) }}" alt="Speciality" width="120" height="100">

												</div>	
												<div class="col-md-10">
													<label>Favicon</label>
													<input type="file" name="favicon" class="form-control">	
													<input type="hidden" name="url2" value="{{$contactInfo->favicon}}">	
												</div>	
												
											</div>
											<button type="submit" class="btn btn-primary btn-block">حفظ التغيير </button>
											
										</form>
									</div>
								</div>
							
							<!-- /General -->
								
						</div>
					</div>
					
				</div>			
			</div>
			<!-- /Page Wrapper -->
		
        </div>
		<!-- /Main Wrapper -->
@endsection