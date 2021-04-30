@extends('layout.mainlayout_admin')
@section('content') 
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
                        <div class="row">
                            <div class="col-sm-7 col-auto">
                                <h3 class="page-title">التخصصات</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index">Dashboard</a></li>
                                    <li class="breadcrumb-item active">التخصصات</li>
                                </ul>
                            </div>
                            <div class="col-sm-5 col">
                                @can('اضافة صلاحية')
                                <a href="{{ route('roles.create') }}" class="btn btn-primary float-right mt-2">أضافة صلاحية</a>
                                @endcan
                            </div>
                            @if (session()->has('Add'))
                                <script>
                                    window.onload = function() {
                                        notif({
                                            msg: " تم اضافة الصلاحية بنجاح",
                                            type: "success"
                                        });
                                    }

                                </script>
                            @endif

                            @if (session()->has('edit'))
                                <script>
                                    window.onload = function() {
                                        notif({
                                            msg: " تم تحديث بيانات الصلاحية بنجاح",
                                            type: "success"
                                        });
                                    }

                                </script>
                            @endif

                            @if (session()->has('delete'))
                                <script>
                                    window.onload = function() {
                                        notif({
                                            msg: " تم حذف الصلاحية بنجاح",
                                            type: "error"
                                        });
                                    }

                                </script>
                            @endif

                        </div>
        </div>
                    <!-- /Page Header -->
        <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table
                                            class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>الاسم</th>
                                                    <th>العمليات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($roles as $key => $role)
                                                    <tr>
                                                        <td>{{ ++$i }}</td>
                                                        <td>{{ $role->name }}</td>
                                                        <td>
                                                            @can('عرض صلاحية')
                                                                <a class="btn btn-success btn-sm"
                                                                    href="{{ route('roles.show', $role->id) }}">عرض</a>
                                                            @endcan
                                                            
                                                            @can('تعديل صلاحية')
                                                                <a class="btn btn-primary btn-sm"
                                                                    href="{{ route('roles.edit', $role->id) }}">تعديل</a>
                                                            @endcan

                                                            @if ($role->name !== 'owner')
                                                                @can('حذف صلاحية')
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy',
                                                                    $role->id], 'style' => 'display:inline']) !!}
                                                                    {!! Form::submit('حذف', ['class' => 'btn btn-danger btn-sm']) !!}
                                                                    {!! Form::close() !!}
                                                                @endcan
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>          
        </div>
    </div>  
<!-- row closed -->
</div>
<!-- Container closed -->

<!--Internal  Notify js -->
<script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>

      

@endsection
