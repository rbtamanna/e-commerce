#installation
1. composer install
2. cp .env.example .env
3. in your .env these things should be set-

For Job Queue

        QUEUE_CONNECTION=database
For Mail

        MAIL_MAILER=smtp
        MAIL_HOST=smtp.gmail.com
        MAIL_PORT=587
        MAIL_USERNAME=your_gmail
        MAIL_PASSWORD=16 digit code created from google accoutn->secuirity->app password->generate with giving a name
        MAIL_ENCRYPTION=tls
        MAIL_FROM_ADDRESS=
        MAIL_FROM_NAME=   
For Google Login( Get these from https://console.developers.google.com/)

        GOOGLE_CLIENT_ID=
        GOOGLE_CLIENT_SECRET=
        GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
5. Update database configuration in .env
6. php artisan migrate
7. php artisan db:seed
8. php artisan key:generate
9. php artisan serve
10. must have in php.ini

        extension=php_zip.dll
        extension=gd    
