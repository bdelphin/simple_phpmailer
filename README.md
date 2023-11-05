# Simple PHPMailer with Docker

A simple PHP script designed to send email from CLI using PHPMailer class, inside a Docker Container.

## Usage

Clone the [github repository](https://github.com/bdelphin/simple_phpmailer) :

```bash
git clone git@github.com:bdelphin/simple_phpmailer.git
cd simple_phpmailer
```

Create a copy of the `.env.example` file nammed `.env`, and configure the environment variables in this file with a text editor :

```bash
cp .env.example .env
vim .env
```

To send a mail, lauch the following command :

```bash
docker run -it --rm -v ${PWD}/.env:/app/.env \
--name simple_phpmailer bdelphin/simple_phpmailer:latest \
php mailer.php -s "Mail subject" \
-m "Mail body" \
-r "recipient@example.com"
```

## Issues, troubleshooting & contributing

This script isn't intended to be use in production, use it at your own risk.

Feel free to create a PR if you want to contribute improving it.
