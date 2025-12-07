<?php
// Start session and get the user's name
session_start();
$name = isset($_SESSION['thank_you_name']) ? htmlspecialchars($_SESSION['thank_you_name']) : 'there';
// Clear the session variable after using it
unset($_SESSION['thank_you_name']);
?>
<?php include 'header.html'; ?>

<div class="form_box">
    <div class="submitted icons">
        <h1>Thank You!</h1>
        <p style="font-size: 1.4em; line-height: 1.8em;">
            Thanks for your message, <b><?php echo $name; ?></b>.
            <br><br>
            I've received your message and will get back to you within 48 hours.
            <br>
            You should also receive a confirmation email shortly.
        </p>
        <div class="icon-list" style="margin-top: 30px;">
            <div class="icon-item">
                <i class="fa-solid fa-house-chimney-user" style="color: #85cdff; font-size: 1.5em;"></i>
                <a class="page" href="/index.html">Return to homepage</a>
            </div>
            <br>
            <div class="icon-item">
                <i class="fa-solid fa-envelope" style="color: #FF9900; font-size: 1.5em;"></i>
                <a class="page" href="contact.php">Send another message</a>
            </div>
        </div>
    </div>
</div>

<style>
.submitted {
    margin: 50px auto;
    padding: 40px;
    max-width: 600px;
    text-align: center;
}

.submitted h1 {
    color: #85cdff;
    margin-bottom: 20px;
}

.icon-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
    align-items: center;
}

.icon-item {
    display: flex;
    align-items: center;
    gap: 15px;
}

a.page {
    font-family: 'Open Sans', Arial, Helvetica, sans-serif;
    font-size: 1.2em;
    color: #000;
    text-decoration: underline;
    cursor: pointer;
}

a.page:hover {
    color: #85cdff;
}
</style>

</body>
</html>
