<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TechStore Registration</title>
    <link rel="icon" type="image/x-icon" href="<?= base_url();?>public/resources/logolight.jpg">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Styles -->
    <link href="<?=base_url();?>public/css/main.css" rel="stylesheet">
    <link href="<?=base_url();?>public/css/reset.css" rel="stylesheet">
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body style="background-image: url('<?= base_url();?>public/resources/bg.jpg');">
    <div class="forgot-container">
        <span class="valid-feedback" role="alert">
            <strong>Note: Password must be at least 8 characters and contains one of this special characters (!@£$%^&*-_+=?), number, uppercase and lowercase letters.</strong>
        </span>   
        <?php flash_alert() ;?>
        <form id="regForm" method="POST" action="<?=site_url('auth/register');?>" autocomplete="off">
            <?php csrf_field(); ?>
            <h1>Register</h1>
            <p class="instructions">Enter your personal details and start your journey with TechStore.</p>
            <input id="username" type="text" class="form-control " name="username" required placeholder="Username" autocomplete="off"><br>
            <input id="email" type="email" class="form-control" name="email" required placeholder="Email" autocomplete="off"><br>
            <input id="password" type="password" class="form-control " name="password" required placeholder="New Password" autocomplete="off"><br>
            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required placeholder="Confirm Password" autocomplete="off">

            <p class="message hidden" id="message"></p>
            
            <button type="submit" class="btn">Register</button>
        </form>
    </div>
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
</body>
</html>
