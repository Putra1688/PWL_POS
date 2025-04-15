@extends('layouts.template') 
 
@section('content') 
<div class="card"> 
    <div class="card-header"> 
        <h3 class="card-title">Data Stok Barang</h3> 
        <div class="card-tools"> 
            <button onclick="modalAction('{{ url('/stok/create_ajax') }}')" class="btn btn-success">Tambah Stok (Ajax)</button> 
        </div> 
    </div> 
    <div class="card-body"> 
        @if(session('success')) 
            <div class="alert alert-success">{{ session('success') }}</div> 
        @endif 
        @if(session('error')) 
            <div class="alert alert-danger">{{ session('error') }}</div> 
        @endif 

        <table class="table table-bordered table-sm table-striped table-hover" id="table-stok"> 
            <thead> 
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Supplier</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>User</th>
                    <th>Aksi</th>
                </tr> 
            </thead> 
            <tbody></tbody> 
        </table> 
    </div> 
</div> 

<div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false" data-width="75%"></div> 
@endsection 
 
@push('js') 
<script> 
    function modalAction(url = ''){ 
        $('#myModal').load(url,function(){ 
            $('#myModal').modal('show'); 
        }); 
    } 

    var tableStok; 
    $(document).ready(function(){ 
        tableStok = $('#table-stok').DataTable({ 
            processing: true, 
            serverSide: true, 
            ajax: { 
                "url": "{{ url('stok/list') }}", 
                "dataType": "json", 
                "type": "POST", 
                "data": function (d) { 
                    // Tambahkan filter kalau nanti dibutuhkan
                } 
            }, 
            columns: [
                { data: "stok_id", className: "text-center", width: "5%", orderable: false, searchable: false },
                { data: "stok_tanggal", width: "15%" },
                { data: "supplier.supplier_nama", width: "20%" },
                { data: "barang.barang_nama", width: "20%" },
                { data: "stok_jumlah", className: "text-right", width: "10%" },
                { data: "user.nama", width: "15%" },
                { data: "aksi", className: "text-center", width: "15%", orderable: false, searchable: false }
            ]
        });

        $('#table-stok_filter input').unbind().bind().on('keyup', function(e){ 
            if(e.keyCode == 13){ 
                tableStok.search(this.value).draw(); 
            } 
        }); 
    }); 
</script> 
@endpush
