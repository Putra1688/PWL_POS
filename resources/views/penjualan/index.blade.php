@extends('layouts.template') 
 
@section('content') 
  <div class="card card-outline card-primary"> 
      <div class="card-header"> 
        <h3 class="card-title">{{ $page->title }}</h3> 
      </div> 
      <div class="card-body">
        @if (session('success')) 
          <div class="aler alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">
          <div class="col-md-12">
              <div class="form-group row">
                  <label class="col-1 control-label col-form-label">Filter:</label>
                  <div class="col-3">
                      <select class="form-control" id="user_id" name="user_id" required>
                          <option value="">- Semua -</option>
                          @foreach($user as $item)
                              <option value="{{ $item->user_id }}">{{ $item->user_nama }}</option>
                          @endforeach
                      </select>
                      <small class="form-text text-muted">Level Pengguna</small>
                  </div>
              </div>
          </div>
      </div>

        <table class="table table-bordered table-striped table-hover table-sm" id="table_penjualan"> 
          <thead> 
            <tr>
                <th>ID</th>
                <th>Kode Penjualan</th>
                <th>Tanggal Penjualan</th>
                <th>Nama Pembeli</th>
                <th>Aksi</th>
            </tr> 
          </thead> 
      </table> 
    </div> 
  </div>

  <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-
  backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div> 
@endsection 
 
@push('css') 
@endpush 
 
@push('js')
  <script>
  
  function modalAction(url = ''){ 
    $('#myModal').load(url,function(){ 
        $('#myModal').modal('show'); 
    }); 
  }
  
  var dataUser;
  $(document).ready(function() { 
      dataPenjualan = $('#table_penjualan').DataTable({ 
          // serverSide: true, jika ingin menggunakan server side processing 
          serverSide: true,      
          ajax: { 
              "url": "{{ url('penjualan/list') }}", 
              "dataType": "json", 
              "type": "POST",
              "data": function(d) {
                d.user_id = $('#user_id').val();
              } 
          }, 
          columns: [ 
            { 
              // nomor urut dari laravel datatable addIndexColumn() 
              data: "DT_RowIndex",             
              className: "text-center", 
              orderable: false, 
              searchable: false     
            },{ 
                data: "penjualan_kode",                
                className: "", 
                orderable: true,     
                searchable: true     
            },{  
                data: "penjualan_tanggal",                
                className: "", 
                orderable: true,     
                searchable: true     
            },{ 
              data: "pembeli",                
              className: "", 
              // orderable: true, jika ingin kolom ini bisa diurutkan  
              orderable: true,     
              // searchable: true, jika ingin kolom ini bisa dicari 
              searchable: true     
            },{ 
              data: "aksi",                
              className: "", 
              orderable: false,     
              searchable: false     
            } 
          ] 
      }); 

      $('#level_id').on('change', function() {
        dataPenjualan.ajax.reload();
      })

    }); 
  </script> 
@endpush  