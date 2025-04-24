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
                    <label>Kode Penjualan</label> 
                    <input value="" type="text" name="penjualan_kode" id="penjualan_kode" class="form-control" required> 
                    <small id="error-penjualan_kode" class="error-text form-text text-danger"></small> 
                </div> 
                <div class="form-group"> 
                    <label>Barang</label> 
                    <select name="barang_id[]" id="barang_id" class="form-control" required> 
                        <option value="">- Pilih Barang -</option> 
                        @foreach($barang as $l) 
                            <option value="{{ $l->barang_id }}">{{ $l->barang_nama }}</option> 
                        @endforeach 
                    </select> 
                    <small id="error-barang_id" class="error-text form-text text-danger"></small> 
                </div>                
                <div class="form-group"> 
                    <label>User</label> 
                    <select name="user_id" id="user_id" class="form-control" required readonly>
                        <option value="{{ auth()->user()->user_id }}" selected>
                            {{ auth()->user()->nama }}
                        </option>
                    </select>                    
                    <small id="error-user_id" class="error-text form-text text-danger"></small> 
                </div> 
                <div class="form-group"> 
                    <label>Tanggal</label> 
                    <input value="{{ date('Y-m-d') }}" type="date" name="penjualan_tanggal" id="penjualan_tanggal" class="form-control" required> 
                    <small id="error-penjualan_tanggal" class="error-text form-text text-danger"></small> 
                </div> 
                
                <div class="form-group"> 
                    <label>Jumlah</label> 
                    <input value="" type="number" name="jumlah[]" id="jumlah" class="form-control" required min="1"> 
                    <small id="error-jumlah" class="error-text form-text text-danger"></small> 
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
                    pembeli: { required: true, minlength: 3 }, // Bisa sesuaikan dengan kebutuhan validasi
                    penjualan_kode: { required: true, minlength: 5 }, // Validasi panjang kode penjualan
                    barang_id: { required: true },  // Tidak perlu validasi number, karena ini select
                    user_id: { required: true },  // Tidak perlu validasi number, karena ini read-only
                    penjualan_tanggal: { required: true, date: true },
                    jumlah: { required: true, number: true, min: 1 }  // Validasi jumlah, memastikan input adalah angka
   
                },
                submitHandler: function(form) { 
                    $.ajax({ 
                        url: form.action, 
                        type: form.method, 
                        data: $(form).serialize(), 
                        success: function(response) { 
                            if(response.status){ 
                                $('#modal-master').modal('hide'); 
                                Swal.fire({ 
                                    icon: 'success', 
                                    title: 'Berhasil', 
                                    text: response.message
                                }).then(() => {
                                    // Redirect ke halaman index stok setelah alert selesai
                                    window.location.href = "{{ url('/stok') }}"; 
                                }); 
                                dataStok.ajax.reload(); 
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