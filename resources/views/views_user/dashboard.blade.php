@extends('layouts.user.app')

@section('title')
Dashboard
@endsection

@section('breadcrumb')
@parent
<li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
@endsection

@section('content')
<div class="col-lg-8">
    <div class="card mb-4">
        <div class="card-header pb-0 p-3">
            <h6 class="mb-1">Daftar Tryout</h6>
        </div>
        <div class="card-body p-3">
            <div class="row">
                @foreach ($ujians as $ujian)
                <div class="col-xl-4 col-md-6 mb-xl-0 mb-4">
                    <div class="card card-blog card-plain">
                        <div class="card">
                            <div class="card-body p-3">
                                <form method="post" action="{{ route('pembelian.store') }}">
                                    @csrf
                                    @method('post')
                                    <input type="hidden" name="id_ujian" value="{{ $ujian->id }}">

                                    <a href="javascript:;">
                                        <h5>
                                            {{ $ujian->nama }}
                                        </h5>
                                    </a>
                                    <p class="text-gradient text-dark mb-2 text-sm">{{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('d F Y H:i:s') }} - <br>{{ \Carbon\Carbon::parse($ujian->waktu_akhir)->format('d F Y H:i:s') }}</p>
                                    <p class="mb-4 text-sm">
                                        <span class="badge badge-sm bg-gradient-success">Rp{{ number_format( $ujian->harga , 0 , ',' , '.' ) }}</span>
                                    </p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <button type="submit" type="button" class="btn btn-outline-primary btn-sm mb-0">Beli</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<div class="col-lg-8">
    <div class="card mb-4">
        <div class="card-header pb-0 p-3">
            <h6 class="mb-1">History Tryout</h6>
        </div>
        <div class="card-body p-3">
            <div class="row">
                @foreach ($history as $history)
                <div class="col-xl-4 col-md-6 mb-xl-0 mb-4">
                    <div class="card card-blog card-plain">
                        <div class="card">
                            <div class="card-body p-3">
                                    <a href="javascript:;">
                                        <h5>
                                            {{ $history->ujian->nama }}
                                        </h5>
                                    </a>
                                    <p class="text-gradient text-dark mb-2 text-sm">{{ \Carbon\Carbon::parse($history->ujian->waktu_mulai)->format('d F Y H:i:s') }} - <br>{{ \Carbon\Carbon::parse($history->ujian->waktu_akhir)->format('d F Y H:i:s') }}</p>
                                    <p class="mb-4 text-sm">
                                        <span class="badge badge-sm bg-gradient-success">Rp{{ number_format( $history->ujian->harga , 0 , ',' , '.' ) }}</span>
                                    </p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a href="{{ route('ujian.show', $history->ujian_id) }}" type="submit" type="button" class="btn btn-outline-primary btn-sm mb-0">Masuk</a>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
