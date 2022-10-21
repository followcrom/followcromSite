<?php 

const NAME_REQUIRED = 'Please enter your name';
const EMAIL_REQUIRED = 'Please enter your email';
const EMAIL_INVALID = 'Please enter a valid email';
const NO_MESSAGE = 'Please leave a message';


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


$myemail = 'info@followcrom.online';


if (count($errors) === 0) : 
    
{
    $to = $myemail; 
    $email_subject = "Contact Form Submission";
    $email_body = "Contact form submission:".
    " Here are the details:\n Name: $name \n Email: $email \n Message: $message \n"; 

    $headers = "From: $myemail\n"; 
    $headers .= "Reply-To: $email";

    mail($to,$email_subject,$email_body,$headers);
} 
?>

<div class="content_box">
    <h1>
        Thanks for your message <?php echo htmlspecialchars($name) ?>.
    </h1>
    <div>Here is what you wrote:<br>
    "<?php echo htmlspecialchars($message) ?>"
</div>

<div class="submitted">
    <a class="page" href="contact.php">Send another message</a> | <a class="page" href="index.html">Return to the homepage</a>
</div>
</div>

<?php endif ?>