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
        <h3 class="card-title">{{ $page->title }}</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-sm table-striped table-hover" id="table-rekap-stok"> 
            <thead> 
                <tr>
                    <th>No</th>
                    <th>Barang</th>
                    <th>Total Stok</th>
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
    $(document).ready(function(){
        $('#table-rekap-stok').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ '/rekaplist' }}",
                type: "POST",
            },
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    className: 'text-center',
                    orderable: false,
                    searchable: false,
                    width: '5%'
                },
                {
                    data: 'barang_nama',
                    name: 'barang_nama',
                    width: '60%'
                },
                {
                    data: 'stok_jumlah',
                    name: 'stok_jumlah',
                    className: 'text-center',
                    width: '15%'
                }
            ]
        });
    });
</script>
@endpush
