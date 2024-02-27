<div class="modal fade" id="modal-form">
    <div class="modal-dialog modal-lg">
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
                    <div class="form-group row required">
                        <label for="Nama" class="col-sm-3 col-form-label control-label">Nama</label>
                        <div class="col-sm-9">
                            <input type="text" name="nama" id="Nama" class="form-control" placeholder="Nama Ujian" required autofocus>
                            <div class="invalid-feedback">
                                Kolom nama tidak boleh kosong.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row required">
                        <label for="Jenis_ujian" class="col-sm-3 col-form-label control-label">Jenis Ujian</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="jenis_ujian" id="Jenis_ujian" required>
                                <option value="">-- Pilih Jenis Ujian --</option>
                                <option value="skd">SKD</option>
                                <option value="mtk">Matematika</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                            <div class="invalid-feedback">
                                Kolom jenis ujian tidak boleh kosong.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="deskripsi" class="col-sm-3 col-form-label control-label">Deskripsi</label>
                        <div class="col-sm-9">
                            <textarea id="deskripsi" class="form-control" hidden name="deskripsi"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="peraturan" class="col-sm-3 col-form-label control-label">Peraturan</label>
                        <div class="col-sm-9">
                            <textarea id="peraturan" class="form-control" hidden name="peraturan"></textarea>
                        </div>
                    </div>
                    <div class="form-group row required">
                        <label for="Waktu_mulai" class="col-sm-3 col-form-label control-label datetimepicker-input">Mulai Ujian</label>
                        <div class="col-sm-9">
                            <input type="text" name="waktu_mulai" id="Waktu_mulai" class="form-control datetimepicker-input" placeholder="Mulai Ujian" data-toggle="datetimepicker" data-target="#Waktu_mulai" required>
                            <div class="invalid-feedback">
                                Kolom mulai ujian tidak boleh kosong.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row required">
                        <label for="Waktu_akhir" class="col-sm-3 col-form-label control-label datetimepicker-input">Selesai Ujian</label>
                        <div class="col-sm-9">
                            <input type="text" name="waktu_akhir" id="Waktu_akhir" class="form-control datetimepicker-input" placeholder="Selesai Ujian" data-toggle="datetimepicker" data-target="#Waktu_akhir" required>
                            <div class="invalid-feedback">
                                Kolom selesai ujian tidak boleh kosong.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row required">
                        <label for="Waktu_pengumuman" class="col-sm-3 col-form-label control-label datetimepicker-input">Pengumuman Ujian</label>
                        <div class="col-sm-9">
                            <input type="text" name="waktu_pengumuman" id="Waktu_pengumuman" class="form-control datetimepicker-input" placeholder="Pengumuman Ujian" data-toggle="datetimepicker" data-target="#Waktu_pengumuman" required>
                            <div class="invalid-feedback">
                                Kolom pengumuman ujian tidak boleh kosong.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row required">
                        <label for="lama_pengerjaan" class="col-sm-3 col-form-label control-label">Lama Pengerjaan</label>
                        <div class="col-sm-9">
                            <input type="number" name="lama_pengerjaan" id="lama_pengerjaan" class="form-control" placeholder="Lama Pengerjaan" required>
                            <div class="invalid-feedback">
                                Kolom lama pengerjaan tidak boleh kosong.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row required">
                        <label for="Jumlah_soal" class="col-sm-3 col-form-label control-label">Jumlah Soal</label>
                        <div class="col-sm-9">
                            <input type="number" name="jumlah_soal" id="Jumlah_soal" class="form-control" placeholder="Jumlah Soal Ujian" required>
                            <div class="invalid-feedback">
                                Kolom jumlah soal tidak boleh kosong.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row required">
                        <label for="Tipe_ujian" class="col-sm-3 col-form-label control-label">Tipe Ujian</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="tipe_ujian" id="Tipe_ujian" required>
                                <option value="1">Sekali Ujian</option>
                                <option value="2">Periodik</option>
                            </select>
                            <div class="invalid-feedback">
                                Kolom tipe ujian tidak boleh kosong.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row required">
                        <label for="Tampil_kunci" class="col-sm-3 col-form-label control-label">Tampilkan Kunci</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="tampil_kunci" id="Tampil_kunci" required>
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                                <option value="2">Ya, setelah ujian ditutup</option>
                            </select>
                            <div class="invalid-feedback">
                                Kolom tampilkan kunci tidak boleh kosong.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row required">
                        <label for="Tampil_nilai" class="col-sm-3 col-form-label control-label">Tampilkan Nilai</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="tampil_nilai" id="Tampil_nilai" required>
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                                <option value="2">Ya, setelah ujian ditutup</option>
                            </select>
                            <div class="invalid-feedback">
                                Kolom tampilkan nilai tidak boleh kosong.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row required">
                        <label for="Tampil_poin" class="col-sm-3 col-form-label control-label">Tampilkan Poin di Soal</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="tampil_poin" id="Tampil_poin" required>
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                            <div class="invalid-feedback">
                                Kolom tampilkan poin tidak boleh kosong.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row required">
                        <label for="Random" class="col-sm-3 col-form-label control-label">Acak Soal</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="random" id="Random" required>
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                            <div class="invalid-feedback">
                                Kolom acak soal tidak boleh kosong.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row required">
                        <label for="Random_pilihan" class="col-sm-3 col-form-label control-label">Acak Pilihan</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="random_pilihan" id="Random_pilihan" required>
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                            <div class="invalid-feedback">
                                Kolom acak pilihan tidak boleh kosong.
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
