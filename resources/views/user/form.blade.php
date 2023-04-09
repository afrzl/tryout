<div class="modal fade" id="modal-form">
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
                        <label for="Name" class="col-sm-3 col-form-label">Nama</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" id="Name" class="form-control" placeholder="Nama Member" required autofocus>
                            <div class="invalid-feedback">
                                Kolom nama tidak boleh kosong.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Email" class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-9">
                            <input type="email" name="email" id="Email" class="form-control" placeholder="Email Member" required>
                            <div class="invalid-feedback">
                                Kolom email tidak boleh kosong.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Password" class="col-sm-3 col-form-label">Password</label>
                        <div class="col-sm-9 input-group">
                            <input type="text" name="password" id="Password" class="form-control" placeholder="Password Member" required>
                            <span class="input-group-append">
                                <a type="button" onclick="generatePass('new')" class="btn btn-outline-primary">Generate</a>
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
