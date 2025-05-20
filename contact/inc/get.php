<noscript>
    <div class="errors">
        JavaScript must be enabled to submit this form.
    </div>
</noscript>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" novalidate>
    <h1>Get in Touch</h1>

    <p><i class="fa-solid fa-handshake"></i> Let's connect. Send me a message and I will get back to you within 48 hours.</p>

    <div>
        <label for="name">Name:</label>
        <input type="text"
            name="name"
            id="name"
            placeholder="Your name (required)"
            autocomplete="name"
            value="<?php echo $inputs['name'] ?? '' ?>"
            class="<?php echo isset($errors['name']) ? 'error' : '' ?>">
        <div class="errors"><?php echo $errors['name'] ?? '' ?></div>
    </div>

    <div>
        <label for="email">Email:</label>
        <input type="text"
            name="email"
            id="email"
            placeholder="Your email (required)"
            autocomplete="email"
            value="<?php echo $inputs['email'] ?? '' ?>"
            class="<?php echo isset($errors['email']) ? 'error' : '' ?>">
        <div class="errors"><?php echo $errors['email'] ?? '' ?></div>
    </div>

    <div>
        <label for="message">Message:</label>
        <textarea name="message"
            id="message"
            rows="7"
            cols="37"
            placeholder="Your message (required)"
            class="<?php echo isset($errors['message']) ? 'error' : '' ?>"><?php echo $inputs['message'] ?? '' ?></textarea>
        <div class="errors"><?php echo $errors['message'] ?? '' ?></div>
    </div>

    <div>
        <p>Anti-spam check:</p>
        <div class="g-recaptcha"
            data-sitekey="6LdzbkIoAAAAAJxgGPBrMvx688rUFaqoxUD5AMxS"></div>
    </div>

    <div style="display:none;" aria-hidden="true">
        <label for="website">Leave this field blank</label>
        <input type="text" name="website" id="website" autocomplete="off" tabindex="-1">
    </div>

    <input type="submit" value="Submit" aria-label="Submit the form">
</form>