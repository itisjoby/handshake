<?php
/**
 * Mail configuration Page
 * @date 09-05-2019
 * @version 1.0
 * @copyright 2019, Jobys creative lab.
 */

#################################################################################
# SMTP Settings

/**
 * [CONSTANT VAR] Whether to send emails or not (if not send, then the emails will be logged in database)
 * @global string
 */
define("_SEND_MAILS", false);

/**
 * [CONSTANT VAR] The authentication mode of the SMTP email server.
 * '' - Default (ssl), 'tls' - TLS Auth
 * @global string
 */
define("_SMTP_AUTH_MODE", 'tls');

/**
 * [CONSTANT VAR] The SMTP email server host name
 * @global string
 */
define("_SMTP_HOST", "email-smtp.eu-west-1.amazonaws.com");

/**
 * [CONSTANT VAR] The port of the SMTP email server
 * @global string
 */
define("_SMTP_PORT", 587);

/**
 * [CONSTANT VAR] The username using which the SMTP server should authenticate
 * @global string
 */
define("_SMTP_USER_EMAIL", 'AKIAJETZW5MUSRDHB22A');

/**
 * [CONSTANT VAR] The password of the username using which the SMTP server should authenticate
 * @global string
 */
define("_SMTP_USER_PASSWORD", 'Ajhyq0BzbneHBi3qdeT+enLxuvC7MzKcVcHH/ShcLA+u');

/**
 * [CONSTANT VAR] Enable/disable debug mode of the SMTP email server.
 * false - No debug messages (for live server), true = prints debug messages
 * @global boolean
 */
define("_SMTP_DEBUG_MODE", false);
