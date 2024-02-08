<div class="modal fade" id="modal-reset">
    <div class="modal-dialog">
        <form action="" method="post" class="form-horizontal needs-validation" novalidate>
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
                        <label for="Password" class="col-sm-3 col-form-label">Password</label>
                        <div class="col-sm-9 input-group">
                            <input type="text" name="password" id="PasswordReset" class="form-control" placeholder="Password Member" required>
                            <span class="input-group-append">
                                <a type="button" onclick="generatePass('reset')" class="btn btn-outline-primary">Generate</a>
                            </span>
                            <div class="invalid-feedback">
                                Kolom password tidak boleh kosong.
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
