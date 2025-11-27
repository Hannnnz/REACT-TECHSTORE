<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to TechStore</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <link rel="stylesheet" href="<?= base_url();?>public/css/login.css">
    <link rel="stylesheet" href="<?= base_url();?>public/css/toast.css">
</head>
<body style="background-image: url('<?= base_url();?>public/resources/bg.jpg');">

    <div id="toast-container"></div>

    <audio id="notifSound" src="<?= base_url();?>public/resources/notif.mp3" preload="auto"></audio>
    <div class="welcome-container" id="welcome-container">
        <div class="welcome-content">
            
            <div class="logo-container anim-fade-in">
                <i class="fas fa-laptop-code"></i>
            </div>
            
            <h1 class="anim-slide-up" style="animation-delay: 0.2s;">Welcome to TechStore</h1>
            <p class="anim-slide-up" style="animation-delay: 0.4s;">
                Your one-stop shop for the latest and greatest in technology.
                <br>
                Join us and discover the future of tech retail.
            </p>
            <div class="welcome-buttons anim-slide-up" style="animation-delay: 0.6s;">
                <button class="btn primary" id="show-login-btn">Login</button>
            </div>

        </div>
    </div>
    <div class="modal-wrapper hidden" id="modal-wrapper">
        <div class="container" id="container">
            <span class="close-btn" id="close-btn">&times;</span>

            <div class="form-container sign-up-container">
                <?php flash_alert(); ?>
                <form id="regForm" method="POST" action="<?=site_url('auth/register');?>">
                    <?php csrf_field(); ?>
                    <h1>Create Account</h1>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input id="username" type="text" class="form-control " name="username" required placeholder="Username" />
                    </div>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input id="email" type="email" class="form-control" name="email" placeholder="Email" required/>
                    </div>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input id="password" type="password" class="form-control" name="password" placeholder="Enter password" required/>
                    </div>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" placeholder="Confirm password" required/>
                    </div>
                    <button class="btn" style="margin-top: 20px;">Register</button>
                </form>
            </div>

            <div class="form-container sign-in-container">
                <?php flash_alert() ;?>
                <form id="logForm" method="POST" action="<?=site_url('auth/login');?>">
                    <?php csrf_field(); ?>
                    <h1>Login</h1>
                    <div class="input-group">
                        <i class="fas fa-user-shield"></i>
                        <?php $LAVA =& lava_instance(); ?>
                        <input type="text" name="email" placeholder="Email" required autofocus/>
                    </div>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Password" required/>
                    </div>
                    <a href="<?=site_url('auth/password-reset');?>">Forgot your password?</a>
                    <button class="btn">Login</button>
                </form>
            </div>

            <div class="overlay-container">
                <div class="overlay">
                    <div class="overlay-panel overlay-left">
                        <h1>Hello, Techmate!</h1>
                        <p>Enter your personal details and start your journey with TechStore</p><hr>
                        <h5>OR</h5><br>
                        <button class="btn ghost" id="signIn">Login</button>
                    </div>
                    <div class="overlay-panel overlay-right">
                        <h1>Welcome Back!</h1>
                        <p>To keep connected with us, please login with your personal info</p><hr>
                        <h5>OR</h5><br>
                        <a href="<?= site_url('auth/register'); ?>" class="btn ghost">Register</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d970.2816607672548!2d121.17750456951032!3d13.40448420375496!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bce8c94a906141%3A0xb64b77e93c40b707!2sFAB&#39;S%20DIGIHUB%20COMPUTER%20TRADING!5e0!3m2!1sen!2sph!4v1763007960036!5m2!1sen!2sph" width="800" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

    <script src="<?= base_url();?>public/js/login.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
    <script>
        $(function() {
            var regForm = $("#regForm")
                if(regForm.length) {
                    regForm.validate({
                        rules: {
                            email: {
                                required: true,
                            },
                            password: {
                                required: true,
                                minlength: 8
                            },
                            password_confirmation: {
                                required: true,
                                minlength: 8
                            },
                            username: {
                                required: true,
                                minlength: 5
                            }
                        },
                        messages: {
                            email: {
                                required: "Please input your email address.",                            
                            },
                            password: {
                                required: "Please input your password",
                                minlength: jQuery.validator.format("Password must be atleast {0} characters.")
                            },
                            password_confirmation: {
                                required: "Please input your password",
                                minlength: jQuery.validator.format("Password must be atleast {0} characters.")
                            },
                            username: {
                                required: "Please input your username.",                            
                            }
                        },
                    })
                }
        })
    </script>
    

    <script src="<?= base_url();?>public/js/toast.js"></script>
    <?php toast_alert(); ?>

</body>
</html>