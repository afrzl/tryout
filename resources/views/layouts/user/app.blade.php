<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>@yield('title') &mdash; {{ config('app.name', 'Laravel') }}</title>
    <meta content="Website Tryout UKM Bimbel STIS" name="description">
    <meta content="UKM Bimbel" name="keywords">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <!-- Favicons -->
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('Bootslander') }}/assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="{{ asset('Bootslander') }}/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('Bootslander') }}/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('Bootslander') }}/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="{{ asset('Bootslander') }}/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="{{ asset('Bootslander') }}/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="{{ asset('Bootslander') }}/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('adminLTE') }}/plugins/toastr/toastr.min.css">

    <!-- Template Main CSS File -->
    <link href="{{ asset('Bootslander') }}/assets/css/style.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('adminLTE') }}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('adminLTE') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('adminLTE') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <style>
        .btn-primary {
            background-color: #1a1d94;
            border-color: #232199;
        }

        .badge-primary {
            background-color: #1a1d94;
        }

        .btn-primary:hover {
            background-color: #23007B;
            border-color: #23007B;
        }

        .btn-primary:active {
            background-color: #23007B;
            border-color: #23007B;
        }

        .btn-primary:disabled {
            background-color: #23007B;
            border-color: #23007B;
        }

        .form-group.required .control-label:after {
            content: " *";
            color: red;
        }

        .form-group.required .col-form-label:after {
            content: " *";
            color: red;
        }

    </style>

    @stack('links')
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top d-flex align-items-center">
        <div class="container d-flex align-items-center justify-content-between">

            <div class="logo ">
                <h1>
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo Bimbel">
                        <span class="align-middle"> {{ config('app.name', 'Laravel') }}</span>
                    </a>
                </h1>
            </div>

            @include('layouts.user.navbar')

        </div>
    </header><!-- End Header -->

    <!-- ======= Hero Section ======= -->
    @yield('content')

    <!-- ======= Footer ======= -->
    <footer id="footer">
        <div class="footer-top">
            <div class="container">
                <div class="row">

                    <div class="col-12">
                        <div class="footer-info">
                            <div class="d-flex flex-row">
                                <div class="col-lg-8">
                                    <h3><img src="{{ asset('img/logo.png') }}" alt="Logo Bimbel" width="5%" class="mb-2"> UKM Bimbel</h3>
                                    <p class="pb-3"><em>UKM Bimbel merupakan salah satu UKM dari Unit Pendidikan dan Kebudayaan di bawah naungan BEM Polstat STIS. UKM Bimbel memiliki  program kerja Try Out Akbar (TOBAR), Try Out Nasional (TONAS), dan Bimbingan Intensif USM STIS (BIUS) untuk membantu para calon mahasiswa/i mempersiapkan SPMB Polstat STIS. Sudah banyak para pejuang yang menjadikan UKM Bimbel sebagai teman yang membantu  mempersiapkan SPMB Polstat STIS. Hal ini dikarenakan bentuk dan mekanisme Try Out maupun Bimbingan yang ada di UKM Bimbel dibuat langsung oleh mahasiswa/i Polstat STIS. Sehingga sangat relevan dengan mekanisme dan aturan SPMB yang terbaru.</em></p>
                                </div>
                                <div class="col-lg-1"></div>
                                <div class="col-lg-3 mt-5">
                                    <p class="mt-4">
                                        Jalan Otto Iskandar Dinata 64C <br>
                                        Jakarta Timur, DKI Jakarta<br><br>
                                        <strong>Email:</strong> <a style="color: inherit;" href="mailto:ukmbimbel@stis.ac.id">ukmbimbel@stis.ac.id</a><br>
                                    </p>
                                    <div class="social-links mt-3">
                                        <a href="https://twitter.com/UKMBimbelSTIS" target="_blank" class="twitter"><i class="bx bxl-twitter"></i></a>
                                        <a href="https://www.facebook.com/BimbelSTIS/" target="_blank" class="facebook"><i class="bx bxl-facebook"></i></a>
                                        <a href="https://www.instagram.com/ukmbimbelstis/" target="_blank" class="instagram"><i class="bx bxl-instagram"></i></a>
                                        <a href="https://www.youtube.com/@BimbelStis" target="_blank" class="youtube"><i class="bx bxl-youtube"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="copyright">
                Copyright &copy; UKM Bimbel 2024
            </div>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="{{ asset('Bootslander') }}/assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="{{ asset('Bootslander') }}/assets/vendor/aos/aos.js"></script>
    <script src="{{ asset('Bootslander') }}/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('Bootslander') }}/assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="{{ asset('Bootslander') }}/assets/vendor/swiper/swiper-bundle.min.js"></script>
    {{-- <script src="{{ asset('Bootslander') }}/assets/vendor/php-email-form/validate.js"></script> --}}

    <!-- jQuery -->
    <script src="{{ asset('adminLTE') }}/plugins/jquery/jquery.min.js"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('Bootslander') }}/assets/js/main.js"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('adminLTE') }}/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Toastr -->
    <script src="{{ asset('adminLTE') }}/plugins/toastr/toastr.min.js"></script>
    <!-- Select2 -->
    <script src="{{ asset('adminLTE') }}/plugins/select2/js/select2.full.min.js"></script>
    <!-- Alpinejs -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
    <script>
        toastr.options = {
            "positionClass": "toast-bottom-right"
        };

    </script>

    @stack('scripts')
</body>

</html>
