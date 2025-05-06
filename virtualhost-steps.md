# Setting Up Laravel Local Domain `laraveldemo.local` on Linux Mint with Apache

This guide helps you set up a custom local domain (`laraveldemo.local`) to serve a Laravel project using Apache, by including a custom virtual host file inside `000-default.conf`.

My project path is : `/var/www/html/laraveldemo`.
Please update paths according your project.

---

## 1. Create Custom Apache Virtual Host Config File

Create the config file at:

```
sudo nano /var/www/html/laraveldemo/proxy-le-ssl.conf
```

Paste the following content:

```apache
<VirtualHost *:80>
    ServerName laraveldemo.local
    DocumentRoot /var/www/html/laraveldemo/public

    <Directory /var/www/html/laraveldemo/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/laraveldemo_error.log
    CustomLog ${APACHE_LOG_DIR}/laraveldemo_access.log combined
</VirtualHost>
```

Save and close the file.

## 2. Include This Config in `000-default.conf`

Open the Apache default virtual host configuration file:

```
sudo nano /etc/apache2/sites-available/000-default.conf
```

Add the following line to the bottom of the file:

```
Include /var/www/html/laraveldemo/proxy-le-ssl.conf
```

Save and close the file.

## 3. Edit the Hosts File

Map the custom domain to localhost:

```
sudo nano /etc/hosts
```

Add this line at the end:

```
127.0.0.1 laraveldemo.local
```

Save and close the file.

## 4. Restart Apache

Apply changes by restarting the Apache service:

```
sudo systemctl restart apache2
```

## 5. Set Correct Permissions (Optional but Recommended)

Ensure Laravel project files have the right permissions:

```
sudo chown -R www-data:www-data /var/www/html/laraveldemo
sudo chmod -R 755 /var/www/html/laraveldemo
```

## 6. Access the Laravel Application

Open your browser and go to:

```
http://laraveldemo.local
```

Your Laravel application should now be accessible via the custom local domain.

---