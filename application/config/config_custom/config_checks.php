<?php
$config['check_config_file_version'] = '1.0';

if(_LIVE_SERVER===true) {
	error_reporting(0);
	ini_set('display_errors', 0);
}
else {
	error_reporting(-1);
	ini_set('display_errors', 1);
}

if(_RESTRICT_URL===TRUE && (!isset($_SERVER['HTTP_HOST']) || !isset($_SERVER['SCRIPT_NAME']) || _BASE_URL!=(_PROTOCOL.'://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']))){
	$err_code	=	'URL_ERR';
}
else if(_DEBUG_MODE===TRUE) {
	$err_code	=	'DEBUG_MODE';
}

if(!empty($err_code)) {
?>
	<div>
		<center>
			<font color="red">
<?php
				switch($err_code) {
					case 'URL_ERR'		:	echo 'The URL through which you are accessing the system is not an allowed one. <br/>Please use the provided URL or contact the administrator.';
					break;
					case 'DEBUG_MODE'	:	echo 'System is under maintenance. Please try after sometime.';
					break;
				}
?>
			</font>
		</center>
	</div>
<?php
	exit;	//	Stop the execution if an error occurs.
}
