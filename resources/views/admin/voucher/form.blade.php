<div class="modal fade" id="modal-form">
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
                        <input type="text" name="kode" id="Kode" class="form-control" placeholder="Kode voucher">
                        <div class="invalid-feedback">
                            Kolom kode tidak boleh kosong.
                        </div>
                    </div>
                    <div class="form-group required">
                        <label for="Diskon" class="col-form-label">Diskon</label>
                        <input type="number" name="diskon" id="Diskon" class="form-control" placeholder="Diskon voucher">
                        <div class="invalid-feedback">
                            Kolom diskon tidak boleh kosong.
                        </div>
                    </div>
                    <div class="form-group required">
                        <label for="Kuota" class="col-form-label">Kuota</label>
                        <input type="number" name="kuota" id="Kuota" class="form-control" placeholder="Kuota voucher">
                        <div class="invalid-feedback">
                            Kolom kuota tidak boleh kosong.
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
