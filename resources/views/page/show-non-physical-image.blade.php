@extends('layout/index')

@section('title')
<title>Detail Bukti Non Fisik</title>
@endsection

@section('view-of-content')
    <div class="content-wrapper">
        <div class="wrap-component-menus">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex flex-column">
                    <p class="desc-item-name">Bukti non fisik dari asset :</p>
                    <h5 class="item-name">{{ $name }}</h5>
                </div>
                <a href="/asset-management" class="button-warm">Kembali</a>
            </div>
            <div class="mt-3">
                @if ($software_evidence)
                    <p>Link Bukti Non Fisik:</p>
                    <a href="{{ $software_evidence }}" target="_blank" class="button-primary">Buka Link</a>
                @else 
                    <div class="container d-flex justify-content-center align-items-center" style="min-height : 50vh">
                        <div class="d-flex flex-column justify-content-center align-items-center">
                            <img src="{{ asset('assets/img/no_file.svg') }}" alt="file not found" style="width:88px;">
                            <h6 class="mt-4">Belum ada bukti non fisik yang diunggah.</h6>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
