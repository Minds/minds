PHP-LinkedIn-SDK
================

A PHP wrapper for the LinkedIn API


Here's a quick way to get started with this wrapper:
---

Instantiate our class
```php
$li = new LinkedIn(
  array(
    'api_key' => 'yourapikey', 
    'api_secret' => 'yourapisecret', 
    'callback_url' => 'https://yourdomain.com/redirecthere'
  )
);
```

Get the login URL - this accepts an array of SCOPES
```php
$url = $li->getLoginUrl(
  array(
    LinkedIn::SCOPE_BASIC_PROFILE, 
    LinkedIn::SCOPE_EMAIL_ADDRESS, 
    LinkedIn::SCOPE_NETWORK
  )
);
```

LinkedIn will redirect to 'callback_url' with an access token as the 'code' parameter. 
You might want to store the token in your session so the user doesn't have to log in again
```php
$token = $li->getAccessToken($_REQUEST['code']);
$token_expires = $li->getAccessTokenExpiration();
```

Make a request to the API
```php
$info = $li->get('/people/~:(first-name,last-name,positions)');
```
