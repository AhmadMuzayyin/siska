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

    <!-- Contact Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h1 class="mb-3">Hubungi Kami</h1>
                <p>Silahkan hubungi kami melalui kontak yang tersedia atau datang langsung ke lokasi kami. Kami siap
                    melayani dan menjawab pertanyaan Anda seputar program pendidikan Al-Quran di MQ Al-Amin.</p>
            </div>
            <div class="row g-4">
                <div class="col-12">
                    <div class="row gy-4">
                        <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.1s">
                            <div class="bg-light rounded p-3">
                                <div class="d-flex align-items-center bg-white rounded p-3"
                                    style="border: 1px dashed rgba(0, 185, 142, .3)">
                                    <div class="icon me-3" style="width: 45px; height: 45px;">
                                        <i class="fa fa-map-marker-alt text-primary"></i>
                                    </div>
                                    <span>{{ $alamat ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.3s">
                            <div class="bg-light rounded p-3">
                                <div class="d-flex align-items-center bg-white rounded p-3"
                                    style="border: 1px dashed rgba(0, 185, 142, .3)">
                                    <div class="icon me-3" style="width: 45px; height: 45px;">
                                        <i class="fa fa-envelope-open text-primary"></i>
                                    </div>
                                    <span>{{ $email ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.5s">
                            <div class="bg-light rounded p-3">
                                <div class="d-flex align-items-center bg-white rounded p-3"
                                    style="border: 1px dashed rgba(0, 185, 142, .3)">
                                    <div class="icon me-3" style="width: 45px; height: 45px;">
                                        <i class="fa fa-phone-alt text-primary"></i>
                                    </div>
                                    <span>{{ $telepon ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <iframe class="position-relative rounded w-100 h-100"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63215.08041807192!2d114.30915379002053!3d-8.00486435668377!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd13915ef9088b9%3A0xc58e55b3596f87d4!2sLPQ%20Madrasatul%20Qur&#39;An%20Al-%20Amin%20%22Metode%20Tilawati%22!5e0!3m2!1sen!2sid!4v1731544303392!5m2!1sen!2sid"
                        style="min-height: 400px; border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade" aria-hidden="false" tabindex="0"></iframe>
                </div>
                <div class="col-md-6">
                    <div class="wow fadeInUp" data-wow-delay="0.5s">
                        <form id="form-kontak">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Nama Lengkap">
                                        <label for="name">Nama Lengkap</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="email" placeholder="Email Aktif">
                                        <label for="email">Email Aktif</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="subject" placeholder="Subjek">
                                        <label for="subject">Subjek</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Tulis pesan disini" id="message" style="height: 150px"></textarea>
                                        <label for="message">Pesan</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary w-100 py-3" type="submit">Kirim Pesan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#form-kontak').submit(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Apakah anda yakin ingin mengirim pesan?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    confirmButtonColor: '#0EBD94',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var name = $('#name').val();
                        var email = $('#email').val();
                        var subject = $('#subject').val();
                        var message = $('#message').val();
                        var _token = "{{ csrf_token() }}";

                        $.ajax({
                            url: "{{ route('kontak.store') }}",
                            type: "POST",
                            data: {
                                name,
                                email,
                                subject,
                                message,
                                _token
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Pesan berhasil dikirim',
                                    icon: 'success',
                                    confirmButtonColor: '#0EBD94'
                                });
                            },
                            error: function(xhr, status, error) {
                                // tampilkan pesan error pada setiap input
                                var errors = xhr.responseJSON.errors;
                                $('#name').addClass('is-invalid');
                                $('#email').addClass('is-invalid');
                                $('#subject').addClass('is-invalid');
                                $('#message').addClass('is-invalid');
                                $('#name').after('<div class="invalid-feedback">' +
                                    errors.name + '</div>');
                                $('#email').after('<div class="invalid-feedback">' +
                                    errors.email + '</div>');
                                $('#subject').after('<div class="invalid-feedback">' +
                                    errors.subject + '</div>');
                                $('#message').after('<div class="invalid-feedback">' +
                                    errors.message + '</div>');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
