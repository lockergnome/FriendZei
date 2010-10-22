<?php
/**
 * Facebook API Configuration
 */

require 'facebook.php';

/* Set up Facebook API object */
$FB = new Facebook(array(
    'appId'     => '159073957460563',
    'secret'    => '2587b1d6afd85832c723c1b2da247f2f',
    'cookie'    => true
));
