<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">

    <h1>Get in Touch</h1>

    <p>Let's connect! Drop me a message and I will get back to you within 48 hours.</p>

    <div>
        <label for="name">
            <h3>Name:</h3>
        </label>
        <input type="text" name="name" id="name" placeholder="Your name (required)" autocomplete="name"
            value="<?php echo $inputs['name'] ?? '' ?>" class="<?php echo isset($errors['name']) ? 'error' : ''  ?>">
        <div class="errors"><?php echo $errors['name'] ?? '' ?></div>
    </div>

    <div>
        <label for="email">
            <h3>Email:</h3>
        </label>
        <input type="email" name="email" id="email" placeholder="Your email (required)" autocomplete="email"
            value="<?php echo $inputs['email'] ?? '' ?>" class="<?php echo isset($errors['email']) ? 'error' : ''  ?>">
        <div class="errors"><?php echo $errors['email'] ?? '' ?></div>
    </div>

    <div>
        <label for='message'>
            <h3>Message:</h3>
        </label>
        <textarea name="message" id="message" rows="7" cols="37" placeholder="Your message (required)"
            class="<?php echo isset($errors['message']) ? 'error' : '' ?>"><?php echo $inputs['message'] ?? '' ?></textarea>
        <div class="errors"><?php echo $errors['message'] ?? '' ?></div>
    </div>
    <div class="g-recaptcha" data-sitekey="6LdzbkIoAAAAAJxgGPBrMvx688rUFaqoxUD5AMxS"></div>
    <input type="submit" value="Submit" aria-label="Submit the form">
</form>