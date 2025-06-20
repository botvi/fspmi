<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="{{ asset('env') }}/logo.png" type="image/png" />
    <!--plugins-->
    <link href="{{ asset('admin') }}/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="{{ asset('admin') }}/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="{{ asset('admin') }}/assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <!-- loader-->
    <link href="{{ asset('admin') }}/assets/css/pace.min.css" rel="stylesheet" />
    <script src="{{ asset('admin') }}/assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('admin') }}/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('admin') }}/assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{ asset('admin') }}/assets/css/app.css" rel="stylesheet">
    <link href="{{ asset('admin') }}/assets/css/icons.css" rel="stylesheet">
    <title>Login - FSPMI</title>
    <style>
        

        .section-authentication-signin {
            min-height: 100vh;
        }

        img.img-fluid {
            max-height: 300px;
        }
    </style>

</head>

<body class="bg-login">
    <!--wrapper-->
    <div class="wrapper">

        <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container">
                <div class="row align-items-center">
                    <!-- Logo -->
                    <div class="col-lg-5 text-center mb-4 mb-lg-0">
                        <img src="{{ asset('env') }}/logo_text.png" width="250" alt="Logo FSPMI" class="img-fluid">
                        <p class="mt-3">
                            FSPMI (Federasi Serikat Pekerja Metal Indonesia) adalah federasi serikat pekerja yang
                            berfokus pada isu-isu buruh, terutama di sektor metal dan manufaktur. FSPMI merupakan bagian
                            dari organisasi serikat pekerja yang tersebar di seluruh Indonesia dan merupakan salah satu
                            serikat pekerja terbesar.
                        </p>
                       
                    </div>

                    <!-- Form -->
                    <div class="col-lg-7">
                        <div class="card">
                            <div class="card-body">
                                <div class="border p-4 rounded">
                                    <div class="text-center">
                                        <img src="{{ asset('env') }}/logo_text.png" width="200" alt="Logo FSPMI"
                                            class="img-fluid">
                                    </div>
                                    <div class="login-separater text-center mb-4">
                                        <span>MASUK MENGGUNAKAN USERNAME DAN PASSWORD</span>
                                        <hr />
                                    </div>
                                    <div class="form-body">
                                        <form class="row g-3" action="{{ route('login') }}" method="POST">
                                            @csrf
                                            <div class="col-12">
                                                <label for="username" class="form-label">Username</label>
                                                <input type="text" class="form-control" name="username"
                                                    id="username" placeholder="Username" required>
                                            </div>
                                            <div class="col-12">
                                                <div class="input-group" id="show_hide_password">
                                                    <input type="password" class="form-control border-end-0"
                                                        id="password" name="password" placeholder="Enter Password"> <a
                                                        href="javascript:;" class="input-group-text bg-transparent"><i
                                                            class='bx bx-hide'></i></a>
                                                </div>
                                            </div>

                                            {{-- <!-- reCAPTCHA -->
                                            {!! NoCaptcha::display() !!}
                                            @if ($errors->has('g-recaptcha-response'))
                                                <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
                                            @endif
                                         --}}
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary"><i
                                                            class="bx bxs-lock-open"></i> Sign in</button>
                                                </div>
                                            </div>
                                        </form>
                                        {{-- {!! NoCaptcha::renderJs() !!} --}}

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Form -->

                </div>
            </div>
        </div>
    </div>

    <!--end wrapper-->
    <!-- Bootstrap JS -->
    @include('sweetalert::alert')

    <script src="{{ asset('admin') }}/assets/js/bootstrap.bundle.min.js"></script>
    <!--plugins-->
    <script src="{{ asset('admin') }}/assets/js/jquery.min.js"></script>
    <script src="{{ asset('admin') }}/assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="{{ asset('admin') }}/assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="{{ asset('admin') }}/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <!--Password show & hide js -->
    <script>
        $(document).ready(function() {
            $("#show_hide_password a").on('click', function(event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bx-hide");
                    $('#show_hide_password i').removeClass("bx-show");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bx-hide");
                    $('#show_hide_password i').addClass("bx-show");
                }
            });
        });
    </script>
    <!--app JS-->
    <script src="{{ asset('admin') }}/assets/js/app.js"></script>
</body>

</html>
