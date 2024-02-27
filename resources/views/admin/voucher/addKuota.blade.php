<div class="modal fade" id="modal-addKuota">
    <div class="modal-dialog">
        <form action="" method="post" class="form-horizontal" novalidate>
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
                    <div class="form-group required">
                        <label for="Kode" class="col-form-label">Kode</label>
                        <input type="text" name="kode" id="Kode" class="form-control" readonly placeholder="Kode voucher">
                        <div class="invalid-feedback">
                            Kolom kode tidak boleh kosong.
                        </div>
                    </div>
                    <div class="form-group required">
                        <label for="Tambah" class="col-form-label">Kuota Tambahan</label>
                        <input type="number" name="tambah" id="Tambah" class="form-control" placeholder="Kode voucher">
                        <div class="invalid-feedback">
                            Kolom kuota tambahan tidak boleh kosong.
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
