<div class="modal fade" id="modal-form">
    <div class="modal-dialog">
        <form action="" method="post" class="form-horizontal needs-validation" autocomplete="off" novalidate>
            @csrf
            @method('post')

            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="Nama" class="col-sm-3 col-form-label">Nama</label>
                        <div class="col-sm-9">
                            <input type="text" name="nama" id="Nama" class="form-control" placeholder="Nama Ujian" required autofocus>
                            <div class="invalid-feedback">
                                Kolom nama tidak boleh kosong.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Waktu_mulai" class="col-sm-3 col-form-label datetimepicker-input">Mulai Ujian</label>
                        <div class="col-sm-9">
                            <input type="text" name="waktu_mulai" id="Waktu_mulai" class="form-control datetimepicker-input" placeholder="Mulai Ujian" data-toggle="datetimepicker" data-target="#Waktu_mulai" required>
                            <div class="invalid-feedback">
                                Kolom mulai ujian tidak boleh kosong.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Waktu_akhir" class="col-sm-3 col-form-label datetimepicker-input">Selesai Ujian</label>
                        <div class="col-sm-9">
                            <input type="text" name="waktu_akhir" id="Waktu_akhir" class="form-control datetimepicker-input" placeholder="Selesai Ujian" data-toggle="datetimepicker" data-target="#Waktu_akhir" required>
                            <div class="invalid-feedback">
                                Kolom selesai ujian tidak boleh kosong.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Lama_pengerjaan" class="col-sm-3 col-form-label">Lama Pengerjaan</label>
                        <div class="col-sm-3">
                            <input type="number" name="jam" id="Lama_pengerjaan" class="form-control" placeholder="Jam" min="0" required>
                            <div class="invalid-feedback">
                                Kolom jam tidak boleh kosong.
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input type="number" name="menit" id="Menit" class="form-control" placeholder="Menit" min="0" max="59" required>
                            <div class="invalid-feedback">
                                Kolom menit tidak boleh kosong.
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input type="number" name="detik" id="Detik" class="form-control" placeholder="Detik" min="0" max="59" required>
                            <div class="invalid-feedback">
                                Kolom detik tidak boleh kosong.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Harga" class="col-sm-3 col-form-label">Harga</label>
                        <div class="col-sm-9">
                            <input type="number" name="harga" id="Harga" class="form-control" placeholder="Harga Ujian" required>
                            <div class="invalid-feedback">
                                Kolom harga tidak boleh kosong.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Jumlah_soal" class="col-sm-3 col-form-label">Jumlah Soal</label>
                        <div class="col-sm-9">
                            <input type="number" name="jumlah_soal" id="Jumlah_soal" class="form-control" placeholder="Jumlah Soal Ujian" required>
                            <div class="invalid-feedback">
                                Kolom jumlah soal tidak boleh kosong.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
