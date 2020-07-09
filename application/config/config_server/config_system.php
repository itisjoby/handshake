<?php

/**
 * Values Configuration Page
 * @date 09-05-2018
 * @version 1.0
 * @copyright 2018, Jobys creative lab
 */

#################################################################################
# System Settings

# [CONSTANT VAR] Defines whether the current instance is on a production server or not
define("_LIVE_SERVER",FALSE);

# [CONSTANT VAR] Enable for test version
define("_TEST_INSTANCE",FALSE);

# [CONSTANT VAR] Defines whether the profiler needs to be enabled for debugging
define("_ENABLE_PROFILER",FALSE);

# [CONSTANT VAR] Defines whether the JavaScript checks has to be disabled at client browser
define('_DISABLE_JAVASCRIPT_VALIDATIONS',FALSE);

# [CONSTANT VAR] Defines whether the current run is for debugging or not. if in debug mode, public users will see a maintenance message
define('_DEBUG_MODE',FALSE);

# [CONSTANT VAR] Defines the message to be shown to public users while in debug mode
define('_DEBUG_MESSAGE','Site is under maintenance. <br/>Please visit back later.');

#################################################################################
# Log Settings

# [CONSTANT VAR] Defines the maximum number of seconds allowed for query execution
define("_MAX_QUERY_RUN_SECONDS",0.5);

# [CONSTANT VAR] Defines the maximum number of seconds allowed for page execution
define("_MAX_PAGE_RUN_SECONDS",0.5);

# [CONSTANT VAR] Defines the max number of queries allowed in a page
define("_MAX_NUM_QUERIES",10);

################################################################################
# SESSION & COOKIE Settings

# [CONSTANT VAR] Session name
define("_SESSION_NAME", "HANDSHAKE_SESSION");

# [CONSTANT VAR] Session expiring time (in minutes)
define("_SESSION_EXPIRY_MINUTES", 120);

# [CONSTANT VAR] Session IP Matching (to check if the IP address has changed in a session)
define("_SESSION_IP_MATCH", TRUE);

# [CONSTANT VAR] Cookie Name
define("_COOKIE_PREFIX", 'HANDSHAKE_COOKIE');

# [CONSTANT VAR] Cookie Domain (affects SESSION as well)
define("_COOKIE_DOMAIN", '');

# [CONSTANT VAR] Cookie will be set only if HTTPS connection exist
define("_SECURE_COOKIE", (_PROTOCOL=='https'?TRUE:FALSE));

################################################################################
# Cross Site Request Forgery Settings

# [CONSTANT VAR] To set CSRF protection On or Off
define("_CSRF_ENABLED", FALSE);

# [CONSTANT VAR] Token Name for CSRF
define("_CSRF_TOKEN_NAME", 'HANDSHAKE_CSRF_TOKEN');

# [CONSTANT VAR] CSRF Cookie Name
define("_CSRF_COOKIE_NAME", 'HANDSHAKE_CSRF_COOKIE');

# [CONSTANT VAR] Whether to regenerate CSRF token on every request
define("_CSRF_REGENERATE", TRUE);
