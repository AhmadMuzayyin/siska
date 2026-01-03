<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ $title ?? '-' }}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="{{ $meta_keyword ?? '-' }}" name="keywords">
    <meta content="{{ $meta_deskripsi ?? '-' }}" name="description">
    <meta name="google-adsense-account" content="ca-pub-3998682868826922">

    <!-- Favicon -->
    <link href="{{ asset($favicon ?? '-') }}" rel="icon">
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap"
        rel="stylesheet">
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Libraries Stylesheet -->
    <link href="{{ asset('assets/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Template Stylesheet -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    {{-- adsense --}}
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3998682868826922"
     crossorigin="anonymous"></script>
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        @include('layouts.navbar')


        @yield('content')


        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-6 col-md-6">
                        <h5 class="text-white mb-4">Hubungi Kami</h5>
                        <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>{{ $alamat ?? '-' }}</p>
                        <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>{{ $telepon ?? '-' }}</p>
                        <p class="mb-2"><i class="fa fa-envelope me-3"></i>{{ $email ?? '-' }}</p>
                        <div class="d-flex pt-2">
                            <a class="btn btn-outline-light btn-social" href=""><i
                                    class="fab fa-twitter"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i
                                    class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i
                                    class="fab fa-youtube"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i
                                    class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Tautan Cepat</h5>
                        <a class="btn btn-link text-white-50" href="{{ route('about') }}">About Us</a>
                        <a class="btn btn-link text-white-50" href="{{ route('kontak') }}">Contact Us</a>
                        <a class="btn btn-link text-white-50" href="{{ route('privacy') }}">Privacy Policy</a>
                        <a class="btn btn-link text-white-50" href="{{ route('terms') }}">Terms & Condition</a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Ikuti Kami</h5>
                        <p>
                            Silahkan isi email anda untuk menerima berita terbaru dari MQ-Alamin
                        </p>
                        <div class="position-relative mx-auto" style="max-width: 400px;">
                            <input class="form-control bg-transparent w-100 py-3 ps-4 pe-5" type="text"
                                placeholder="Email Anda" id="subscribe-email" name="">
                            <button type="submit" id="subscribe-button"
                                class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">Ikuti</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            &copy; <a class="border-bottom" href="#">{{ $title ?? '-' }}</a>, All Right
                            Reserved.

                            <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
                             <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a>
                            X <a class="border-bottom" href="https://github.com/AhmadMuzayyin">USTDEV</a>
                        </div>
                        <div class="col-md-6 text-center text-md-end">
                            <div class="footer-menu">
                                <a href="{{ route('index') }}">Home</a>
                                <a href="{{ route('cookies') }}">Cookies</a>
                                <a href="{{ route('kontak') }}">Help</a>
                                <a href="">FQAs</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
        <button type="button" class="btn btn-lg btn-primary btn-lg-square whatsapp" id="whatsapp-button">
            <i class="bi bi-whatsapp"></i>
        </button>
        <div class="alert alert-info fixed-bottom text-center mb-0" role="alert">
            Kami menggunakan cookie untuk meningkatkan pengalaman Anda di website kami. Dengan melanjutkan penggunaan,
            Anda menyetujui Kebijakan Cookie kami.
            <a href="{{ route('cookies') }}" class="alert-link">Pelajari lebih lanjut</a>.
            <button class="btn btn-sm btn-primary ms-2" id="cookie-agree-button">Saya Setuju</button>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('assets/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('assets/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @stack('scripts')
    <script>
        $(document).ready(function() {
            if (!document.cookie.includes('cookie-consent=true')) {
                $('.alert').fadeIn('slow');
            } else {
                $('.alert').hide();
            }
            $('#subscribe-button').click(function() {
                var email = $('#subscribe-email').val();
                var _token = "{{ csrf_token() }}";
                $.ajax({
                    url: "{{ route('subscribe.store') }}",
                    method: "POST",
                    data: {
                        email: email,
                        _token: _token
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: response.message,
                            confirmButtonColor: '#0EBD94',
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Gagal',
                            text: xhr.responseJSON.message,
                            confirmButtonColor: '#FF0000',
                        });
                    }
                });
            });
            $('#whatsapp-button').click(function() {
                window.open(
                    'https://wa.me/{{ $telepon ?? '-' }}?text=Assalamualaikum%20Ustadz%2C%20saya%20ingin%20mengetahui%20informasi%20lebih%20lanjut%20tentang%20MQ-Alamin',
                    '_blank');
            });
            $('#cookie-agree-button').click(function() {
                document.cookie = "cookie-consent=true; expires=" + new Date(new Date().getTime() + 365 *
                    24 * 60 * 60 * 1000).toUTCString() + "; path=/";
                $('.alert').fadeOut('slow', function() {
                    $(this).alert('close');
                });
            });
        });
    </script>
</body>

</html>
