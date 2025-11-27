<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TechStore Reset Password</title>
    <link rel="icon" type="image/x-icon" href="<?= base_url();?>public/resources/logolight.jpg">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
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
        <form id="forgot-form" action="<?=site_url('auth/set-new-password');?>" method="post" autocomplete="off">
            <?php csrf_field(); ?>
            <h1>Change Password</h1>
            <p class="instructions">Please fill and match the following to reset your password.</p>
            <input type="hidden" name="token" value="<?php !empty($_GET['token']) && print $_GET['token'];?>"> 
                <input id="password" type="password" class="form-control " name="password" required placeholder="New Password" autocomplete="off"><br>
                <input id="re_password" type="password" class="form-control " name="re_password" required placeholder="Confirm Password" autocomplete="off">

            <p class="message hidden" id="message"></p>
            
            <button type="submit" class="btn">Change Password</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
    <script>
        $(function() {
            var myForm = $("#forgot-form")
                if(myForm.length) {
                    myForm.validate({
                        rules: {
                            password: {
                                required: true,
                                minlength: 8
                            },
                            re_password: {
                                required: true,
                                minlength: 8
                            }
                        },
                        messages: {
                            password: {
                                required: "Please input your password",
                                minlength: jQuery.validator.format("Password must be atleast {0} characters.")
                            },
                            re_password: {
                                required: "Please input your password",
                                minlength: jQuery.validator.format("Password must be atleast {0} characters.")
                            }
                        },
                    })
                }
        })
    </script>
</body>
</html>
