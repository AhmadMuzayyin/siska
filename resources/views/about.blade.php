@extends('layouts.app')

@section('content')
    <!-- Header Start -->
    <div class="container-fluid header bg-white p-0">
        <div class="row g-0 align-items-center flex-column-reverse flex-md-row">
            <div class="col-md-6 p-5 mt-lg-5">
                <h1 class="display-5 animated fadeIn mb-4">Mari <span class="text-primary">Bersama</span> kita mengenal
                    tentang MQ Al-Amin</h1>
                <p class="animated fadeIn mb-4 pb-2">
                    MQ Al-Amin (Madrasah Qurani Al-Amin) adalah lembaga pendidikan Al-Quran yang berlokasi di Dusun Krajan,
                    Desa Wongsorejo, Kecamatan Wongsorejo, Kabupaten Banyuwangi. Berdiri sejak tahun 2015, MQ Al-Amin
                    berkomitmen untuk membentuk generasi Qurani yang berakhlak mulia dan berwawasan luas melalui pendidikan
                    Al-Quran yang berkualitas.
                </p>
            </div>
            <div class="col-md-6 animated fadeIn">
                <div class="owl-carousel header-carousel">
                    <div class="owl-carousel-item">
                        <img class="img-fluid" src="https://cdn.pixabay.com/photo/2019/05/16/03/02/ramadan-4206409_1280.jpg"
                            alt="">
                    </div>
                    <div class="owl-carousel-item">
                        <img class="img-fluid"
                            src="https://images.pexels.com/photos/20558180/pexels-photo-20558180/free-photo-of-wanita-perempuan-kaum-wanita-memegang.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1"
                            alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->


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
    <!-- Category Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInDown" data-wow-delay="0.1s" style="max-width: 600px;">
                <h1 class="mb-3">Jenis Program</h1>
                <p>
                    MQ-Alamin menyediakan berbagai jenis program untuk anak-anak Qurani, mulai dari program dasar
                    hingga program lanjutan.
                </p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                    <a class="cat-item d-block bg-light text-center rounded p-3" href="">
                        <div class="rounded p-4">
                            <div class="icon mb-3">
                                <img class="img-fluid" src="{{ asset('assets/img/icon-apartment.png') }}" alt="Icon">
                            </div>
                            <h6>PAUD</h6>
                            <span>Program dasar untuk anak-anak usia dini dari kelas 1 sampai 6</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Category End -->
@endsection
