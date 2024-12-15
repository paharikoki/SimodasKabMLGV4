@extends('layout/index')

@section('title')
<title>Label Aset</title>
@endsection

@section('content-delivery')
    <link rel="stylesheet" href="{{ asset('css/label.css') }}">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection

@section('view-of-content')
    <div class="content-wrapper">
        <p>Unduh file dalam format JPG</p>
        <a class="button-primary" style="width: 140px;" id="download">Unduh Label</a>
        <a class="button-warm" style="width: 140px;" href="/asset-management">Kembali</a>
    </div>
    <div id="out-image"></div>
    <div class="content-wrapper">
       <div class="d-flex justify-content-center flex-column align-items-center">
            <div class="canvas" id="capture">
                <div class="container">
                    <div class="row">
                        <div class="logo">
                            <img src="{{ asset('assets/img/logo-pemkab.svg') }}" alt="Pemkab Malang">
                        </div>
                        <div class="logo">
                            <div class="qrcode-container">
                                {!! $qrcode !!}
                            </div>
                        </div>

                        <div class="box-desc">
                            <div class="desc">
                                <b class="head">DINAS KOMUNIKASI DAN INFORMATIKA <br>KABUPATEN MALANG</b>
                            </div>
                            <div class="label-text">
                                <div class="desc">
                                    <b>
                                        Nama Barang :<span> {{ $asset->item_name }}</span>
                                    </b>
                                </div>
                                <div class="desc">
                                    <b>
                                        Merk/Brand :<span> {{ $asset->brand }}</span>
                                    </b>
                                </div>
                                <div class="desc">
                                    <b>
                                        Tahun Aggaran :<span> {{ $asset->item_year }}</span>
                                    </b>
                                </div>
                                <div class="desc">
                                    <b>
                                        Kode Reg :<span> {{ $asset->registration }}</span>
                                    </b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
       </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>

        $(document).ready(function(){

            $('#download').on('click', function(){
                if(window.innerWidth > 768){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Maaf',
                        text: 'Fitur ini hanya bisa digunakan pada layar desktop',
                        confirmButtonText: 'OK'
                    });
                    // alert('Maaf, fitur ini hanya bisa digunakan pada layar desktop');
                    return;
                }else{
                    var name = 'label-aset-{{ $asset->nibar }}.jpg';
                    name = name.replace(/\s/g, '-');
                    var filename = name;
                    html2canvas($('#capture')[0]).then((canvas)=>{
                        console.log('done ...');
                        var imageData = canvas.toDataURL('image/jpg');
                        var newdData = imageData.replace(/^data:image\/jpg/, 'data:application/octet-stream');

                        $('#download').attr('download', filename).attr('href', newdData);

                        $('#download').click();
                    });
                }
            });
        });
    </script>
@endsection

{{-- @section('content-delivery-js')

@endsection --}}

