@extends('layouts.user.app')

@section('title')
    Dashboard
@endsection

@section('content')
    <section id="hero">

        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-7 pt-5 pt-lg-0 order-2 order-lg-1 d-flex align-items-center">
                    <div data-aos="zoom-out">
                        <h1>Selamat Datang di Website Tryout <span>UKM BIMBEL</span> Polstat STIS</h1>
                        <h2> Membantu kamu mempersiapkan seleksi masuk Polstat STIS dan sekolah kedinasan lainnya dengan
                            maksimal dan efektif bersama mahasiswa Polstat STIS</h2>
                        <div class="text-center text-lg-start">
                            <a href="#pricing" class="btn-get-started scrollto">Lihat Paket</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 order-1 order-lg-2 hero-img" data-aos="zoom-out" data-aos-delay="300">
                    <img src="{{ asset('Bootslander') }}/assets/img/hero-img.png" class="img-fluid animated" alt="">
                </div>
            </div>
        </div>

        <svg class="hero-waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
            viewBox="0 24 150 28 " preserveAspectRatio="none">
            <defs>
                <path id="wave-path" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z">
            </defs>
            <g class="wave1">
                <use xlink:href="#wave-path" x="50" y="3" fill="rgba(255,255,255, .1)">
            </g>
            <g class="wave2">
                <use xlink:href="#wave-path" x="50" y="0" fill="rgba(255,255,255, .2)">
            </g>
            <g class="wave3">
                <use xlink:href="#wave-path" x="50" y="9" fill="#fff">
            </g>
        </svg>

    </section><!-- End Hero -->

    <main id="main">

        <!-- ======= About Section ======= -->
        <section id="about" class="about">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-xl-5 col-lg-6 video-box d-flex justify-content-center align-items-stretch"
                        data-aos="fade-right">
                    </div>

                    <div class="col-xl-7 col-lg-6 icon-boxes d-flex flex-column align-items-stretch justify-content-center py-5 px-lg-5"
                        data-aos="fade-left">
                        <h3>Kenapa harus tryout di UKM Bimbel</h3>
                        <p>Try out UKM Bimbel merupakan sarana dalam menunjang para peserta untuk masuk sekolah kedinasan
                            yang terdiri atas TOBAR dengan 3 gelombang dan TONAS. Selain mendapat gambaran soal, Try Out UKM
                            Bimbel juga menawarkan Bimbingan BIUS yang tentunya membantu teman-teman untuk mematangkan
                            persiapan seleksi masuk POLSTAT STIS dan sekolah kedinasan lain.</p>

                        <div class="icon-box" data-aos="zoom-in" data-aos-delay="100">
                            <div class="icon"><i class="bx bx-notepad"></i></div>
                            <h4 class="title"><a href="#">Soal Dibuat Langsung OLEH MAHASISWA POLSTAT STIS</a></h4>
                            <p class="description">Soal akurat karena dibuat langsung oleh mahasiswa Polstat STIS yang
                                berpengalaman mengerjakan soal asli SPMB Polstat STIS</p>
                        </div>

                        <div class="icon-box" data-aos="zoom-in" data-aos-delay="200">
                            <div class="icon"><i class="bx bx-desktop"></i></div>
                            <h4 class="title"><a href="">Pengerjaan Online Dengan Sistem CAT</a></h4>
                            <p class="description">Pengerjaan dilakukan secara daring dengan menggunakan sistem CAT
                                (Computer Assisted Test) sehingga menyerupai sistem ujian yang sebenarnya</p>
                        </div>

                        <div class="icon-box" data-aos="zoom-in" data-aos-delay="300">
                            <div class="icon"><i class="bx bx-money-withdraw"></i></div>
                            <h4 class="title"><a href="">Harga Terjangkau</a></h4>
                            <p class="description">Dengan harga terjangkau, kamu bisa mendapatkan Try Out dan bimbingan yang
                                berkualitas dan bermutu</p>
                        </div>

                    </div>
                </div>

            </div>
        </section><!-- End About Section -->

        <!-- ======= Counts Section ======= -->
        <section id="counts" class="counts">
            <div class="container">

                <div class="row" data-aos="fade-up">

                    <div class="col-lg-3 col-md-6">
                        <div class="count-box">
                            <i class="bi bi-journal-text"></i>
                            <span data-purecounter-start="0" data-purecounter-end="500" data-purecounter-duration="1"
                                class="purecounter"></span>
                            <p>Soal berkualitas yang disiapkan oleh Mahasiswa Polstat STIS</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mt-5 mt-md-0">
                        <div class="count-box">
                            <i class="bi bi-mortarboard-fill"></i>
                            <span data-purecounter-start="0" data-purecounter-end="36" data-purecounter-duration="1"
                                class="purecounter"></span>
                            <p>Pengajar yang merupakan Mahasiswa Polstat STIS</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mt-5 mt-lg-0">
                        <div class="count-box">
                            <i class="bi bi-people"></i>
                            <span data-purecounter-start="0" data-purecounter-end="10000" data-purecounter-duration="1"
                                class="purecounter"></span>
                            <p>Pejuang Kedinasan telah mempercayakan Try Out dan bimbingan masuk kedinasan bersama Kami</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mt-5 mt-lg-0">
                        <div class="count-box">
                            <i class="bi bi-award-fill"></i>
                            <span data-purecounter-start="0" data-purecounter-end="15" data-purecounter-duration="1"
                                class="purecounter"></span>
                            <p>tahun berpengalaman menyelenggarakan Try Out dan Bimbingan masuk Polstat STIS dan kedinasan
                                lainnya.</p>
                        </div>
                    </div>

                </div>

            </div>
        </section><!-- End Counts Section -->

        <!-- ======= Pricing Section ======= -->
        <section id="pricing" class="pricing">
            <div class="container">

                <div class="section-title" data-aos="fade-up">
                    <h2>Daftar</h2>
                    <p>Pilihan Paket Tryout</p>
                </div>

                <div class="row" data-aos="fade-left">
                    @if ($pakets->isEmpty())
                        <div class="alert alert-primary" role="alert">Paket Ujian belum tersedia</div>
                    @else
                        @foreach ($pakets as $paket)
                            <div class="col-lg-4 col-md-6 mt-4 mb-4 mt-lg-0">
                                <div class="box" data-aos="zoom-in" data-aos-delay="400">
                                    @if ($paket->id == '03dfc817-3ee3-404c-b162-e1a4acb8ff73')
                                        <span class="advanced">Terlaris</span>
                                    @endif
                                    <h3>{{ $paket->nama }}</h3>
                                    <h4><sup>Rp</sup>{{ number_format($paket->harga, 0, ',', '.') }}</h4>
                                    {!! $paket->deskripsi !!}
                                    <div class="btn-wrap">
                                        @if ($paket->pembelian->isEmpty() || !auth()->check())
                                            @if (Carbon\Carbon::now()->between($paket->waktu_mulai, $paket->waktu_akhir))
                                                <form method="post" action="{{ route('pembelian.store') }}">
                                                    @csrf
                                                    @method('post')
                                                    <input type="hidden" name="paket_id" value="{{ $paket->id }}">
                                                    <button type="submit" class="btn-buy">Beli Paket</button>
                                                @else
                                                    <button type="button"
                                                        style="background-color: grey; border-color: black"
                                                        class="btn-buy">Belum tersedia</button>
                                            @endif
                                        @else
                                            @if ($paket->pembelian[0]->paket_id == '0df8c9b0-d352-448b-9611-abadffc4f46d')
                                                <a href="https://drive.google.com/drive/folders/1quwqv6tAgGi8OvlAVzCyMI9T_xJXuJ9U?usp=sharing"
                                                    target="_blank" type="button"
                                                    style="width: 12rem; border: 2px solid; border-color: black"
                                                    class="btn-buy mb-2">Modul BIUS</a>
                                                <a href="https://chat.whatsapp.com/GQefjygQnl82v9OlXwpPbL" target="_blank"
                                                    type="button"
                                                    style="width: 10rem; border: 2px solid; border-color: black"
                                                    class="btn-buy mb-2">Grup WA</a>
                                            @elseif($paket->pembelian[0]->paket_id == 'd5f57505-fb5a-4f59-a301-3722ef581844')
                                                <a href="https://chat.whatsapp.com/CKsDXB9OJYZFWfxlYk5QPH" target="_blank"
                                                    type="button"
                                                    style="width: 10rem; border: 2px solid; border-color: black"
                                                    class="btn-buy mb-2">Grup WA</a>
                                            @else
                                                <a href="https://chat.whatsapp.com/BzxL0RHOfXd1QhukyYzXTz" target="_blank"
                                                    type="button"
                                                    style="width: 10rem; border: 2px solid; border-color: black"
                                                    class="btn-buy mb-2">Grup WA</a>
                                            @endif
                                            <a href="{{ route('tryout.index', $paket->id) }}" type="button"
                                                style="width: 10rem; background-color: grey; border: 2px solid; border-color: black"
                                                class="btn-buy">Lihat Paket</a>
                                        @endif
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

            </div>
        </section>
        <!-- End Pricing Section -->

        <!-- ======= Testimonials Section ======= -->
        <section id="testimonials" class="testimonials">
            <div class="container">

                <div class="testimonials-slider swiper" data-aos="fade-up" data-aos-delay="100">
                    <div class="swiper-wrapper">

                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <img src="{{ asset('img/fajar.jpg') }}" class="testimonial-img" alt="">
                                <h3>Fajar Fithra Ramadhan</h3>
                                <h4>Mahasiswa STIS Angkatan 64</h4>
                                <p>
                                    <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                                    Tryout SKD di TOBAR ini untuk komposisi sudah sesuai waktu ujian asli di BKN, Soal
                                    matematikanya sangat relevan dengan tahap 2 SPMB. Sehingga jadi terbiasa ngerjain soal
                                    aslinya akhirnya dapet nilai yang lumayan di tahap 2 kemarin.
                                    <i class="bx bxs-quote-alt-right quote-icon-right"></i>
                                </p>
                            </div>
                        </div><!-- End testimonial item -->

                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <img src="{{ asset('img/ihsan.jpg') }}" class="testimonial-img" alt="">
                                <h3>M. Ihsan Silmi Kaffah</h3>
                                <h4>Mahasiswa STIS Angkatan 64</h4>
                                <p>
                                    <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                                    Ketika tujuanmu itu terlalu tinggi, jangan kurangi tujuanmu, tapi tingkatkan usahamu!
                                    itulah yang aku tanamkan ketika ingin masuk STIS ini. Latihan-latihan soal penting
                                    banget buatku, sampai aku menemukan TONAS ini buat menguji kemampuan ku! Beberapa soal
                                    latihannya menurutku masih fresh gitu dan mirip dengan SPMB ku saat itu. Alhamdulillah,
                                    kini aku menjadi bagian dari Polstat STIS untuk meraih impianku, mau sepertiku?
                                    tingkatkan pemahamanmu melalui TONAS ini ya!
                                    <i class="bx bxs-quote-alt-right quote-icon-right"></i>
                                </p>
                            </div>
                        </div><!-- End testimonial item -->

                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <img src="{{ asset('img/acha.jpg') }}" class="testimonial-img" alt="">
                                <h3>Ananda Natasya</h3>
                                <h4>Mahasiswa STIS Angkatan 65</h4>
                                <p>
                                    <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                                    Tobar tahun 2023 keren banget, soal-soalnya semua menantang tapi masi bisa dikerjain dan
                                    lumayan yang sesuai dengan soal aslinya. semoga Tobar tahun 2024 bisa lebih keren lagi.
                                    <i class="bx bxs-quote-alt-right quote-icon-right"></i>
                                </p>
                            </div>
                        </div><!-- End testimonial item -->
                    </div>
                    <div class="swiper-pagination"></div>
                </div>

            </div>
        </section><!-- End Testimonials Section -->

        <!-- ======= F.A.Q Section ======= -->
        <section id="faq" class="faq section-bg">
            <div class="container">

                <div class="section-title" data-aos="fade-up">
                    <h2>F.A.Q</h2>
                    <p>Frequently Asked Questions</p>
                </div>

                <div class="faq-list">
                    <ul>
                        <li data-aos="fade-up">
                            <i class="bx bx-help-circle icon-help"></i> <a data-bs-toggle="collapse" class="collapse"
                                data-bs-target="#faq-list-1">Apakah bimbingan dan try out yang diadakan berafiliasi dengan
                                Polstat STIS? <i class="bx bx-chevron-down icon-show"></i><i
                                    class="bx bx-chevron-up icon-close"></i></a>
                            <div id="faq-list-1" class="collapse show" data-bs-parent=".faq-list">
                                <p>
                                    Seluruh bimbingan dan try out yang diadakan tidak berafiliasi dengan Politeknik
                                    Statistika STIS. Seluruh kegiatan bimbingan dan try out sepenuhnya merupakan tanggung
                                    jawab UKM Bimbel.
                                </p>
                            </div>
                        </li>

                        <li data-aos="fade-up" data-aos-delay="100">
                            <i class="bx bx-help-circle icon-help"></i> <a data-bs-toggle="collapse"
                                data-bs-target="#faq-list-2" class="collapsed">Apakah soal dan pembahasan try out akan
                                dibagi? <i class="bx bx-chevron-down icon-show"></i><i
                                    class="bx bx-chevron-up icon-close"></i></a>
                            <div id="faq-list-2" class="collapse" data-bs-parent=".faq-list">
                                <p>
                                    Ya, setiap peserta mendapatkan soal dan pembahasan try out yang diikuti.
                                </p>
                            </div>
                        </li>

                        <li data-aos="fade-up" data-aos-delay="200">
                            <i class="bx bx-help-circle icon-help"></i> <a data-bs-toggle="collapse"
                                data-bs-target="#faq-list-3" class="collapsed">[TOBAR] Jika sudah mendaftar batch 1,
                                apakah harus mendaftar batch selanjutnya juga? <i
                                    class="bx bx-chevron-down icon-show"></i><i
                                    class="bx bx-chevron-up icon-close"></i></a>
                            <div id="faq-list-3" class="collapse" data-bs-parent=".faq-list">
                                <p>
                                    Salah satu keuntungan ketika anda mendaftar TOBAR Batch 1 adalah sudah termasuk dengan
                                    Batch 2 dan Batch 3. Jadi, anda tidak perlu melakukan pendaftaran berulang pada Batch 2
                                    dan 3. Kemudian, apabila anda mendaftar tobar Batch 2, anda tidak perlu melakukan
                                    pendaftaran pada Batch 3.
                                </p>
                            </div>
                        </li>

                        <li data-aos="fade-up" data-aos-delay="300">
                            <i class="bx bx-help-circle icon-help"></i> <a data-bs-toggle="collapse"
                                data-bs-target="#faq-list-4" class="collapsed">[TOBAR] Bagaimana jika saya telat
                                mengerjakan tryout? <i class="bx bx-chevron-down icon-show"></i><i
                                    class="bx bx-chevron-up icon-close"></i></a>
                            <div id="faq-list-4" class="collapse" data-bs-parent=".faq-list">
                                <p>
                                    Waktu pelaksanaan TOBAR adalah selama 2 hari, sehingga anda dapat mengerjakannya secara
                                    fleksibel pada rentang waktu 2 hari tersebut.
                                </p>
                            </div>
                        </li>

                        <li data-aos="fade-up" data-aos-delay="400">
                            <i class="bx bx-help-circle icon-help"></i> <a data-bs-toggle="collapse"
                                data-bs-target="#faq-list-5" class="collapsed">[TONAS] Jika saya berhalangan untuk
                                mengerjakan try out pada tanggal tersebut, apakah saya dapat mengerjakannya atau mendapatkan
                                soal dan pembahasannya? <i class="bx bx-chevron-down icon-show"></i><i
                                    class="bx bx-chevron-up icon-close"></i></a>
                            <div id="faq-list-5" class="collapse" data-bs-parent=".faq-list">
                                <p>
                                    Ya, bagi peserta yang berhalangan untuk melaksanakan try out di tanggal pelaksanaan,
                                    dapat mengikuti try out susulan akan tetapi nilainya tidak dimasukkan pada perankingan
                                    nasional. Peserta juga berhak untuk mendapatkan soal dan pembahasan walau tidak
                                    mengerjakan try out.
                                </p>
                            </div>
                        </li>

                    </ul>
                </div>

            </div>
        </section><!-- End F.A.Q Section -->

        <!-- ======= Contact Section ======= -->
        <section id="contact" class="contact">
            <div class="container">

                <div class="section-title" data-aos="fade-up">
                    <h2>Contact</h2>
                    <p>Contact Us</p>
                </div>

                <div class="row">

                    <div class="col-lg-4" data-aos="fade-right" data-aos-delay="100">
                        <div class="info">
                            <div class="address">
                                <i class="bi bi-envelope"></i>
                                <h4>Email:</h4>
                                <p><a style="color: inherit;" href="mailto:ukmbimbel@stis.ac.id">ukmbimbel@stis.ac.id</a>
                                </p>
                            </div>

                            <div class="phone">
                                <i class="bi bi-phone"></i>
                                <h4>Contact Person 1:</h4>
                                <p><a style="color: inherit;" target="_blank"
                                        href="https://wa.me/6282163859215">082163859215 (Fira)</a></p>
                            </div>

                            <div class="phone">
                                <i class="bi bi-phone"></i>
                                <h4>Contact Person 2:</h4>
                                <p><a style="color: inherit;" target="_blank"
                                        href="https://wa.me/6285813626170">085813626170 (Nayya)</a></p>
                            </div>

                            <div class="phone">
                                <i class="bi bi-phone"></i>
                                <h4>Contact Person 3:</h4>
                                <p><a style="color: inherit;" target="_blank"
                                        href="https://wa.me/6282259524985">082259524985 (Rafli)</a></p>
                            </div>

                        </div>

                    </div>

                    <div class="col-lg-8 mt-5 mt-lg-0" data-aos="fade-left" data-aos-delay="200">

                        <form action="{{ route('sendEmail') }}" method="post" role="form" class="php-email-form">
                            @csrf
                            @method('post')
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <input type="text" name="name" class="form-control" id="name"
                                        placeholder="Nama Lengkap" required>
                                </div>
                                <div class="col-md-6 form-group mt-3 mt-md-0">
                                    <input type="email" class="form-control" name="email" id="email"
                                        placeholder="Email" required>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <input type="text" class="form-control" name="subject" id="subject"
                                    placeholder="Judul" required>
                            </div>
                            <div class="form-group mt-3">
                                <textarea class="form-control" name="message" rows="5" placeholder="Pesan" required></textarea>
                            </div>
                            <div class="my-3">
                                <div class="loading">Menunggu</div>
                                <div class="error-message"></div>
                                <div class="sent-message">Pesanmu telah terkirim, terimakasih!</div>
                            </div>
                            <div class="text-center"><button type="submit">Kirim Pesan</button></div>
                        </form>

                    </div>

                </div>

            </div>
        </section><!-- End Contact Section -->

    </main><!-- End #main -->
@endsection

@push('scripts')
    <script>
        (function() {
            "use strict";

            let forms = document.querySelectorAll(".php-email-form");

            forms.forEach(function(e) {
                e.addEventListener("submit", function(event) {
                    event.preventDefault();

                    let thisForm = this;

                    let action = thisForm.getAttribute("action");
                    let recaptcha = thisForm.getAttribute("data-recaptcha-site-key");

                    if (!action) {
                        displayError(thisForm, "The form action property is not set!");
                        return;
                    }
                    thisForm.querySelector(".loading").classList.add("d-block");
                    thisForm.querySelector(".error-message").classList.remove("d-block");
                    thisForm.querySelector(".sent-message").classList.remove("d-block");

                    let formData = new FormData(thisForm);

                    if (recaptcha) {
                        if (typeof grecaptcha !== "undefined") {
                            grecaptcha.ready(function() {
                                try {
                                    grecaptcha.execute(recaptcha, {
                                        action: "php_email_form_submit"
                                    }).then((token) => {
                                        formData.set("recaptcha-response", token);
                                        php_email_form_submit(thisForm, action,
                                            formData);
                                    });
                                } catch (error) {
                                    displayError(thisForm, error);
                                }
                            });
                        } else {
                            displayError(thisForm, "The reCaptcha javascript API url is not loaded!");
                        }
                    } else {
                        php_email_form_submit(thisForm, action, formData);
                    }
                });
            });

            function php_email_form_submit(thisForm, action, formData) {
                fetch(action, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    },
                }).then((response) => {
                    thisForm.querySelector(".loading").classList.remove("d-block");
                    thisForm.querySelector(".sent-message").classList.add("d-block");
                    thisForm.reset();
                });
            }

            function displayError(thisForm, error) {
                thisForm.querySelector(".loading").classList.remove("d-block");
                thisForm.querySelector(".error-message").innerHTML = error;
                thisForm.querySelector(".error-message").classList.add("d-block");
            }
        })();
    </script>
@endpush
