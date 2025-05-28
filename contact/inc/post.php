<?php

const NAME_REQUIRED = 'Please enter your name';
const EMAIL_REQUIRED = 'Please enter your email';
const EMAIL_INVALID = 'Please enter a valid email';
const NO_MESSAGE = 'Please leave a message';

$config = parse_ini_file('../contact/config.ini');
$recaptchaSecretKey = $config['secret_key'];

$inputs = [];
$errors = [];

// Sanitize and validate name
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$inputs['name'] = $name;
$name = trim($name);

if ($name === '') {
    $errors['name'] = NAME_REQUIRED;
}

// Sanitize and validate email
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$inputs['email'] = $email;

if ($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = EMAIL_INVALID;
    }
} else {
    $errors['email'] = EMAIL_REQUIRED;
}

// Sanitize and validate message
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$inputs['message'] = $message;
$message = trim($message);

if ($message === '') {
    $errors['message'] = NO_MESSAGE;
}

if (count($errors) === 0):

    // Verify reCAPTCHA
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret'   => $recaptchaSecretKey,
        'response' => $_POST['g-recaptcha-response'],
        'remoteip' => $_SERVER['REMOTE_ADDR']
    ];

    $options = [
        'http' => [
            'header'  => "Content-Type: application/x-www-form-urlencoded\r\nUser-Agent:MyAgent/1.0\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        ]
    ];

    $context = stream_context_create($options);
    $verify = file_get_contents($url, false, $context);
    $captchaSuccess = json_decode($verify);

    if (!$captchaSuccess->success):
        echo "<div class='submitted'>Oh no! It looks like you were unable to pass the captcha.
              <br>Don't worry, you can <a class='page' href='contact.php'>try again</a>.</div>";
        exit;
    endif;

    // Send internal contact email
    $myemail = 'noreply@followcrom.com';
    $to = 'hello@followcrom.com';
    $subject = "Contact Form Submission";
    $body = "Contact form submission - here are the details:\n\n"
        . "Name: $name\n"
        . "Email: $email\n"
        . "Message:\n$message\n\n"
        . "IP Address: {$_SERVER['REMOTE_ADDR']}\n"
        . "User Agent: {$_SERVER['HTTP_USER_AGENT']}\n"
        . "API Response: {$captchaSuccess->success}\n";

    $headers = "From: followCrom Contact <{$myemail}>\r\n";
    $headers .= "Reply-To: {$email}\r\n";
    $headers .= "Content-Type: text/plain; charset=utf-8\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    mail($to, $subject, $body, $headers);

    // 🎉 Display success message
?>
    <div class="submitted icons">
        <p>Thanks for your message <b><?php echo htmlspecialchars($name); ?></b>.
            <br>I will get back to you at <i><?php echo htmlspecialchars($email); ?></i> within 48 hours.
        </p>
        <div class="icon-list">
            <div class="icon-item">
                <i class="fa-solid fa-envelope" style="color: #FF9900;"></i>
                <a class="page" href="contact.php">Send another message</a>
            </div>
        </div>
    </div>
<?php

    // ✅ Send confirmation email to user
    if (!empty($inputs['email'])) {
        $to = $inputs['email'];
        $subject = "Thanks for contacting followCrom";
        $confirmMessage = "👋 Hi " . htmlspecialchars($inputs['name']) . ",\n\n"
            . "Thanks for getting in touch. We've received your message and will get back to you within 48 hours. 📬\n\n"
            . "Regards,\nfollowCrom\n\n"
            . "🌐 Visit us at https://followcrom.com"
            . "\n\n🤖 This is an automated message. Please do not reply.";

        $confirmHeaders = "From: followCrom<noreply@followcrom.com>\r\n";
        $confirmHeaders .= "Reply-To: noreply@followcrom.com\r\n";
        $confirmHeaders .= "Content-Type: text/plain; charset=utf-8\r\n";
        $confirmHeaders .= "X-Mailer: PHP/" . phpversion();

        mail($to, $subject, $confirmMessage, $confirmHeaders);
    }

endif;
?>