@extends('layouts.user.app')

@section('title')
{{ $ujian->nama }}
@endsection

@section('breadcrumb')
@parent
<li class="breadcrumb-item text-sm text-dark active" aria-current="page">Ujian</li>
@endsection

@section('content')
@foreach ($soal as $item)
<div class="col-12">
    <div class="card h-100">
        <div class="card-header pb-0 p-3">
            <div class="row">
                <div class="d-flex align-items-center">
                    <h6 class="mb-0">Nomor <span class="badge badge-sm bg-gradient-info">{{ $soal->currentPage() }}</span></h6>
                </div>
            </div>
        </div>
        <div class="card-body p-3">
            <p class="text-sm">{{ $item->soal }}</p>
            <hr class="horizontal gray-light my-4">
            <ul class="list-group">
                @foreach ($item->jawaban as $key => $jawaban)
                <li class="list-group-item border-0 ps-0 pt-0 mb-2">
                    <button type="button" class="btn bg-gradient-info mb-0 mx-2">{{ chr($key + 65) }}</button>
                    {{ $jawaban->jawaban }}
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endforeach
@for ($i = 1; $i <= $soal->total(); $i++)
    {{ $i }}
@endfor
@endsection

@push('scripts')

@endpush
