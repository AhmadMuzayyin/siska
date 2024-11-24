@extends('layouts.app')

@section('content')
    <x-hero-section />
    <!-- Search Start -->
    <div class="container-fluid bg-primary mb-5 wow fadeIn" data-wow-delay="0.1s" style="padding: 35px;">
        <div class="container">
            <div class="row g-2">
                <div class="col-12 text-center">
                    <h4 class="text-white">
                        MENGAJI UNTUK MASA DEPAN
                    </h4>
                </div>
            </div>
        </div>
    </div>
    <!-- Search End -->

    <!-- About Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="about-img position-relative overflow-hidden p-5 pe-0">
                        <img class="img-fluid w-100" src="{{ asset('assets/img/about.jpg') }}">
                    </div>
                </div>
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                    <h1 class="mb-4">Visi dan Misi</h1>
                    <p class="mb-4">
                        <strong>Visi:</strong><br>
                        Menjadi lembaga pendidikan Al-Quran terkemuka yang menghasilkan generasi Qurani yang berakhlak
                        mulia,
                        berprestasi, dan bermanfaat bagi masyarakat.
                    </p>
                    <p class="mb-4">
                        <strong>Misi:</strong>
                    </p>
                    <p><i class="fa fa-check text-primary me-3"></i>Menyelenggarakan pendidikan Al-Quran yang berkualitas
                        dengan
                        metode pembelajaran yang efektif dan menyenangkan</p>
                    <p><i class="fa fa-check text-primary me-3"></i>Membentuk karakter dan kepribadian siswa berdasarkan
                        nilai-nilai Islam dan Al-Quran</p>
                    <p><i class="fa fa-check text-primary me-3"></i>Mengembangkan potensi siswa melalui program-program
                        pendidikan yang komprehensif</p>
                    <p><i class="fa fa-check text-primary me-3"></i>Membangun kerjasama yang baik dengan orang tua dan
                        masyarakat dalam mendidik generasi Qurani</p>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->
@endsection
