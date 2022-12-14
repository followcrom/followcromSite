<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">

  <h1>Get in Touch</h1>

  <p>Drop us a line and we'll get back to you within 48 hours.</p>

      <div>
        <h3><label for="name">Name:</label></h3>
        <input type="text" name="name" id="name" placeholder="your name" value="<?php echo $inputs['name'] ?? '' ?>" class="<?php echo isset($errors['name']) ? 'error' : ''  ?>">
        <div class="errors"><?php echo $errors['name'] ?? '' ?></div>
    </div>

    <div>
    <h3><label for="name">Email:</label></h3>
        <input type="text" name="email" id="email" placeholder="your email address"
        value="<?php echo $inputs['email'] ?? '' ?>"
        class="<?php echo isset($errors['email']) ? 'error' : '' ?>">
        <div class="errors"><?php echo $errors['email'] ?? '' ?></div>
    </div>

    <div>
    <h3><label for='message'>Message:</label></h3>
        <textarea name="message" id="message" rows="7" cols="37" placeholder="multi-line input" value="<?php echo $inputs['message'] ?? '' ?>" class="<?php echo isset($errors['message']) ? 'error' : '' ?>"><?php echo $inputs['message'] ?? '' ?></textarea>
        <div class="errors"><?php echo $errors['message'] ?? '' ?></div>
        </div>

          <input type="submit" value="Submit">
      </form>
