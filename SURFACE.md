# 👩🏻‍💻 **followCrom.online** on Digital Ocean 🦾📓✍🏻💡

Create a directory in `/var/www/` for the static website files (HTML, CSS, JavaScript, images, etc.).

```bash
sudo mkdir -p /var/www/fc_online
```

## Move fies onto D.O. VM 📦

You can use **SCP** or **rsync** to upload the files to your server. Here's an example using SCP:

```bash
scp -i ~/.ssh/digiocean -r ftp root@188.166.155.230:/var/www/fc_online
```

**rsync** is another option that provides more flexibility and control over the transfer process, as it will update only the files that have changed on the local server without re-uploading everything. It also allows you to exclude certain files or directories from the transfer. Here's an example:

```bash
rsync -avz --exclude='.git' --exclude='.github' -e "ssh -i ~/.ssh/digiocean" ftp/ root@188.166.155.230:/var/www/fc_online/
```

Explanation of the Options:

- -a (archive mode): This option preserves permissions, symbolic links, timestamps, and other file attributes.
- -v (verbose): Shows detailed information during the sync process.
- -z (compress): Compresses file data during the transfer, which can speed up the process if you're transferring over a slow network.

**Dry Run**: If you want to preview which files would be transferred without actually performing the sync, you can add the `--dry-run` option:

```bash
rsync -avz --dry-run --exclude='.git' --exclude='.github' -e "ssh -i ~/.ssh/digiocean" ftp/ root@188.166.155.230:/var/www/fc_online/
```

📤 Check the output to ensure that only the intended files are transferred:

```bash
cd /var/www/fc_online
ll -a # See ownership and permissions
```

### Ownership (1000:1000) 🗃️

The numeric 1000:1000 that might be seen at this stage indicates that the files are owned by a user and group with the UID and GID of 1000. This is typical if you've uploaded the files as a specific user. If this user is not `www-data`, and you prefer to align ownership, you can change it like this:

```bash
sudo chown -R www-data:www-data /var/www/fc_online/
```

### Write Permissions:

As Nginx doesn't need write permissions, ensure that files and directories are not world-writable for security purposes (i.e. `chmod 777` should be avoided).

<br>

# Nginx Configuration 🆖

### Option 1: Serve Static Site on a Specific Path

`sudo nano /etc/nginx/sites-available/ttt`

```nginx
server {
    server_name www.ttt.followcrom.online ttt.followcrom.online 188.166.155.230;

    location / {
        include proxy_params;
        proxy_pass http://unix:/var/www/ttt/tttracker.sock;
    }

    location /fc/ {
        alias /var/www/fc_online/;
        index index.html;
    }

    listen 443 ssl; # managed by Certbot
    ssl_certificate /etc/letsencrypt/live/ttt.followcrom.online/fullchain.pem; # managed by Certbot
    ssl_certificate_key /etc/letsencrypt/live/ttt.followcrom.online/privkey.pem; # managed by Certbot
    include /etc/letsencrypt/options-ssl-nginx.conf; # managed by Certbot
    ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem; # managed by Certbot
}

server {
    if ($host = ttt.followcrom.online) {
        return 301 https://$host$request_uri;
    } # managed by Certbot

    listen 80;
    server_name www.ttt.followcrom.online ttt.followcrom.online 188.166.155.230;
    return 404; # managed by Certbot
}
```

Test the Nginx configuration and reload Nginx:

```bash
sudo nginx -t
sudo systemctl reload nginx
```

This serves the static site on a specific path: `https://ttt.followcrom.online/fc/index.html` but there are still issues to resolve with the php on the contact page. 🔗 [See this section](#php-contact-form) for the PHP contact form setup.

<br>

### Option 2: Serve Static Site on a Different Subdomain

To serve the static site on a different subdomain (e.g., static.ttt.followcrom.online), you'll need a separate SSL certificate for that subdomain. Each subdomain requires its own SSL certificate unless you're using a wildcard certificate.

Update your Nginx configuration to include a new server block for the new subdomain:

`sudo nano /etc/nginx/sites-available/ttt`

```nginx
server {
    server_name static.ttt.followcrom.online;

    root /var/www/fc_online;  # Path to your static site files
    index index.html;

    location / {
        try_files $uri $uri/ =404;
    }

    listen 443 ssl; # managed by Certbot
    ssl_certificate /etc/letsencrypt/live/static.ttt.followcrom.online/fullchain.pem; # managed by Certbot
    ssl_certificate_key /etc/letsencrypt/live/static.ttt.followcrom.online/privkey.pem; # managed by Certbot
    include /etc/letsencrypt/options-ssl-nginx.conf; # managed by Certbot
    ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem; # managed by Certbot
}

server {
    if ($host = static.ttt.followcrom.online) {
        return 301 https://$host$request_uri;
    } # managed by Certbot

    listen 80;
    server_name static.ttt.followcrom.online;
    return 404; # managed by Certbot
}
```

Run the following Certbot command to obtain and install an SSL certificate for the new subdomain:

```bash
sudo certbot --nginx -d static.ttt.followcrom.online
```

Certbot will automatically detect the Nginx configuration, obtain the certificate, and configure the SSL settings in your Nginx configuration for that subdomain.

After obtaining the certificate, Certbot will usually reload Nginx for you. However, if necessary, you can manually reload Nginx:

```bash
sudo nginx -t
sudo systemctl reload nginx
```

<br>

# </> PHP contact form </>

To get PHP scripts to run on the VM, you'll need to install and configure PHP with Nginx. 

```bash
# Update the Package List:
sudo apt update

# Install PHP and the necessary PHP-FPM (FastCGI Process Manager) package:
sudo apt install php-fpm
```

### Configure the Server Block for PHP

This is for option 1, serving the static site on a specific path. Update the Nginx configuration file:

`sudo nano /etc/nginx/sites-available/ttt`

```nginx
server {
    server_name www.ttt.followcrom.online ttt.followcrom.online 188.166.155.230;

    # Root location block for your Django app
    location / {
        include proxy_params;
        proxy_pass http://unix:/var/www/ttt/tttracker.sock;
    }

    # Static files location for the static site
    location /fc/ {
        alias /var/www/fc_online/;
        index index.html;
        try_files $uri $uri/ =404;
    }

    # Handle PHP files specifically in the /fc/contact/ directory
    location ~ ^/fc/contact/(.+\.php)$ {
        alias /var/www/fc_online/contact/$1;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index contact.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $request_filename;
    }

    # SSL configuration (already handled by Certbot)
    listen 443 ssl; # managed by Certbot
    ssl_certificate /etc/letsencrypt/live/ttt.followcrom.online/fullchain.pem; # managed by Certbot
    ssl_certificate_key /etc/letsencrypt/live/ttt.followcrom.online/privkey.pem; # managed by Certbot
    include /etc/letsencrypt/options-ssl-nginx.conf; # managed by Certbot
    ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem; # managed by Certbot
}

# Redirect HTTP to HTTPS
server {
    if ($host = ttt.followcrom.online) {
        return 301 https://$host$request_uri;
    } # managed by Certbot

    listen 80;
    server_name ttt.followcrom.online;
    return 404; # managed by Certbot
}
```

<br>

- The `location` regex captures the PHP filename.
- The `alias` directive uses this capture group (`$1`) to correctly map the URI to the file system path. I believe this is need because I am using includes for `get.php` and `post.php`.
- `php8.1-fpm.sock` is the PHP FastCGI socket file.
- Currently not handling the php in the `wotd` directory. See below for that configuration.

<br>

```bash
# Check Nginx configuration and reload
nginx -t
systemctl reload nginx
systemctl restart nginx # if necessary. Reload is preferred.
systemctl status nginx
```

### 🧙🏼‍♂️ Troubleshooting PHP-FPM 🕵

```bash
# Verify the PHP-FPM socket exists and is accessible:
ls -l /run/php/php8.1-fpm.sock
# It should show something like:
srw-rw---- 1 www-data www-data 0 Aug 18 08:53 /run/php/php8.1-fpm.sock

# Ensure PHP-FPM is running:
sudo systemctl status php8.1-fpm

# If it's not running, start and enable it:
sudo systemctl start php8.1-fpm
sudo systemctl restart php8.1-fpm
sudo systemctl enable php8.1-fpm
```

### 📊 Error Logs 🪵

```bash
tail -f /var/log/nginx/error.log
tail -f /var/log/php8.1-fpm.log
```

Great! 🤗 Now the PHP files are being executed instead of downloaded, meaning that Nginx is correctly processing them through PHP-FPM. However, 🤚 the PHP script is not sending emails. This is because the server does not have a mail server configured.

<br>

# 📨 Mail server 📬

Ensure that the PHP `mail()` function is correctly configured. You can test this with a simple PHP script called `test_mail.php` in the `/var/www/fc_online/contact/` directory:

```php
<?php
$to = "followcrom@gmail.com";
$subject = "Test email from PHP";
$message = "This is a test email sent from PHP on your server.";
$headers = "From: followcrom@gmail.com";

if(mail($to, $subject, $message, $headers)) {
    echo "Test email sent successfully!";
} else {
    echo "Failed to send test email.";
}
?>
```

Access this file through the browser (https://ttt.followcrom.online/fc/contact/test_mail.php) and check if the test email is received.

## Mail Transfer Agent (MTA) ✉️ 

As the mail was not sending, I knew the VM did not have a mail server configured. I needed to install and configure a Mail Transfer Agent (MTA) on the server. I chose **Postfix** as the MTA and configured it to relay emails through **Gmail's SMTP server**.

```bash
# Update package list
sudo apt update

# Install Postfix and mailutils
sudo apt install postfix mailutils -y

# During installation, choose "Internet Site" when prompted. For the System mail name, use your domain name (ttt.followcrom.online).

# Open Postfix configuration file:
sudo nano /etc/postfix/main.cf

# Add or modify these lines:
myhostname = ttt.followcrom.online
alias_maps = hash:/etc/aliases
alias_database = hash:/etc/aliases
myorigin = /etc/mailname
mydestination = $myhostname, localhost.$mydomain, localhost
relayhost = [smtp.gmail.com]:587
mynetworks = 127.0.0.0/8 [::ffff:127.0.0.0]/104 [::1]/128
mailbox_size_limit = 0
recipient_delimiter = +
inet_interfaces = all
inet_protocols = all
myhostname = ttt.followcrom.online # probably already set
smtp_use_tls = yes
smtp_sasl_auth_enable = yes
smtp_sasl_password_maps = hash:/etc/postfix/sasl_passwd
smtp_sasl_security_options = noanonymous

# Save and exit (Ctrl+X, then Y, then Enter)

# Create a password file (replace with Gmail address and app password in `/docs`) ** see below
echo "[smtp.gmail.com]:587 followcrom@gmail.com:pgnyoxtmxxxxxxxx" | sudo tee /etc/postfix/sasl_passwd

# Secure the password file
sudo chmod 600 /etc/postfix/sasl_passwd

# Create the hash db file for Postfix
sudo postmap /etc/postfix/sasl_passwd

# Restart Postfix
sudo systemctl restart postfix

# Check the status
sudo systemctl status postfix

# If necessary, but probably not needed:
sudo systemctl enable postfix
sudo systemctl start postfix

# Test the email setup
echo "This is a test email" | mail -s "Test Subject" teedcrompton@gmail.com

# Check the mail log:
sudo tail -f /var/log/mail.log
```

** Create an **App Password** (not regular Gmail password) for Postfix to use to authenticate with Gmail's SMTP server.

- Log into your Google account and go to the following link: https://myaccount.google.com/apppasswords
- Enter "Postfix" as the name, then `Create`.
- This will generate a 16-character password.

Now we can access this file through the browser (https://ttt.followcrom.online/fc/contact/test_mail.php) and the test email is received! 🤗

However, if we email via the IONOS contact form, the email goes to `info@followcrom.online` but is not being forward to my personal email. In the SURFACE files I changed the email address in `post.php` to _followcrom@gmail.com_, and the message is received at that address. There might be some issue with the IONOS email forwarding using Postfix with Gmail.

<br>

# Handle `subscribe.php` in the wotd directory 📁

Extend the Nginx configuration to allow PHP files in the `/fc/wotd/` dir. Also with additional logging for debugging and PHP error logging.

```nginx
# Handle subscribe.php in /fc/wotd/ directory
location = /fc/wotd/subscribe.php {
    alias /var/www/fc_online/wotd/subscribe.php;
    fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
    include fastcgi_params;
    fastcgi_param SCRIPT_FILENAME $request_filename;
    # Debug logging
    error_log /var/log/nginx/php_debug.log debug;
    # PHP error logging
    fastcgi_param PHP_VALUE "error_log=/var/log/nginx/php_errors.log";
}
```

<br>

# 📝 Things to Note

- Some links in the static site were broken due to the change in the URL structure. I needed to update the links in the HTML files to reflect the new URL structure.

- The favicons are all currently the vinyl record. I need to update these to the brain logo.

- Be cautious when uploading these new files to GitHub, as a GitHub Action is set up to deploy the files to the IONOS server on push. 

Create a new branch and push the changes:

```bash
git checkout -b SURFACE

git push origin SURFACE
```
