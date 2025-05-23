<form action="{{ url('/penjualan/ajax') }}" method="POST" id="form-tambah"> 
    @csrf 
    <div id="modal-master" class="modal-dialog modal-lg" role="document"> 
        <div class="modal-content"> 
            <div class="modal-header"> 
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Transaksi</h5> 
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span 
    aria-hidden="true">&times;</span></button> 
            </div> 
            <div class="modal-body"> 

                <div class="form-group"> 
                    <label>Nama Pembeli</label> 
                    <input value="" type="text" name="pembeli" id="pembeli" class="form-control" required> 
                    <small id="error-pembeli" class="error-text form-text text-danger"></small> 
                </div> 
                <div class="form-group"> 
                    <label>Kode Penjualan Level</label> 
                    <input value="" type="text" name="penjualan_kode" id="penjualan_kode" class="form-control" required> 
                    <small id="error-penjualan_kode" class="error-text form-text text-danger"></small> 
                </div> 
                <div class="form-group">
                    <label>Barang <span class="text-danger">*</span></label>
                    <select class="form-control barang-select" name="barang_id[]" required>
                        <option value="" disabled selected>- Pilih Barang -</option>
                        @foreach($barang as $item)
                            <option value="{{ $item->barang_id }}" 
                                    data-harga="{{ $item->harga_jual }}"
                                    data-stok="{{ $item->stok->sum('stok_jumlah') }}">
                                {{ $item->barang_kode }} - {{ $item->barang_nama }}
                            </option>
                        @endforeach
                    </select>
                    <small class="error-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Harga Total</label>
                    <input type="hidden" name="harga[]" class="harga-input">
                    <input type="text" class="form-control harga-display" readonly>
                </div>
                
                
            </div> 
            <div class="modal-footer"> 
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button> 
                <button type="submit" class="btn btn-primary">Simpan</button> 
            </div> 
        </div> 
    </div> 
    </form> 
    <script> 
        $(document).ready(function() { 
            $("#form-tambah").validate({ 
                rules: { 
                    barang_id: {required: true, number: true}, 
                }, 
                submitHandler: function(form) { 
                    $.ajax({ 
                        url: form.action, 
                        type: form.method, 
                        data: $(form).serialize(), 
                        success: function(response) { 
                            if(response.status){ 
                                $('#myModal').modal('hide'); 
                                Swal.fire({ 
                                    icon: 'success', 
                                    title: 'Berhasil', 
                                    text: response.message 
                                }); 
                                dataPenjualan.ajax.reload(); 
                            }else{ 
                                $('.error-text').text(''); 
                                $.each(response.msgField, function(prefix, val) { 
                                    $('#error-'+prefix).text(val[0]); 
                                }); 
                                Swal.fire({ 
                                    icon: 'error', 
                                    title: 'Terjadi Kesalahan', 
                                    text: response.message 
                                }); 
                            } 
                        }             
                    }); 
                    return false; 
                }, 
                errorElement: 'span', 
                errorPlacement: function (error, element) { 
                    error.addClass('invalid-feedback'); 
                    element.closest('.form-group').append(error); 
                }, 
                highlight: function (element, errorClass, validClass) { 
                    $(element).addClass('is-invalid'); 
                }, 
                unhighlight: function (element, errorClass, validClass) { 
                    $(element).removeClass('is-invalid'); 
                } 
            }); 
        }); 
    </script>     