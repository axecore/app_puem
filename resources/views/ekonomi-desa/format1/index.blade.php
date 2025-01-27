<x-app-layout>

    <x-slot name="title">DATA FORMAT 1</x-slot>
  
    <x-slot name="extra_css">
      <link rel="stylesheet" href="{{ asset('vendor/DataTables/datatables.min.css') }}">
      <link rel="stylesheet" href="{{ asset('vendor/DataTables/DataTables-1.10.24/css/dataTables.bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('vendor/iziToast/dist/css/iziToast.min.css') }}">
      <link rel="stylesheet" href="{{ asset('vendor/bootstrap-colorpicker-3.2.0/dist/css/bootstrap-colorpicker.min.css') }}">
    </x-slot>
  
    <!-- Main Content -->
    <div class="main-content">
      <section class="section">
        <div class="section-header">
          <h1>DATA FORMAT 1</h1>
          <div class="section-header-button">
          </div>
          <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dasbor</a></div>
            <div class="breadcrumb-item">Data Format 1</div>
          </div>
        </div>
  
        <div class="section-body">
          <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-1">
                      <div class="m-0 p-4">
                        <div class="row">
                          <div class="col-sm-4">
                            <div class="form-group">
                              <label class="control-label" for="input-name">Kecamatan</label>
                              <select class="form-control select2" name="id_kecamatan" id="kecamatan" onchange="getDesaByIdKecamatan()" required>
                                <option value="">Pilih</option>
                                @foreach($kecamatan as $value)
                                  <option value="{{ $value->id }}">{{ $value->nama_kecamatan }}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                          <div class="col-sm-4">
                            <div class="form-group">
                              <label class="control-label" for="input-name">Desa</label>
                              <select class="form-control select2" name="id_desa" id="desa" onchange="ketDesa()" disabled>
                               
                              </select>
                            </div>
                          </div>
                          <div class="col-sm-3">
                            <div class="form-group">
                              <label class="control-label" for="input-name">Tahun</label>
                              <select class="form-control select2" name="" id="tahun" onchange="pemetaan_tahun(this.value)" required>
                                <option value="">Pilih</option>
                               @php
                                $tahun = date('Y');
                                for($i = $tahun; $i >= $tahun-5; $i--){
                                  echo "<option value='$i'>$i</option>";
                                }
                               @endphp
                              </select>
                            </div>
                          </div>
                          <div class="col-sm-4">
                            <div class="form-group">    
                              <button class="btn btn-primary" id="tampilkan" onclick="tampilkan()">Tampilkan</button>
                            </div>
                          </div>
                          <div class="col-sm-12" class="text-dark">
                            <p id="pemetaan_tahun">PEMETAAN USAHA EKONOMI DESA</p>
                            <div class="text-center">
                                <p class="text-center"><h5>INDENTITAS USAHA EKONOMI DESA</h5></p>
                            </div>
                            <table width="100%">
                                <tr>
                                  <td width="10%">Provinsi</td>
                                  <td width="1%">:</td>
                                  <td width="79%">Kalimantan Selatan</td>
                                </tr>
                                <tr>
                                  <td>Kabupaten/Kota</td>
                                  <td>:</td>
                                  <td>Tanah Laut</td>
                                </tr>
                                  <tr>
                                  <td>Kecamatan</td>
                                  <td>:</td>
                                  <td id="kec_ket"></td>
                                </tr>
                                <tr>
                                  <td>Desa/kelurahan</td>
                                  <td>:</td>
                                  <td id="des_ket"></td>
                                </tr>
                              </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <div class="card">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered" id="table">
                      <thead class="bg-primary">
                        <tr>
                            <th rowspan="2" class="text-center">No.</th>
                            <th rowspan="2" class="text-center">Jenis Usaha</th>
                            <th rowspan="2" class="text-center">Jenis Produksi</th>
                            <th rowspan="2" class="text-center">Jenis Komoditas</th>
                            <th rowspan="2" class="text-center">Luas Lahan(Ha)</th>
                            <th colspan="2" class="text-center">Swasta/Negara</th>
                            <th colspan="2" class="text-center">Rakyat</th>
                            <th rowspan="2" class="text-center">Nilai Produksi (Rp)</th>
                            <th rowspan="2" class="text-center">Pemasaran Hasil</th>
                            <th rowspan="2" class="text-center">Aksi</th>
                        </tr>
                          <tr>
                            <th>Luas (Ha)</th>
                            <th>Hasil (Ton)</th>
                            <th>Luas (Ha)</th>
                            <th>Hasil (Ton)</th>
                          </tr>
                      </thead>
                      <tbody>
                         
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  
    <x-slot name="extra_js">
      <script src="{{ asset('vendor/DataTables/datatables.min.js') }}"></script>
      <script src="{{ asset('vendor/DataTables/DataTables-1.10.24/js/dataTables.bootstrap4.min.js') }}"></script>
      <script src="{{ asset('vendor/sweetalert/dist/sweetalert.min.js') }}"></script>
      <script src="{{ asset('vendor/iziToast/dist/js/iziToast.min.js') }}"></script>
      <script src="{{ asset('vendor/bootstrap-colorpicker-3.2.0/dist/js/bootstrap-colorpicker.min.js') }}"></script>
      <script src="{{ asset('js/plugin.js') }}"></script>
      <script>
        
        $(document).ready(function() {
          
          @if (Session::has('sukses_sess'))
            swal("Berhasil", '{{ Session::get('sukses_sess') }}', "success");
          @endif

        })

        function tampilkan(){

          $('#tampilkan').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
          $('#tampilkan').prop('disabled', true);

          const kec = $('#kecamatan').val()
          const des = $('#desa').val()
          const tahun = $('#tahun').val()

          if (kec == '' || des == '' || tahun == '') {
            swal("Peringatan!", "Silahkan pilih data yang akan ditampilkan", "warning");
          }

          const formData = new FormData();
          formData.append('kec', kec);
          formData.append('des', des);
          formData.append('tahun', tahun);

          fetch('{{ route("format1-json") }}', {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: formData
          })
          .then((res) => {
            return res.json()
          })
          .then((res) => {
          
            $('#table > tbody').html(res.html)
            $('#tampilkan').html('Tampilkan');
            $('#tampilkan').prop('disabled', false);

          })

        }

        function getDesaByIdKecamatan() {

            $('#kec_ket').html( $('#kecamatan option:selected').text() ) 

            const kecamatanId = $('#kecamatan').val()

            if (kecamatanId == "" || kecamatanId == null) {
                $('#desa').empty();
                $('#desa').prop('disabled', true);
            return
            }

            $('#desa').empty();
            $('#desa').html('<option value="">Sedang Mengambil data...</option>');

            const formData = new FormData();
            formData.append('id_kecamatan', kecamatanId);

            fetch("/api/getdesabyidkecamatan", {
                    headers: {
                        "X-CSRF-Token": $('input[name="_token"]').val()
                    },
                    method: "POST",
                    credentials: "same-origin",
                    body: formData
            })
            .then((res) => {
                return res.json()
            })
            .then((res) => {

                $('#desa').empty();
                $('#desa').prop('disabled', false);
                $('#desa').append('<option value="">Pilih Desa</option>');
                
                for (const iterator of res.data) {
                
                $('#desa').append(`<option value="${iterator.id}">${iterator.nama_desa}</option>`);

                }

            })
            .catch((err) => {
                console.log(err);
            })
          }

          function ketDesa(){

            $('#des_ket').html( $('#desa option:selected').text() )

          }

          function pemetaan_tahun(tahun) {

            $('#pemetaan_tahun').html('PEMETAAN USAHA EKONOMI DESA ' + tahun)

          }
  
      </script>
    </x-slot>
  
  </x-app-layout>