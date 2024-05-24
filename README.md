
# Task Management Backend

Mini Task Management Application using PHP Laravel for the backend.

First pull this project do the following steps!




# Task Management Backend

Mini Task Management Application using PHP Laravel for the backend.

First pull this project do the following steps!


## Deployment

To deploy this project run

```bash
  composer install
```

rename .example.env to .env and create database on your system and make database connection.

After this run migration command to create tables inn connected database

```bash
  php artisan migrate
```

and then run this command to start the backend server

```bash
  php artisan serve
```


## Task Reminder Notification Commands


this service use email configuration in your env file

## Replace this

```javascript
MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## By this

```javascript
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=mygoogle@gmail.com 
MAIL_PASSWORD=*************
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=mygoogle@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

first run queue command

```bash
  php artisan queue:work
```
and then for send reminder notification

```bash
  php artisan schedule:run
```
