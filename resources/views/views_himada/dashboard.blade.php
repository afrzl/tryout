@extends('layouts/admin/app')

@section('title')
Dashboard
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Peserta Ujian</span>
                    <span style="font-size: 150%; font-weight: bold">
                        {{ $pembelian }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@endpush
