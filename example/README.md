
Prerequisite:

The primary README.md is at the root of the tejascaptcha project and must be implemented first, before this guide is implemented.

----

This README.md is based on a fresh laravel install, where the only route is the welcome route.

----

* ` cp vendor/tejas/tejascaptcha/example/resources/js/tejascaptcha.js resources/js/ `

Edit your projects resources/js/app.js file.
At the top of the file add the following line:

* ` require('./tejascaptcha'); `
- - - -

* ` cp vendor/tejas/tejascaptcha/example/resources/sass/tejascaptcha*scss resources/sass/ `

Edit your projects resources/sass/app.scss file.
At the top of the file add the following two lines:

* ` @import './tejascaptcha.scss'; `
* ` @import './tejascaptcha_blade.scss'; `
- - - -

* ` cp vendor/tejas/tejascaptcha/example/resources/views/tejascaptcha.blade.php resources/views/ `
----

Edit your projects routes/web.php file.

Comment the welcome route:

```
/*
Route::get('/', function () {
    return view('welcome');
});
*/
```

* ` cat vendor/tejas/tejascaptcha/example/routes/web.php >> routes/web.php `


![The tejascaptcha Web Routes file image is not available](../assets/githubReadme/images/tejasCaptchaRoutesWeb.png?raw=true "TejasCaptcha Web Routes file")


__app/Http/Kernel.php__

In the `app/Http/Kernel.php` file, at the bottom of the `routeMiddleware` section insert the following line:
* `'tejascaptcha_verify_captcha' => \App\Http\Middleware\VerifyTejasCaptcha::class,`

![The tejascaptcha middleware Kernel file image is not available](../assets/githubReadme/images/tejasCaptchaMiddlewareKernel.png?raw=true "TejasCaptcha Middleware Kernel file")
