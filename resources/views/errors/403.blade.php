<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - RKS</title>


    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ asset('mazer/compiled/css/app.cs') }}s">
    <link rel="stylesheet" href="{{ asset("mazer/compiled/css/error.css") }}">
</head>

<body>
    <script src="assets/static/js/initTheme.js"></script>
    <div id="error">


        <div class="error-page container">
            <div class="col-md-8 col-12 offset-md-2">
                <div class="text-center">
                    <img class="img-error" src="{{ asset('mazer/compiled/svg/error-403.svg') }}" alt="Not Found">
                    <h1 class="error-title">Terlarang</h1>
                    <p class="fs-5 text-gray-600">Hayooo kamu mau berbuat apa?</p>
                    <a href="/dashboard" class="btn btn-lg btn-outline-primary mt-3">Kembali</a>
                </div>
            </div>
        </div>


    </div>
</body>

</html>
