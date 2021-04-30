@extends('layout.mainlayout_admin')
@section('content') 
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
                        <div class="row">
                            <div class="col-sm-7 col-auto">
                                <h3 class="page-title">المستخدمين</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index">Dashboard</a></li>
                                    <li class="breadcrumb-item active">التخصصات</li>
                                </ul>
                            </div>
                            <div class="col-sm-5 col">
                                 @can('اضافة مستخدم')
                                <a  class="btn btn-primary float-right mt-2" href="{{ route('users.create') }}">اضافة مستخدم</a></a>
                                @endcan
                            </div>

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
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
                                                    <th class="wd-10p border-bottom-0">#</th>
                                                    <th class="wd-15p border-bottom-0">اسم المستخدم</th>
                                                    <th class="wd-20p border-bottom-0">البريد الالكتروني</th>
                                                    <th class="wd-15p border-bottom-0">حالة المستخدم</th>
                                                    <th class="wd-15p border-bottom-0">نوع المستخدم</th>
                                                    <th class="wd-10p border-bottom-0">العمليات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data as $key => $user)
                                                    <tr>
                                                        <td>{{ ++$i }}</td>
                                                        <td>{{ $user->name }}</td>
                                                        <td>{{ $user->email }}</td>
                                                        <td>
                                                            @if ($user->Status == 'مفعل')
                                                                <span class="label text-success d-flex">
                                                                    <div class="dot-label bg-success ml-1"></div>{{ $user->Status }}
                                                                </span>
                                                            @else
                                                                <span class="label text-danger d-flex">
                                                                    <div class="dot-label bg-danger ml-1"></div>{{ $user->Status }}
                                                                </span>
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @if (!empty($user->getRoleNames()))
                                                                @foreach ($user->getRoleNames() as $v)
                                                                    <label class="badge badge-success">{{ $v }}</label>
                                                                @endforeach
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @can('تعديل مستخدم')
                                                                
                                                                <a href="{{ route('users.edit', $user->id) }}"  
                                                                    class="btn btn-sm bg-success-light"  >
                                                                <i class="fe fe-pencil"></i> تعديل
                                                            </a>
                                                            @endcan

                                                            @can('حذف مستخدم')
                                                                <a class="btn btn-sm bg-danger-light" data-effect="effect-scale"
                                                                    data-user_id="{{ $user->id }}" data-username="{{ $user->name }}"
                                                                    data-toggle="modal" href="#delete" title="حذف">
                                                                    <i class="fe fe-trash"></i> حذف</a>
                                                                   
                                                            @endcan
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

    <!--    <div class="modal" id="modaldemo8">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content modal-content-demo">
                        <div class="modal-header">
                            <h6 class="modal-title">حذف المستخدم</h6><button aria-label="Close" class="close"
                                data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <form action="{{ route('users.destroy', 'test') }}" method="post">
                            {{ method_field('delete') }}
                            {{ csrf_field() }}
                            <div class="modal-body">
                                <p>هل انت متاكد من عملية الحذف ؟</p><br>
                                <input type="hidden" name="user_id" id="user_id" value="">
                                <input class="form-control" name="username" id="username" type="text" readonly>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                                <button type="submit" class="btn btn-danger">تاكيد</button>
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div> -->

        <div class="modal fade" id="delete" aria-hidden="true" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document" >
                    <div class="modal-content">
                    <!--    <div class="modal-header">
                            <h5 class="modal-title">Delete</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>-->
                        <div class="modal-body">
                            <div class="form-content p-2">
                                <h4 class="modal-title">حذف</h4>
                                <p class="mb-4">هل متأكد من الحذف ؟</p>
                                <div class="row text-center">
                                    <div class="col-sm-3">
                                    </div>
                                    <div class="col-sm-2">
                                        <form method="post" action="{{route('users.destroy','test')}}">
                                             @csrf
                                             @method('delete')
                                             <input type="hidden" name="user_id" id="user_id" >
                                            <button type="submit" class="btn btn-primary">حذف </button>
                                        </form>
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            
</div>

  <script src="{{asset('js/app.js')}}"></script>

<script>

     $('#delete').on('show.bs.modal', function (event) {

      var button = $(event.relatedTarget) 

      var user_id = button.data('user_id') 
      var modal = $(this)

      modal.find('.modal-body #user_id').val(user_id);
})


</script>

       




        <!-- /Main Wrapper -->
@endsection