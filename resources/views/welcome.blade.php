@extends('layouts.template')

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Halo, apakabar!!!</h3>
        <div class="card-tools"></div>
    </div>
    <div class="card-body">
        Selamat datang, ini adalah halaman utama dari web ini
    </div>
</div>

<div class="card"> 
    <div class="card-header"> 
        <h3 class="card-title">Data Stok Barang</h3>  
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
                    <th>Barang</th>
                    <th>Jumlah Stok</th>
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
            autoWidth: false,
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
                { data: "barang.barang_nama", width: "20%" },
                { data: "stok_jumlah", className: "text-center", width: "10%" },
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
