
@extends('layout.mainlayout_admin')
@section('content')		
			<!-- Page Wrapper -->
            <div class="page-wrapper">
			
                <div class="content container-fluid">
					
					<!-- Page Header -->
					<div class="page-header">
						<div class="row">
							<div class="col-sm-12">
								<h3 class="page-title">مرحبا {{Auth::user()->name}}!</h3>
								<ul class="breadcrumb">
									<!-- <li class="breadcrumb-item active">Dashboard</li> -->
								</ul>
							</div>
						</div>
					</div>
					<!-- /Page Header -->

					<div class="row">
						<div class="col-xl-4 col-sm-6 col-12">
							<div class="card">
								<a href="{{url('doctors-appointments')}}">
								<div class="card-body">
									<div class="dash-widget-header">
										<span class="dash-widget-icon text-primary border-primary">
											<i class="fe fe-users"></i>
										</span>
										<div class="dash-count">
											<h3>{{$doctorsCount}}</h3>
										</div>
									</div>
									<div class="dash-widget-info">
										<h6 class="text-muted">الدكاتر تقارير</h6>
										<div class="progress progress-sm">
											<div class="progress-bar bg-primary w-50"></div>
										</div>
									</div>
								</div>
								</a>
							</div>
						</div>
						<div class="col-xl-4 col-sm-6 col-12">
							<div class="card">
								<a href="{{url('patients-appointments')}}">
								<div class="card-body">
									<div class="dash-widget-header">
										<span class="dash-widget-icon text-success">
											<i class="fe fe-credit-card"></i>
										</span>
										<div class="dash-count">
											<h3>{{$patientsCount}}</h3>
										</div>
									</div>
									<div class="dash-widget-info">
										
										<h6 class="text-muted">المرضى</h6>
										<div class="progress progress-sm">
											<div class="progress-bar bg-success w-50"></div>
										</div>
									</div>
								</div>
								</a>
							</div>
						</div>
						<div class="col-xl-4 col-sm-6 col-12">
							<div class="card">
								<div class="card-body">
									<div class="dash-widget-header">
										<span class="dash-widget-icon text-danger border-danger">
											<i class="fe fe-money"></i>
										</span>
										<div class="dash-count">
											<h3>{{$appointmentsCount}}</h3>
										</div>
									</div>
									<div class="dash-widget-info">
										
										<h6 class="text-muted">المواعيد</h6>
										<div class="progress progress-sm">
											<div class="progress-bar bg-danger w-50"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- <div class="col-xl-3 col-sm-6 col-12">
							<div class="card">
								<div class="card-body">
									<div class="dash-widget-header">
										<span class="dash-widget-icon text-warning border-warning">
											<i class="fe fe-folder"></i>
										</span>
										<div class="dash-count">
											<h3>$62523</h3>
										</div>
									</div>
									<div class="dash-widget-info">
										
										<h6 class="text-muted">Revenue</h6>
										<div class="progress progress-sm">
											<div class="progress-bar bg-warning w-50"></div>
										</div>
									</div>
								</div>
							</div>
						</div> -->
					</div>
					<div class="row">
						<div class="col-md-12 d-flex">
							<!-- Recent Orders -->
							<div class="card card-table flex-fill">
								<div class="card-header">
									<h4 class="card-title">قائمة الدكاتره</h4>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table
		                                    class="table table-bordered table-striped table-hover js-basic-example dataTable">
		                                    <thead>
												<tr>
												    <th>الصوره</th>
													<th>الاسم عربي</th>
													<th>الاسم انجليزي</th>
													<th>التخصص </th>
													<!-- <th>Speciality En</th> -->
													<!--<th>عضو منذ </th>-->
													<!-- <th>Earned</th> -->
													<!-- <th>Account Status</th> -->
												</tr>
											</thead>
											<tbody>

												@foreach ($doctors as $_item)
												<tr>
													<td class="text-center">
														<h2 class="table-avatar">
															@if($_item->photo !=null)
        												        <a href="{{url('doctor-profile/'.$_item->id) }}">  
        											              <img class="avatar-img rounded-circle" src="{{asset('assets_admin/img/doctors/photo/'.$_item->photo) }}" alt="User Image" width="60px" height="60px">
        												        </a>
        												    @else
        									    		        <a href="{{url('patient-profile/'.$_item->id) }}">
        												             <img class="avatar-img rounded-circle" src="{{asset('assets_admin/img/profile_image.png') }}" alt="User Image" width="60px" height="60px">
        												        </a>
        												    @endif	
        												    

														</h2>
													</td>
													<td class="text-center">
														<h2 class="table-avatar">
															<a href="profile">{{$_item->first_name_ar}} {{$_item->last_name_ar}}</a>
														</h2>
													</td>
													<td class="text-center">
														<h2 class="table-avatar">
															<a href="profile">{{$_item->first_name_en}} {{$_item->last_name_en}}</a>
														</h2>
													</td>
													
													<td class="text-center">
														<!--{{$_item->categorynamear}}-->
														{{$_item->categorynamear->name_ar}} 
													</td class="text-center">
													<!-- <td>{{$_item->categorynamear}}</td> -->
													
													<!--<td class="text-center">{{ mb_strimwidth($_item->created_at,0,10) }} <br>-->
													<!--	<small>{{ substr($_item->created_at,10,11) }}</small></td>-->
													
													
													<!-- <td>${{ $_item->earned }}</td> -->
													
													
												</tr>
												@endforeach
											</tbody>
		                                    
		                                </table>
									</div>
								</div>
							</div>
							<!-- /Recent Orders -->
							
						</div>
						
					</div>


					<div class="row">
						
						<div class="col-md-12 d-flex">
							
								<!-- Feed Activity -->
								<div class="card  card-table flex-fill">
									<div class="card-header">
										<h4 class="card-title">قائمة المرضى</h4>
									</div>
									<div class="card-body">
										<div class="table-responsive">
											<table
			                                    class="table table-bordered table-striped table-hover js-basic-example dataTable">
			                                    <thead>
														<tr>
															
															<th>الصوره</th>
															<th>الاسم عربي</th>
															<th>الاسم انجليزي</th>
															<th> الهاتف <h>
															
															<!-- <th class="text-right">Paid</th> -->
															<!-- <th>Actions</th> -->
														</tr>
													</thead>
													<tbody>
														@foreach ($patients as $_item)
														<tr>
															<td class="text-center">
																<h2 class="table-avatar">
																	@if($_item->photo !=null)
        														        <a href="{{url('patient-profile/'.$_item->id) }}"> 
        														             <img class="avatar-img rounded-circle" src="{{asset('assets_admin/img/patients/'.$_item->photo) }}" alt="User Image" width="60px" height="60px">
        														        </a>
        														    @else
        														        <a href="{{url('patient-profile/'.$_item->id) }}">
        														             <img class="avatar-img rounded-circle" src="{{asset('assets_admin/img/profile_image.png') }}" alt="User Image" width="60px" height="60px">
        														        </a>
        														    @endif
																</h2> 
																
															</td>
															<td class="text-center">
        														<h2 class="table-avatar">
        															<a href="profile">{{$_item->first_name_ar}} {{$_item->last_name_ar}}</a>
        														</h2>
        													</td>
        													<td class="text-center">
        														<h2 class="table-avatar">
        															<a href="profile">{{$_item->first_name_en}} {{$_item->last_name_en}}</a>
        														</h2>
        													</td>
															<td class="text-center">{{ $_item->mobile }}</td>
															<!--<td class="text-center">{{ $_item->email }}</td>-->
															<!-- <td>${{ $_item->paid }}</td> -->
															
															
															
														</td>
															
														</tr>
														@endforeach
													</tbody>
			                                    
			                                </table>
										</div>
									</div>
								</div>
								<!-- /Feed Activity -->
								
						</div>
					</div>


					<div class="row">
						<div class="col-md-12">
						
							<!-- Recent Orders -->
							<div class="card card-table">
								<div class="card-header">
									<h4 class="card-title">قائمة المواعيد</h4>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table
		                                    class="table table-bordered table-striped table-hover js-basic-example dataTable">
		                                    <thead>
												<tr>
													<th>اسم الدكتور</th>
													<th>التخصص</th>
													<th>اسم المريض</th>
													<th>الوقت</th>
													<th>حالة الموعد</th>
												</tr>
											</thead>
											<tbody>
											@foreach ($appointments as $_item)
												<tr>
													<td class="text-center">
														<h2 class="table-avatar">
																
        													@if($_item->doctor['photo']!=null)
														        <a href="{{url('doctor-profile/'.$_item->doctor['id']) }}"> 
														             <img class="avatar-img rounded-circle" src="{{asset('assets_admin/img/doctors/photo/'.$_item->doctor['photo']) }}" alt="User Image" width="60px" height="60px">
														        </a>
														    @else
														       <a href="{{url('doctor-profile/'.$_item->doctor['id']) }}"> 
														             <img class="avatar-img rounded-circle" src="{{asset('assets_admin/img/profile_image.png') }}" alt="User Image" width="60px" height="60px">
														        </a>
														    @endif
															<a href="{{url('doctor-profile/photo/'.$_item->doctor['id']) }}">&nbsp;
															     {{$_item->doctor['first_name_ar']}}  {{$_item->doctor['last_name_ar']}}
															</a>
														</h2>
													</td>
													<td class="text-center">
													   
														{{$_item->speciality['name_ar']}} 
													</td>
													<td class="text-center">
														<h2 class="table-avatar">
															
														    @if($_item->patient['photo']!=null)
														        <a href="{{url('patient-profile/'.$_item->patient['id']) }}"> 
														             <img class="avatar-img rounded-circle" src="{{asset('assets_admin/img/patients/'.$_item->patient['photo']) }}" alt="User Image" width="60px" height="60px">
														        </a>
														    @else
														        <a href="{{url('patient-profile/'.$_item->patient['id']) }}">
														             <img class="avatar-img rounded-circle" src="{{asset('assets_admin/img/profile_image.png') }}" alt="User Image" width="60px" height="60px">
														        </a>
														    @endif
															<a href="{{url('patient-profile/'.$_item->patient['id']) }}"> &nbsp;
														    {{$_item->patient['first_name_ar']}} {{$_item->patient['last_name_ar']}}
															</a>
														</h2>
													</td>
													<td> {{$_item->date}}<span class="text-primary d-block">{{$_item->time}}</span></td>
													
													<td class="text-center">
														<!-- <span class="badge badge-pill bg-danger-light">Cancelled</span> -->
														<span class="badge badge-pill bg-warning-light">Pending</span>
														<!-- <span class="badge badge-pill bg-success-light">Confirm</span> -->
													</td>
													
													
												</tr>
											@endforeach
												
												
									
											</tbody>
		                                    
		                                </table>
									</div>
								</div>
							</div>
							<!-- /Recent Orders -->
							
						</div>
					</div>	
				</div>

					

					
					
				</div>			
			</div>
			
		
        </div>
		<!-- /Main Wrapper -->
	   @endsection
	  