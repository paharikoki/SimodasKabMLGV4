<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/login-style.css') }}">
    <title>Details Asset</title>
    <link rel="icon" href="{{ asset('assets/img/logo-pemkab.svg') }}">
    <style>
        .label-text {
            font-size: 18px;
            font-weight: 500;
        }
        .input-text {
            font-size: 21px;
            font-weight: bold;
            pointer-events: auto;
        }
    </style>
</head>

<body>
    <div class="content-wrapper d-flex">
        <div class="side-left">
            <div class="wrapper-position-brand d-flex justify-content-center align-items-center">
                <div class="brand-wrapper">
                    <div class="box-of-brand d-flex align-items-center">
                        <div class="wrapper-logo">
                            <img src="{{ asset('assets/img/logo-pemkab.svg') }}" alt="Logo PEMKAB">
                        </div>
                        <div class="typography-box">
                            <p class="text-light">Monitoring Distribusi Aset</p>
                            <h2 class="second-typography">DINAS KOMUNIKASI <br>DAN INFORMATIKA </h2>
                            <p class="third-typography">Kabupaten Malang</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="side-right pb-5">
            <div class="wrapper-position-form d-flex justify-content-center align-items-center">
                <div class="form-wrapper">
                    <div class="wrapper-mobile-only-logo justify-content-center flex-column align-items-center mt-5 mb-3">
                        <img src="{{ asset('assets/img/logo-pemkab.svg') }}" alt="Logo PEMKAB">
                        <p class="text-light">Monitoring Distribusi Aset</p>
                        <h4 class="second-typography">DINAS KOMUNIKASI <br>DAN INFORMATIKA </h4>
                        <p class="text-light">Kabupaten Malang</p>
                    </div>
                    <form>
                        <h4 class="second-typography">Details BMD <br></h4>
                        <h3 class="second-typography">DINAS KOMUNIKASI DAN INFORMATIKA </h3>
                        <h5 class="text-light mb-4">KABUPATEN MALANG</h5>
                        <div class="mb-2">
                            <label for="item_name" class="form-label label-text">Nama Barang</label>
                            <input type="text" class="form-control input-text"  id="item_name" value="{{ $asset->item_name }}"  readonly>
                        </div>
                        <div class="mb-2">
                            <label for="brand" class="form-label label-text">Type/Merk</label>
                            <input type="text" class="form-control input-text" id="brand" value="{{ $asset->brand }}" readonly>
                        </div>
                        <div class="mb-2">
                            <label for="how_to_earn" class="form-label label-text">Asal Barang/Cara Perolehan</label>
                            <input type="text" class="form-control input-text" id="how_to_earn" value="{{ $asset->how_to_earn }}"  readonly>
                        </div>
                        <div class="mb-2">
                            <label for="item_year" class="form-label label-text">Tahun Barang</label>
                            <input type="text" class="form-control input-text" id="item_year" value="{{ $asset->item_year }}" readonly>
                        </div>
                        <div class="mb-2">
                            <label for="item_code" class="form-label label-text">Kode Barang</label>
                            <input type="text" class="form-control input-text" id="item_code" value="{{ $asset->item_code }}" readonly>
                        </div>
                        <div class="mb-2">
                            <label for="registration" class="form-label label-text">Kode Registrasi</label>
                            <input type="text" class="form-control input-text" id="registration" value="{{ substr($asset->registration, -6) }}" readonly>
                        </div>
                        <div class="mb-2">
                            <label for="nibar" class="form-label label-text">Nibar</label>
                            <textarea id="nibar" class="form-control input-text"  rows="2" readonly style="resize: none; overflow:auto;">{{ $asset->nibar }}</textarea>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>
