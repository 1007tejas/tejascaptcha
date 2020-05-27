protected $routeMiddleware = [

	// Add the following line to the bottom of the $routeMiddleware section
	// in your projects app/Http/Kernel.php file

	'tejascaptcha_verify_captcha' => \App\Http\Middleware\VerifyTejasCaptcha::class,
];
