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
                    <div class="form-group row required">
                        <label for="Nama" class="col-sm-3 col-form-label">Nama</label>
                        <div class="col-sm-9">
                            <input type="text" name="nama" id="Nama" class="form-control" placeholder="Nama Paket Ujian" required autofocus>
                            <div class="invalid-feedback">
                                Kolom nama tidak boleh kosong.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row required">
                        <label for="Deskripsi" class="col-sm-3 col-form-label">Deskripsi</label>
                        <div class="col-sm-9">
                            <textarea id="deskripsi" hidden required name="deskripsi"></textarea>
                            <div class="invalid-feedback">
                                Kolom deskripsi tidak boleh kosong.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row required">
                        <label for="Harga" class="col-sm-3 col-form-label">Harga</label>
                        <div class="col-sm-9">
                            <input type="number" name="harga" id="Harga" class="form-control" placeholder="Harga Ujian" required>
                            <div class="invalid-feedback">
                                Kolom harga tidak boleh kosong.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row required">
                        <label for="Waktu_mulai" class="col-sm-3 col-form-label datetimepicker-input">Mulai Pendaftaran</label>
                        <div class="col-sm-9">
                            <input type="text" name="waktu_mulai" id="Waktu_mulai" class="form-control datetimepicker-input" placeholder="Mulai Ujian" data-toggle="datetimepicker" data-target="#Waktu_mulai" required>
                            <div class="invalid-feedback">
                                Kolom mulai pendaftaran tidak boleh kosong.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row required">
                        <label for="Waktu_akhir" class="col-sm-3 col-form-label datetimepicker-input">Selesai Pendaftaran</label>
                        <div class="col-sm-9">
                            <input type="text" name="waktu_akhir" id="Waktu_akhir" class="form-control datetimepicker-input" placeholder="Selesai Ujian" data-toggle="datetimepicker" data-target="#Waktu_akhir" required>
                            <div class="invalid-feedback">
                                Kolom selesai pendaftaran tidak boleh kosong.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Ujians" class="col-sm-3 col-form-label">Daftar Ujian</label>
                        <div class="col-sm-9">
                            <select class="form-control ujians" id="Ujians" name="ujians[]" multiple="multiple" data-placeholder="Daftar Ujian" style="width: 100%;">
                                @foreach ($ujians as $ujian)
                                    <option value="{{ $ujian->id }}">{{ $ujian->nama }}</option>
                                @endforeach
                            </select>
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
