<?php
/* -----------------------------------------------------------------------------------
 * DATABASE CREDENTIALS
 */

define("FRAME_DB_HOSTNAME", "localhost");
define("FRAME_USER",        "root");
define("FRAME_PASSWORD",    "123");
define("FRAME_DB_NAME",     "framework_db");


/* -----------------------------------------------------------------------------------
 * OTHER SETTINGS
 */

define("FRAME_SQL_ERROR_TABLE", "_error_sql");

//Having mysqlnd installed results in a near 50% improvement in performance.
define("MYSQLND_INSTALLED", false);