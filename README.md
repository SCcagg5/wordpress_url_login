# wordpress_url_login


This project allow you to generate an **jwt token** containing the **username** of an actual wordpress user and auto login by passing this argument in the get parameter

`http://<your_wordpressurl>/<your_page>/?jwt=<your_jwt>`

the jwt is by default available for 1 second

```php 
get_jwt ( string $username , string $secret [, int $time = 1 ] ) : string
```

*To enable on your website copy and paste the content of `autolog.php` at the start of your `functions.php`*
