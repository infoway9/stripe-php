# stripe-php
PHP library for the Stripe API
# Requirement
PHP 5.6 
# Composer
You can install via Composer. Run the following command:
composer require stripe/stripe-php
# To Use
require_once('vendor/autoload.php');<br/>
require_once('vendor/stripe/stripe-php/init.php');<br/>
Check the index.php for credit card payment.<br/>
Change the Stripe Secret Key in line number 48 in index.php<br/>
\Stripe\Stripe::setApiKey("YOUR_SECRET_KEY_HERE");
# Sample card Number 
Please use for <a href="https://stripe.com/docs/testing">test credit card </a>
