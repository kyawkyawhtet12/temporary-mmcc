@extends('layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
@endsection

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Banner Image</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                                <li class="breadcrumb-item active">Banner Image</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">                    
                        <div class="card-body">
                            @if(!$data)
                                <div class="d-flex justify-content-end">
                                    <a href="#" class='btn btn-success btn-sm ml-3 my-2' id="add" > Add Banner Image </a>
                                </div>
                            @endif
                            <table id="table_id" class="display">
                                <thead>
                                    <tr>
                                        <th>Photo</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if( $data )
                                    <tr>
                                        <td>
                                            <img src="{{ asset($data->image) }}" style="width: 150px; height: 150px" class="img-reponsive">
                                        </td>
                                        <td>
                                            <a href="#" class='btn btn-outline-success mb-1' id="add" >
                                                Edit
                                            </a>
                                            <form action="{{ route('banner.destroy',$data->id) }}" method="POST">  
                                                @csrf
                                                @method('DELETE')      
                                                <button onclick="return confirm('Are you sure want to delete this?')" type="submit" class="btn btn-outline-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                                                        
                        </div><!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                
            </div>

        </div>
        <!-- container-fluid -->
    </div>

    <div class="modal fade" id="ajaxModel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('banner.store') }}" method="post" id="form" name="form" class="form-horizontal" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group">
                            <label for="image" class="col-sm-12 control-label"> Image </label>
                            <div class="col-sm-12">
                                <input type="file" class="form-control" id="image" name="image" required="" accept="images/*">
                            </div>
                        </div>
       
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
                          </button>
                        </div>
                  </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
  
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>

<script>
    $(document).ready( function () {
        $('#table_id').DataTable();        

        $('#add').click(function () {            
            $('#ajaxModel').modal('show');
        });
    } );

</script>
@endsection