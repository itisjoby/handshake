<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['protocol'] = 'smtp';
$config['charset'] = 'utf-8';
$config['mailtype'] = 'html';
$config['newline']  = "\r\n";
$config['crlf']  = "\r\n";

$config['smtp_host'] = _SMTP_HOST;
$config['smtp_port'] = _SMTP_PORT;

$config['smtp_user'] = _SMTP_USER_EMAIL;
$config['smtp_pass'] = _SMTP_USER_PASSWORD;

$config['smtp_timeout'] = 30;

if(_SMTP_AUTH_MODE!='') {
	$config['smtp_crypto'] = _SMTP_AUTH_MODE;
} //print_r($config);exit;
