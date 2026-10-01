@extends('layouts.app')
@section('title', 'List Subjects Dashboard')
@section('css')
@endsection
@section('content')
<div class="page-content">
    <!--start breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Subjects</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0 align-items-center">
                    <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">List Subjects</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="d-lg-flex align-items-center mb-4 gap-3">
                        <div class="position-relative">
                        </div>
                        <div class="ms-auto"><a href="{{ route('add.subject') }}"
                                class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i>Add New
                                Subjects</a></div>
                    </div>
                    <form action="{{route('list.subject')}}" method="get">
                    <div class="d-lg-flex align-items-center mb-4 gap-2">
                            <label for="customDropdown">Course:</label>
                            @php $course_id = request()->get('course_id'); @endphp
                            <select type="search" id="course_id" name="course_id" class="form-control" style="width: 300px">
                                <option value="">Select Course</option>
                                @foreach($course as $key => $val)
                                <option value="{{$val->id}}" {{ ( $course_id == $val->id) ? 'selected' : '' }}>{{$val->course_name}}</option>
                                @endforeach
                            </select>
                            <label for="customDropdown">Groups:</label>
                            @php $group_id = request()->get('group_id'); @endphp
                            <select type="search" id="group_id" name="group_id" class="form-control" style="width: 300px">
                                <option value="">Select Groups</option>
                                 @foreach($group as $key => $val)
                                <option value="{{$val->id}}" {{ ( $group_id == $val->id) ? 'selected' : '' }}>{{$val->group_name}}</option>
                                @endforeach
                            </select>
                             <label for="customDropdown">Paper:</label>
                            @php $paper_id = request()->get('paper_id'); @endphp
                            <select type="search" id="paper_id" name="paper_id" class="form-control" style="width: 300px">
                                <option value="">Select Paper</option>
                                @foreach($paper as $key => $val)
                                <option value="{{$val->id}}" {{ ( $paper_id == $val->id) ? 'selected' : '' }}>{{$val->paper_name}}</option>
                                @endforeach
                            </select>
                            <!--<label for="customDropdown">Subject Name:</label>-->
                            <!--<input type="text" id="q" name="q" value="{{!empty(request()->get('q')) ? request()->get('q') :''}}" class="form-control"  style="width: 150px">-->
                            </select>
                            <button type="submit" class="btn btn-primary"><div class="font-20"><i class="lni lni-funnel"></i></div></button>
                        </div>
                    </form>
                    <div class="table-responsive">
                        
                        <table id="example" class="table table-striped table-bordered student-table"
                            style="width:100%">
                            
                            <thead>
                               <tr>
                                    <th>S.NO</th>
                                    <th>Course Name</th>
                                    <th>Group Name</th>
                                    <th>Paper Name</th>
                                    <th>Subject Name</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!$subject->isEmpty())
                                @foreach($subject as $key => $val)
                                <tr>
                                    <td>{{ ($subject->currentpage()-1) * $subject->perpage() + $key + 1 }}</td>
                                    <td>{{getCourse($val->course_id)}}</td>
                                    <td>{{getGroup($val->group_id)}}</td>
                                    <td>{{getPaper($val->paper_id)}}</td>
                                    <td>{{$val->subject_name}}</td>
                                    @php $status = $val->status ? "checked" : ""; @endphp
                                    <td><img src="{{asset($val->subject_image)}}" class="img-fluid" height="100px" width="100px"></td>
                                    <td><div class='switch'>
                                        <div class='onoffswitch'>
                                            <input type="checkbox {{$status}}" data-id='{{$val->id}}' class='onoffswitch-checkbox status-update' id='example{{$val->id}}'>
                                            <label class='onoffswitch-label' for='example"{{$val->id}}"'>
                                                <span class='onoffswitch-inner'></span>
                                                <span class='onoffswitch-switch'></span>
                                            </label>
                                        </div>
                                    </div></td>
                                    <td><a href="{{route('edit.subject', [$val->id])}}" class="editModule" data-id="' . $val->id . '"><i class="bx bxs-edit text-success" style="font-size: 22px;"></i></a> | 
                                    <a href="#" onclick="validate('{{ $val->id }}','{{ $val->subject_name }}')"><i class="bx bxs-trash text-danger" style="font-size: 22px;"></i></a></td>
                                </tr>
                                @endforeach
                                @else
                                <tr><td style="color: red;text-align: center;" colspan="8">No Record found</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    {{ $subject->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script src="{{ asset('backend/dataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('backend/dataTables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('body').on('click', '.status-update', function() {
                let id = $(this).attr('data-id')
                let status = this.checked ? 1 : 0;
                $.ajax({
                    url: "{{ route('paper.destroy') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: id,
                        status: status,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        notify(null, 'Status Updated successfully', 'top right', 'success', false, "{{ route('list.paper') }}", 1000);
                    }
                });
            })
        });
    </script>
  <script>
    jQuery(document).ready(function (){	
	$('#course_id').change(function(){    
			  var course_id=$(this).val();
			  $.ajax({    
			  type: "POST",    
			  dataType: "html",    
			  url: "{{route('get.group.name')}}",    
			  data: {_token: "{{ csrf_token() }}",course_id: course_id }})
			  .done(function(data) { 
			 if(data!=''){
				$('#group_id').html(data);
			  }
			  });
	  });
	  
	  	$('#group_id').change(function(){    
			  var course_id=$(this).val();
			  var group_id=$('#group_id').val();
			  $.ajax({    
			  type: "POST",    
			  dataType: "html",    
			  url: "{{route('get.paper.name')}}",    
			  data: {_token: "{{ csrf_token() }}",course_id: course_id,group_id: group_id }})
			  .done(function(data) { 
			 if(data!=''){
				$('#paper_id').html(data);
			  }
			  });
	  });
   });
   </script>
   
   <script>
function validate(id, title) {
    swal({
        title: "Are you sure?",
        text: "Are you sure you want to delete this record" + " " + title,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, Delete it!",
        closeOnConfirm: false
    }, function() {
        $.ajax({
                type:'DELETE',
                url: "{{route('subject.destroy')}}" + '/' + id,
                data:{"_token": "{{ csrf_token() }}",},
                success: function (data) {
                    var jso = JSON.stringify(data); 
                    if (data.success){
                        swal({
                            title: "Deleted!",
                            text: "Your file has been deleted.",
                            type: "success",
                        });
                        location.reload();
                            }
                    }         
            });
    });
}

</script>
@stop
