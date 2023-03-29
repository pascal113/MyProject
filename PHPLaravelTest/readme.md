# brownbear.com

## Requirements

PHP >= 7.2, MySQL >= 5.7.8/MariaDB >= 10.2, NodeJS >= 14.15

### Required PHP modules:

1. All [Laravel required models](https://laravel.com/docs/7.x/installation#server-requirements)

## Framework

Laravel `7.x` & Laravel Voyager `1.4.x`

## Installation

1. If you need an environment that meets the Requirements above, follow the "Create Docker container" instructions below
1. Copy .env.example to .env: `cp .env.example .env`
1. Fill .env with correct values.
    - `APP_ENV` **MUST** be `local` for local dev.
    - remember that #comments can not exist on the same line as a name/value pair
1. Create database with credentials matching .env
1. Skip on local: Create the following cronjob on the www-data user: `* * * * * php /path/to/repository_root/artisan schedule:run >> /dev/null 2>&1`
1. Make sure npx is installed: `npm install -g npx`
1. Run `composer install-app` (`composer install-app-prod` on production)
1. Production only: You may now safely delete the node_modules dir: `rm -rf node_modules`
1. On a new deployment (especially production), run `php artisan key:generate` to generate .env:APP_KEY
1. DocumentRoot should point to ./public

### Install supervisor

Skip on local

Supervisor is required in order to automatically process job queues.

1. `sudo apt-get install supervisor`
1. Copy supervisor config file `sudo cp ./supervisor.conf.example /etc/supervisor/conf.d/brownbear.com.conf`
1. Fill `/etc/supervisor/conf.d/brownbear.com.conf` with correct values
1. Run `sudo supervisorctl reread`
1. Run `sudo supervisorctl update`
1. Run `sudo supervisorctl start brownbear.com:*`

## To reset and re-seed entire database

1. Run `composer reset`

## To strip db-stored domain names:

The database stores references to URLs for things such as images in several places. For example, in the `content` of Pages stored in the CMS, and the `image` for a Product. Some (older) implementations stored the static domain name in the URL, which is problematic when changing domain names. To strip the domain references stored in the database, use the following command. **Please note:** This is not a fool-proof conversion, and you will likely need to manually scan the database for other references.

`php artisan db:strip-image-url-domains {domain}`

## Running locally

1. Run `yarn start` (add `--host={hostname or IP}` to specify a different host name). Browser will automatically open to http://localhost:3010 (or specified hostname) and update on changes

## Running on a local server, including code-server

In order to run both this repo and the gateway.brownbear.com repo on any local server, a real-world URL must be used (code-server's proxy feature will not suffice).

1. Point DNS for your local domain name to http://localhost:8010
1. Use local domain name values in .env where appropriate (such as `APP_URL`).
1. Remove or comment out `BROWSER_SYNC_PORT` in ./.env
1. Run `yarn start` as usual.
1. Open a browser to the local domain name.

## Public Key Infrastructure setup to connect to Payeezy, aka Updating Payeezy Certificates

Payeezy moved to a self-signed TLS certificate for https in June 2022. They provided a CER file as a key to validate their cert. Current steps to acquire and use the cert are as follows:

1. https://support.payeezy.com/hc/en-us/articles/203850459-Maintenance-and-Release-Notes  also "Maintenance and Release Notes" off their https://support.payeezy.com/ site
1. Download the `DEMO - Download Certificate` and the `PROD - Download Certificate`, which are `.cer` files
1. have `openssl` utility already installed
1. run `openssl x509 -inform der -in <source.cer> -out <for-deployment.pem>`
    - example `openssl x509 -inform der -in demo.globalgatewaye4.firstdata.com.cer -out demo.globalgatewaye4.firstdata.com.pem` for Local/Dev/QA
    - example `openssl x509 -inform der -in globalgatewaye4.firstdata.com.cer -out globalgatewaye4.firstdata.com.pem`
1. replace the .pem files in repository root with the newly created ones, while keeping the exact filenames
1. expect to replace on an annual basis

## Create Docker container

1. Copy .env.example to .env
1. Grab your local intranet IP address: Try `ipconfig getifaddr en0` or `ipconfig getifaddr en1`, or find it in Open Network Preferences -> Advanced -> TCP/IP if on mac
1. Fill .env with correct values. Use `http://localhost:8088` for APP_URL, the IP address from above for DB_HOST, `3307` for DB_PORT, `root` for DB_USERNAME, and `12345` for DB_PASSWORD
1. Run `./.docker/run-server.sh`
1. ssh into the mysql Docker instance: `docker exec -ti brownbear.com-mysql bash`
1. Create database: `mysql -u root -p12345 -e "CREATE DATABASE <the value of DB_DATABASE in your .env>;"`
1. Exit mysql Docker instance: `exit`
1. Run "To reset and re-seed entire database" instructions above
1. Open a browser to http://localhost:8088
1. If you need to ssh into the app Docker instance: `docker exec -ti --workdir /var/www/html/brownbear.com brownbear.com-app bash`
1. If you want to complete remove the Docker instances & containers: `docker ps a` and then `docker rm -f {CONTAINER_ID}` for each container you want to remove

## Linting & formatting

1. PHP linting & formatting with automatic fix: Run `yarn lint`

## Detect vulnerabilities

Snyk is used to detect known vulnerabilities:

1. `yarn snyk test`

## Testing

Unit tests are performed using PHPUnit. Tests are located in `./tests/**/`

1. To run tests, run `composer test` from repository root

## Visual Regression Tests

Visual regression tests are performed using Laravel Dusk, Percy, stechstudio/laravel-visual-testing. Tests are located in `./tests/Browser/**/`

#### Run a Percy Visual Regression Test:

1. The site has to be running locally (`php artisan serve`)
1. Run `php artisan dusk:chrome-driver 72 && php artisan dusk` (makes sure correct Chrome driver is installed, then runs tests)
1. Upon success, visit https://percy.io/fpcs/brownbear.com to see the new Percy build

#### Details:

* Laravel Dusk & ChromeDriver are required for visual regression tests: `php artisan dusk:chrome-driver 72`
* To run visual regression tests, run `php artisan dusk` from repository root. Snapshots will be available at https://percy.io/fpcs/brownbear.com
* To re-run only failed tests, run `php artisan dusk:fails`
* To do a dry run and not push snapshots to Percy: Comment out `--headless` and `--disable-gpu` in tests/DuskTestCase.php, run test with `php artisan dusk --without-percy`. This will result in seeing the chrome windows open on your screen, though they will close quickly thereafter. Consider adding arbitrary pauses to each test like `->pause(5000)`

## Admin interface

1. Visit `/admin/` in a browser. Default admin user is dev@theflowerpress.net

## Log Out all users

**It is advisable when running this command to run it on both this app as well as the gateway.brownbear.com app.** If needed, this command will log out all users:

`php artisan log-out-all-users`

## MJML emails

* .mjml and -text-blade.php email source files in ./resources/views/emails are automatically compiled into blade templates upon commit.
* To manually compile them, run `yarn compile-emails` in repository root after making changes to the .mjml and -text-blade.php source files.

## Testing/viewing emails locally

To view emails generated by this app when running at localhost, you have at least two options:

#### Option 1: Log+copy/paste

1. Set your maildriver to "log" in .env: `MAIL_DRIVER=log`
1. When the app sends an email, it will be written to a log file instead of sent out via an email server. Open the newest log file under ./storage/logs and look for the email content.
1. Copy the HTML email to a new file (such as ./public/test.html), and visit it in a browser. Text versions of emails can be viewed directly in the log file.

#### Option 2: Mailhog

Use Mailhog to view emails in a more streamlined way, within a browser

1. Install: `brew update && brew install mailhog` (Requires [Homebrew](https://brew.sh/))
1. Run Mailhog: `brew services start mailhog`
1. Set up your .env MAIL section with the values below
1. When the app sends an email, you will be able to view it in a browser by going to [http://0.0.0.0:8025](http://0.0.0.0:8025)

```
MAIL_DRIVER=smtp
MAIL_HOST=0.0.0.0
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
```

## Automatic superscript for ®, ©, and ℗

Design specifies that ®, ©, and ℗ should always be in superscript. However, dynamic content stored in the database (such as CMS page content) is often not allowed to contain HTML.

To remedy this conflict, add the class `.make-trademarks-superscript` to any element in which we want to have the content automatically get those chracters converted to superscript in.

# reCAPTCHA

Google's reCAPTCHA v2 (checkbox) is used to prevent bots on some submission forms.

The instance is currently owned by mrabe@theflowerpress.net and kenglish@theflowerpress.net, and can be administered here: https://www.google.com/u/1/recaptcha/admin/site/348413240

## Sitemap

The sitemap (available at /sitemap.xml) is updated automatically on every commit. To manually re-generate the sitemap, run `php artisan sitemap:generate`

## HTML Validation

Run `yarn test:html` to get HTML validation results from W3C.
