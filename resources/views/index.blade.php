<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ trans('panel.site_title') }} - TERAJU</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <style>
        .swiper {
            width: 100%;
            padding-top: 30px;
            padding-bottom: 50px;
        }

        .swiper-slide {
            display: flex;
            justify-content: center;
        }

        .card {
            width: 18rem;
        }

        .banner {
            background-image: url('img/teraju-header-page.png');
            background-size: cover;
            height: 300px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .banner h1 {
            padding: 20px;
            border-radius: 10px;
        }

        .btn-default {
            color: #fff;
            background-color: #f4b2b9;
            border-color: #f4b2b9;
        }

        .btn-default:hover {
            color: #fff;
            background-color: rgb(248, 120, 133);
            border-color: rgb(248, 120, 133);
        }

        .card-img-top {
            height: 300px;

        }
    </style>
</head>

<body>

    <!-- Banner Section -->
    <div class="banner bg-teraju position-relative">
        <div class="position-absolute top-0 end-0 m-3 d-flex gap-2">
            <a href="{{ route('login') }}" class="btn" style="font-weight: bold;color:#fff">Login</a> |
            <a href="{{ route('register') }}" class="btn" style="font-weight: bold;color:#fff">Register</a>
        </div>
        <h1 style="text-transform: uppercase">Meet Our Team & Book Your Appointment</h1>
    </div>

    <div class="container py-5">
        <h2 class="mb-4 text-center">Our Team</h2>

        <div class="swiper bsb-team-pro-2 swiper-initialized swiper-horizontal swiper-backface-hidden">
            <div class="swiper-wrapper">

                @foreach($teamMembers as $member)
                <div class="swiper-slide">
                    <div class="card text-center">
                        <img src="{{ $member->photo ? $member->photo->url : asset('images/default.png') }}" class="card-img-top" alt="{{ $member->name }}">
                        <div class="card-body">
                            <h5 class="card-title" style="text-transform: uppercase">{{ $member->name }}</h5>
                            <p class="card-text">{{ $member->department->name }}</p>
                            <h6 class="mt-3">Schedule with me</h6>
                            <a href="{{ route('register', [
                                    'redirect' => route('admin.appointments.create', [
                                        'department_id' => $member->department->id,
                                        'employee_id' => $member->id
                                    ])
                                ]) }}" class="btn btn-default">
                                Book Appointment
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>

            <!-- Swiper navigation buttons -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>

            <!-- Swiper pagination -->
            <div class="swiper-pagination"></div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                576: {
                    slidesPerView: 1
                },
                768: {
                    slidesPerView: 2
                },
                992: {
                    slidesPerView: 3
                },
                1200: {
                    slidesPerView: 4
                },
            }
        });
    </script>
</body>

</html>