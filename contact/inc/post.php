<?php 

const NAME_REQUIRED = 'Please enter your name';
const EMAIL_REQUIRED = 'Please enter your email';
const EMAIL_INVALID = 'Please enter a valid email';
const NO_MESSAGE = 'Please leave a message';

$config = parse_ini_file('../contact/config.ini');
$recaptchaSecretKey = $config['secret_key'];


// sanitize and validate name
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$inputs['name'] = $name;

if ($name) {
    $name = trim($name);
    if ($name === '') {
        $errors['name'] = NAME_REQUIRED;
    }
} else {
    $errors['name'] = NAME_REQUIRED;
}


// sanitize & validate email
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$inputs['email'] = $email;
if ($email) {
    // validate email
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if ($email === false) {
        $errors['email'] = EMAIL_INVALID;
    }
} else {
    $errors['email'] = EMAIL_REQUIRED;
}



// sanitize and validate message
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$inputs['message'] = $message;

if ($message) {
    $message = trim($message);
    if ($message === '') {
        $errors['Message'] = NO_MESSAGE;
    }
} else {
    $errors['message'] = NO_MESSAGE;
}


if (count($errors) === 0) : 
    
    { $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            // Example usage
            'secret' => $recaptchaSecretKey,
            'response' => $_POST['g-recaptcha-response'],
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ];
        
        $options = array(
            'http' => array (
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
                             "User-Agent:MyAgent/1.0\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        
        $context  = stream_context_create($options);
        $verify = file_get_contents($url, false, $context);
        $captcha_success=json_decode($verify);




        if ($captcha_success->success==false) {
            //This user was not verified by recaptcha.
            echo "<div class='submitted'>Oh no! It looks like you were unable to pass the captcha.
            <br>
            Don't worry, you can <a class='page' href='contact.php'>try again</a>.";
            exit;
        
            }
            else if ($captcha_success->success==true) {
                $myemail = 'info@followcrom.online';
                $to = $myemail;
                $apires = serialize($captcha_success);
                $email_subject = "Contact Form Submission";
                $email_body = "Contact form submission - here are the details:\n Name: $name \n Email: $email \n Message: $message \n API response: $apires \n "; 
        
                $headers = "From: $myemail\n";
                $headers .= "Reply-To: $email";
        
                mail($to,$email_subject,$email_body,$headers);
            //   print_r($captcha_success);
            }
        
        } 
        ?>


<div class="submitted">
    <p>Thanks for your message <b><?php echo htmlspecialchars($name) ?></b>.</p>
    <p>I will get back to you at <i><?php echo htmlspecialchars($email) ?></i> within 48 hours.</p>

    <div class="icon-list">
        <div class="icon-item"><i class="fa-solid fa-envelope" style="color: #FF9900;"></i> <a class="page"
                href="contact.php">Send another message</a></div>
        <div class="icon-item"><i class="fa-brands fa-linkedin" style="color: #0077B5;"></i> <a class="page"
                href="https://linkedin.com/in/followCrom">Connect on LinkedIn</a></div>
        <div class="icon-item"><i class="fa-brands fa-github" style="color: #333;"></i> <a class="page"
                href="https://github.com/followcrom">Explore my GitHub repos</a></div>
    </div>
</div>



<?php endif ?>