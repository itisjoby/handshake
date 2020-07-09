<?php

/**
 * Database Configuration Page
 * @date 09-05-2019
 * @version 1.0
 * @copyright 2019, Jobys creative lab.
 */

################################################################################
# Database Settings

# [CONSTANT VAR] Database server name/ip 
define("_DB_HOST", "JOBYS-LAPTOP\SQLEXPRESS");

# [CONSTANT VAR] Database name 
define("_DB_NAME", "HANDSHAKE_2019");

#[CONSTANT VAR] DB server username
define("_DB_USER", "jaimy");

# [CONSTANT VAR] DB server password
define("_DB_PASSWORD", "sa#123");

# [CONSTANT VAR] DB Authentication Mode (SQL/WINDOWS)
define("AUTHENTICATION_MODE", "SQL");

# [CONSTANT VAR] Defines whether to display errors occurred in DB queries.
define('_DEBUG_DB_ERRORS',true);