<?php
/**
 * Created by PhpStorm.
 * User: arp
 * Date: 03.10.2017
 * Time: 19:55
 */

require_once __DIR__. "/../vendor/autoload.php";

$credentials = [
    'username' => 'user',
    'password' => 'password'
];

$_ENV['ROUTER_USERNAME'] = 'user';
$_ENV['ROUTER_PASSWORD'] = 'password';
$_ENV['ROUTER_HOST'] = '192.168.178.1';
