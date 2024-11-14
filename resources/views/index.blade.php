@extends('layouts.app')

@section('content')
    <!-- Header Start -->
    <div class="container-fluid header bg-white p-0">
        <div class="row g-0 align-items-center flex-column-reverse flex-md-row">
            <div class="col-md-6 p-5 mt-lg-5">
                <h1 class="display-5 animated fadeIn mb-4">Belajar <span class="text-primary">Mengaji</span> Bersama
                    MQ-Alamin untuk masa depan anak Qurani</h1>
                <p class="animated fadeIn mb-4 pb-2">
                    MQ-Alamin adalah sekolah yang berdedikasi untuk mempersiapkan anak-anak untuk menjadi pemimpin
                    masa depan yang berkarakter dan berakhlak mulia.
                </p>
                <a href="/daftar" class="btn btn-primary py-3 px-5 me-3 animated fadeIn">Daftar Sekarang</a>
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


    <!-- Category Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
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
                <div class="col-lg-4 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
                    <a class="cat-item d-block bg-light text-center rounded p-3" href="">
                        <div class="rounded p-4">
                            <div class="icon mb-3">
                                <img class="img-fluid" src="{{ asset('assets/img/icon-villa.png') }}" alt="Icon">
                            </div>
                            <h6>SHIFIR</h6>
                            <span>Program lanjutan anak-anak usia dini untuk kelas 7</span>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6 wow fadeInUp" data-wow-delay="0.5s">
                    <a class="cat-item d-block bg-light text-center rounded p-3" href="">
                        <div class="rounded p-4">
                            <div class="icon mb-3">
                                <img class="img-fluid" src="{{ asset('assets/img/icon-house.png') }}" alt="Icon">
                            </div>
                            <h6>MDTA</h6>
                            <span>Program lanjutan anak-anak usia dini untuk kelas 8 - 10</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Category End -->


    <!-- About Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                    <div class="about-img position-relative overflow-hidden p-5 pe-0">
                        <img class="img-fluid w-100" src="{{ asset('assets/img/about.jpg') }}">
                    </div>
                </div>
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                    <h1 class="mb-4">Mengapa MQ-Alamin?</h1>
                    <p class="mb-4">
                        MQ-Alamin adalah pilihan terbaik untuk pendidikan anak-anak Anda karena kami berkomitmen untuk
                        membentuk pemimpin masa depan yang berkarakter, berakhlak mulia, dan siap menghadapi tantangan
                        global. Dengan memilih MQ-Alamin, Anda memastikan bahwa anak-anak Anda mendapatkan pendidikan
                        dengan:
                    </p>
                    <p><i class="fa fa-check text-primary me-3"></i>Kurikulum holistik yang mencakup aspek akademis dan
                        karakter</p>
                    <p><i class="fa fa-check text-primary me-3"></i>Lingkungan belajar yang mendukung dan inspiratif</p>
                    <p><i class="fa fa-check text-primary me-3"></i>Tenaga pengajar profesional dan berpengalaman</p>
                    <a class="btn btn-primary py-3 px-5 mt-3" href="/about">Read More</a>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->


    <!-- Property List Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-0 gx-5 align-items-end">
                <div class="col-lg-6">
                    <div class="text-start mx-auto mb-5 wow slideInLeft" data-wow-delay="0.1s">
                        <h1 class="mb-3">Galeri Kegiatan</h1>
                        <p>
                            Galeri kegiatan MQ-Alamin, mulai dari kegiatan rutin hingga kegiatan khusus.
                        </p>
                    </div>
                </div>
                <div class="col-lg-6 text-start text-lg-end wow slideInRight" data-wow-delay="0.1s">
                    <ul class="nav nav-pills d-inline-flex justify-content-end mb-5">
                        @foreach ($galery_types as $gtype)
                            <li class="nav-item me-2">
                                <a class="btn btn-outline-primary {{ $gtype == 'kegiatan' ? 'active' : '' }}"
                                    data-bs-toggle="pill" href="#tab-{{ $gtype }}">{{ strtoupper($gtype) }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="tab-content">
                @foreach ($galery_types as $type)
                    <div id="tab-{{ $type }}"
                        class="tab-pane fade show p-0 {{ $type == 'kegiatan' ? 'active' : '' }}">
                        @foreach ($galleries as $gallery)
                            <div class="row g-4">
                                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                                    <div class="property-item rounded overflow-hidden">
                                        <div class="position-relative overflow-hidden">
                                            <a href=""><img class="img-fluid"
                                                    src="{{ asset('storage/' . $gallery->image) }}" alt=""></a>
                                            <div
                                                class="bg-primary rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">
                                                {{ strtoupper($gallery->type) }}
                                            </div>
                                            <div
                                                class="bg-white rounded-top text-primary position-absolute start-0 bottom-0 mx-4 pt-1 px-3">
                                            </div>
                                        </div>
                                        <div class="p-4 pb-0">
                                            <a class="d-block h5 mb-2" href="">{{ $gallery->title }}</a>
                                            <p>
                                                {{ $gallery->description }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 text-center wow fadeInUp" data-wow-delay="0.1s">
                                    <a class="btn btn-primary py-3 px-5" href="#">Lihat Selengkapnya</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Property List End -->


    <!-- Call to Action Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="bg-light rounded p-3">
                <div class="bg-white rounded p-4" style="border: 1px dashed rgba(0, 185, 142, .3)">
                    <div class="row g-5 align-items-center">
                        <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                            <img class="img-fluid rounded w-100" src="{{ asset('assets/img/call-to-action.jpg') }}"
                                alt="">
                        </div>
                        <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                            <div class="mb-4">
                                <h1 class="mb-3">Hubungi Kami</h1>
                                <p>
                                    Hubungi kami untuk informasi lebih lanjut tentang program dan layanan kami
                                </p>
                            </div>
                            <a href="tel:{{ $telepon }}" class="btn btn-primary py-3 px-4 me-2">
                                <i class="fa fa-phone-alt me-2"></i>Hubungi Kami
                            </a>
                            <a href="https://wa.me/{{ $telepon ?? '-' }}?text=Kapan saya bisa ke MQ-Alamin Ustadz?"
                                target="_blank" class="btn btn-dark py-3 px-4"><i
                                    class="fa fa-calendar-alt me-2"></i>Buat
                                Janji</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Call to Action End -->


    <!-- Team Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h1 class="mb-3">Para Pengajar</h1>
                <p>
                    Para pengajar yang berkomitmen untuk membantu siswa mencapai potensi maksimal mereka
                </p>
            </div>
            <div class="row g-4">
                @foreach ($guru as $team)
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="team-item rounded overflow-hidden">
                            <div class="position-relative">
                                <img class="img-fluid"
                                    src="{{ asset($team->foto ? 'storage/' . $team->foto : 'assets/img/team-2.jpg') }}"
                                    alt="">
                                <div class="position-absolute start-50 top-100 translate-middle d-flex align-items-center">
                                    <a class="btn btn-square mx-1"
                                        href="https://wa.me/{{ $team->whatsapp }}?text=Assalamualaikum%20Ust%20{{ $team->user->name }}"
                                        target="_blank">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="text-center p-4 mt-3">
                                <h5 class="fw-bold mb-0">{{ $team->user->name }}</h5>
                                <small>{{ $team->alamat }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Team End -->


    <!-- Testimonial Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h1 class="mb-3">Kata Mereka</h1>
                <p>
                    Kata mereka tentang MQ-Alamin, para alumni dan wali murid.
                </p>
            </div>
            <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.1s">
                <div class="testimonial-item bg-light rounded p-3">
                    <div class="bg-white border rounded p-4">
                        <p>
                            MQ-Alamin adalah sekolah yang sangat baik, pengajar sangat kompeten dan sangat membantu dalam
                            pembelajaran.
                        </p>
                        <div class="d-flex align-items-center">
                            <img class="img-fluid flex-shrink-0 rounded"
                                src="{{ asset('assets/img/testimonial-1.jpg') }}" style="width: 45px; height: 45px;">
                            <div class="ps-3">
                                <h6 class="fw-bold mb-1">
                                    Nama Alumni
                                </h6>
                                <small>
                                    Profesi
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-item bg-light rounded p-3">
                    <div class="bg-white border rounded p-4">
                        <p>
                            MQ-Alamin adalah sekolah yang sangat baik, pengajar sangat kompeten dan sangat membantu dalam
                            pembelajaran.
                        </p>
                        <div class="d-flex align-items-center">
                            <img class="img-fluid flex-shrink-0 rounded"
                                src="{{ asset('assets/img/testimonial-2.jpg') }}" style="width: 45px; height: 45px;">
                            <div class="ps-3">
                                <h6 class="fw-bold mb-1">
                                    Nama Alumni
                                </h6>
                                <small>
                                    Profesi
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-item bg-light rounded p-3">
                    <div class="bg-white border rounded p-4">
                        <p>
                            MQ-Alamin adalah sekolah yang sangat baik, pengajar sangat kompeten dan sangat membantu dalam
                            pembelajaran.
                        </p>
                        <div class="d-flex align-items-center">
                            <img class="img-fluid flex-shrink-0 rounded"
                                src="{{ asset('assets/img/testimonial-3.jpg') }}" style="width: 45px; height: 45px;">
                            <div class="ps-3">
                                <h6 class="fw-bold mb-1">
                                    Nama Alumni
                                </h6>
                                <small>
                                    Profesi
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Testimonial End -->
@endsection
