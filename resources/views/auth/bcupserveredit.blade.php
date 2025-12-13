
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="https://helpdesk.kemakmuran.com/assets/images/logoHead.png">
    <!-- APP CSS-->
    <link rel="stylesheet" type="text/css" href="https://helpdesk.kemakmuran.com/assets/css/app.min.css"/>
    <!-- BOOTSTRAP CSS -->
    <link rel="stylesheet" type="text/css" href="https://helpdesk.kemakmuran.com/assets/css/bootstrap.min.css"/>
    <!-- ICONS CSS -->
    <link rel="stylesheet" type="text/css" href="https://helpdesk.kemakmuran.com/assets/css/icons.min.css"/>
    <!-- PRELOADER CSS -->
    <link rel="stylesheet" type="text/css" href="https://helpdesk.kemakmuran.com/assets/css/preloader.min.css"/>

    <style>
        .chat-bubble {
            display: none;
            position: fixed;
            bottom: 80px;
            right: 20px;
            max-width: 300px;
            background: #f1f1f1;
            padding: 15px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            font-size: 13px;
            animation: fadeInUp 0.3s ease-in-out;
            z-index: 9999;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .btn-info-password {
            background: #3b3bb3;
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.2);
            width: 100%;
            margin-bottom: 8px; /* rapetin ke footer */
        }

        .btn-info-password:hover {
            background: #2d2da0;
        }

        .btn-close {
            margin-top: 15px;
            background: #e74c3c;
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            line-height: 1.2;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s ease-in-out;
        }

        .btn-close:hover {
            background: #c0392b;
        }

        .footer-text {
            margin-top: 5px !important; /* rapetin jarak ke tombol */
            font-size: 13px;
            color: #555;
        }
    </style>
</head>

<body>
    <!-- Loading -->
    <!-- LOADING CSS-->
<link rel="stylesheet" type="text/css" href="https://helpdesk.kemakmuran.com/assets/css/dot-loading.css"/>
<!-- Loading -->
<div class="process-container hidden" id="processing">
    <div class="row">
        <div class="col-12 text-center d-flex justify-content-center align-items-center"><div class="dotLoading"></div></div>
        <div class="col-12 text-center d-flex justify-content-center align-items-center mt-4"><h4 class="text-white textLoading">Please Wait</h4></div>
    </div>
</div>    
    <div class="auth-page">
        <div class="container-fluid p-0">
            <div class="row g-0">
                <!-- Form Login -->
                <div class="col-xxl-3 col-lg-4 col-md-5">
                    <div class="auth-full-page-content d-flex p-sm-5 p-4">
                        <div class="w-100">
                            <div class="d-flex flex-column h-100">
                                <div class="mb-4 mb-md-5 text-center">
                                    <img src="https://helpdesk.kemakmuran.com/assets/images/logo.png" alt="" height="50">
                                </div>
                                <div class="auth-content my-auto">
                                    <div class="text-center">
                                        <h5 class="mb-0">Welcome Back !</h5>
                                        <p class="text-muted mt-2">Sign in to continue</p>
                                    </div>
                                    <div class="text-left">
                                        <!--validasi form with $validate-->
                                    </div>

                                    <form class="formLoad" action="https://helpdesk.kemakmuran.com/auth/login" id="login" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="_token" value="xdivd8Mw2fzOYSnhu4bo7XvUtTaItZrMkCOGYTbh" autocomplete="off">                                        <div class="mb-3">
                                            <label class="form-label">Email / Username</label>
                                            <input type="text" class="form-control" name="email" id="username" placeholder="Enter email / username">
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex align-items-start">
                                                <div class="flex-grow-1">
                                                    <label class="form-label">Password</label>
                                                </div>
                                            </div>
                                            <div class="input-group auth-pass-inputgroup">
                                                <input type="password" class="form-control" placeholder="Enter password" aria-label="Password" aria-describedby="password-addon" name="password">
                                                <button class="btn btn-light shadow-none ms-0" type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="remember-check">
                                                    <label class="form-check-label" for="remember-check">
                                                        Remember me
                                                    </label>
                                                </div>  
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <button class="btn btn-primary w-100 waves-effect waves-light" type="submit" name="sb">Log In</button>
                                        </div>
                                        <div class="mb-3">
                                            <a href="https://faq.kemakmuran.com" class="btn btn-outline-secondary w-100 waves-effect">FAQ</a>
                                        </div>
                                        <div class="mb-2">
                                            <button id="btnInfo" type="button" class="btn-info-password">Ketentuan Password</button>
                                        </div>
                                    </form>

                                    <!-- Chat Bubble -->
                                    <div id="chatBubble" class="chat-bubble">
                                        <p>
                                            <strong>Informasi Penggantian Password di Helpdesk MSK</strong><br>
                                            • Panjang password minimal 8 karakter<br>
                                            • Kombinasi huruf besar, huruf kecil, angka, dan karakter khusus<br>
                                            • Hindari informasi pribadi<br>
                                            • Sistem akan memperingati perubahan password setiap 42 hari<br>
                                            • Tidak menggunakan password yang sama<br><br>
                                            <em>Contoh password yang kuat:</em> <strong>Hujan2024@@</strong>
                                        </p>
                                        <button id="btnClose" class="btn-close">Tutup</button>
                                    </div>

                                </div>
                                <div class="text-center footer-text">
                                    <p class="mb-0">
                                        © Dashboard Helpdesk PT Mitra Sendang Kemakmuran Banten 2025
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Background Login -->
                <div class="col-xxl-9 col-lg-8 col-md-7">
                    <div class="auth-bg pt-md-5 p-4 d-flex" style="background-image: url('https://helpdesk.kemakmuran.com/assets/images/background/MSK.png');">
                        <div class="bg-overlay bg-primary"></div>
                        <ul class="bg-bubbles">
                            <li></li><li></li><li></li><li></li><li></li>
                            <li></li><li></li><li></li><li></li><li></li>
                        </ul>
                        <!-- end bubble effect -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="https://helpdesk.kemakmuran.com/assets/libs/jquery/jquery.min.js"></script>
    <script src="https://helpdesk.kemakmuran.com/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://helpdesk.kemakmuran.com/assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="https://helpdesk.kemakmuran.com/assets/libs/simplebar/simplebar.min.js"></script>
    <script src="https://helpdesk.kemakmuran.com/assets/libs/node-waves/waves.min.js"></script>
    <script src="https://helpdesk.kemakmuran.com/assets/libs/feather-icons/feather.min.js"></script>
    <!-- FORM LOAD JS -->
    <script src="https://helpdesk.kemakmuran.com/assets/js/formLoad.js"></script>
    <!-- PW ADDON INIT -->
    <script src="https://helpdesk.kemakmuran.com/assets/js/pages/pass-addon.init.js"></script>

    <script>
        const btnInfo = document.getElementById("btnInfo");
        const btnClose = document.getElementById("btnClose");
        const chatBubble = document.getElementById("chatBubble");

        btnInfo.addEventListener("click", () => {
            chatBubble.style.display = "block";
        });

        btnClose.addEventListener("click", () => {
            chatBubble.style.display = "none";
        });
    </script>
</body>
</html>
