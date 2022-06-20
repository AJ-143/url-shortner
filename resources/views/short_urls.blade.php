@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>URL Generation</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">URL Shortening</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
      <div id="msg-error"></div>  
    <div class="card">  
      <div class="card-header">  
        <form method="POST" id="generateForm">  
            @csrf  
            <div class="input-group mb-3">
            <input type="text" name="title" id="title" class="form-control" placeholder="Title">  
            <span class="text-danger" id="title-error"></span> 
            </div> 
            <div class="input-group mb-3">
              <input type="text" name="link" id="link" class="form-control" placeholder="Enter URL" aria-label="Recipient's username" aria-describedby="basic-addon2">  
              <span class="text-danger" id="link-error"></span>
              <div class="input-group-append">  
                <button class="btn btn-success" type="submit">Generate Short URL</button>  
              </div>  
            </div>  
        </form>  
      </div>   
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Short URL Listing</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped data-table table-responsive">
                    <thead>
                      <tr>
                        <th>Action</th>  
                        <th>Title</th> 
                        <th>Short URL</th>  
                        <th>URL</th>  
                        <th>Created At</th>  
                      </tr>
                    </thead>
                    <tbody></tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
      </div>
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  @endsection
  
  @section('scripts')
      

  <script type="text/javascript">
      $(function () {
          var table = $('.data-table').DataTable({
              processing: true,
              serverSide: true,
              autoWidth:false,
              ajax: "{{ route('generate.url') }}",
              columns: [
                  {data: 'action', name: 'action', orderable: false, searchable: false},
                  {data: 'title', name: 'title'},
                  {data: 'short_url', name: 'short_url'},
                  {data: 'url', name: 'url'},
                  {data: 'created_at', name: 'created_at'}
              ]
          });
      //DATA TABLE LOAD END HERE
      $('.filter').change(function(){
              table.draw();
      });
      });

      $('#generateForm').on('submit',function(e){
        e.preventDefault();

        let title = $('#title').val();
        let link = $('#link').val();

        $.ajax({
          url: "{{ route('generate.shorten.link.post') }}",
          type:"POST",
          data:{
            "_token": "{{ csrf_token() }}",
            link:link,
            title:title
          },
          success:function(response){
            if (response.status == true) {
              $('#msg-error').append(`<div class="alert alert-success alert-dismissible fade show" role="alert">`+response.message+`<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>`);
              $('.data-table').DataTable().ajax.reload();
              $("#generateForm")[0].reset(); 
            }else{
              $('#msg-error').append(`<div class="alert alert-danger alert-dismissible fade show" role="alert">`+response.message+`<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>`);
            }
          }
          });
        });

      $(document).on('click','.copylink',function(e){
        e.preventDefault();
        var link = $(this).attr('link');
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val(link).select();
        document.execCommand("copy");
        $temp.remove()
        $('#msg-error').append(`<div class="alert alert-info alert-dismissible fade show" role="alert">Link Copied! <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>`);
    })
  </script>
  @endsection