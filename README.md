<h1 align="center">
  <img alt="quickform" width="652" src="https://jclerc.github.io/assets/repos/banner/quickform.jpg">
  <br>
</h1>

<p align="center">
  <img alt="made for: fun" src="https://jclerc.github.io/assets/static/badges/made-for/fun.svg">
  <img alt="language: php" src="https://jclerc.github.io/assets/static/badges/language/php.svg">
  <img alt="made in: 2016" src="https://jclerc.github.io/assets/static/badges/made-in/2016.svg">
  <br>
  <sub>Create custom forms, publish them and see their statistics.</sub>
</p>
<br>

## Live demo

See the project here: [jclerc.github.io/quickform](https://jclerc.github.io/quickform/) \
_Note: this demo is static, generated from the php version._

## Features

- [x] Create a form and see answers – like Google Forms
- [x] No user account is needed
- [x] Use emails to get edit link
- [x] Based on MVC pattern (without framework)

## Stack used

- PHPMailer `5.2.14`
- PHP `7.0`
- SQLite `3` / MySQL `5.5`
- Apache `2.2`

## Getting started

#### Requirements

- Apache server with PHP 7+

#### Installation

```sh
git clone https://github.com/jclerc/quickform.git
cd quickform
```

Then:
1. Edit email settings in `/htdocs/app/model/service/mail.class.php`
2. Start webserver in directory `/htdocs/www/` and you're done

To use MySQL instead of SQLite:
1. Set `database.use` to `MySQL` in config file (`/htdocs/app/core/config.json`)
2. You may need to change credentials as well (in the same file)
3. Then import schema from `/sql/mysql/qform.sql`

## Notes

- A form is already created with few answers, you can see it at: \
  `your-website.com/stats/id/1/9e38c3eb5fe6b3f92ef6e19f83d02062f45652a5d00db4e2cec5cc9b34575f89/`
