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
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Styles -->
    <link href="<?=base_url();?>public/css/main.css" rel="stylesheet">
    <link href="<?=base_url();?>public/css/reset.css" rel="stylesheet">
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body style="background-image: url('<?= base_url();?>public/resources/bg.jpg');">
    <div class="forgot-container">
        <form id="forgot-form" method="POST" action="<?=site_url('auth/password-reset');?>" autocomplete="off">
            <?php csrf_field(); ?>
            <h1>Forgot Password</h1>
            <p class="instructions">Enter your email address and we'll send you a link to reset your password.</p>
            
                <?php $LAVA =& lava_instance(); ?>
                <input id="email" type="email" class="form-control <?=$LAVA->session->flashdata('alert');?>" name="email" placeholder="Email Address" required autocomplete="off"/>
                <span class="invalid-feedback" role="alert">
                    <strong>We can&#039;t find a user with that email address.</strong>
                </span>
                <span class="valid-feedback" role="alert">
                    <strong>Reset password link was sent to your email.</strong>
                </span>

            <p class="message hidden" id="message"></p>
            
            <button type="submit" class="btn">Send Password Reset Link</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
