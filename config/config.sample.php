<?php
/**
 * This file is used to en/disable which modules of EasyMVC Core are
 * going to be used in the project
 *
 * @version 1.1.2
 */

/**
 * Set this to true of you are going to use a database in your project
 * You can configure your database(s) in the config/database.php file
 * Use config/database.local.php for you local database (test server)
 */
define('USE_DATABASE', false);

/**
 * Set USE_LOGIN to true if you want to use the EasyMVC login system
 * Set USE_EMAIL_LOGIN to true if you want to use the E-mail address to login with
 *                     or to false if you want to use an Username to login with
 */
define('USE_LOGIN', false);
define('USE_EMAIL_LOGIN', false);

/**
 * Set this to true is you are planning to get information from a WebApi
 */
define('USE_HTTP_REQUEST', false);

/**
 * Set this to true if you want to use the EasyMVC Menu system
 */
define('USE_MENU', false);

/**
 * Set USE_EMAIL to true if you want to use the EasyMVC E-mail system
 * Set USE_SMTP to true if you want to use your personal SMTP server instead of the one used by your hosting server
 */
define('USE_EMAIL', false);
define('USE_SMTP', false);

// Your SMTP Configuration
define('EMAIL_HOST', 'smtp.server.com');
define('EMAIL_FROM', 'your@email.address');
define('EMAIL_BCC', 'bcc@email.address'); // Set this to null is you don't want to receive copies of the e-mails send
define('EMAIL_USERNAME', 'username_login');
define('EMAIL_PASSWORD', 'password_login');
define('EMAIL_SECURITY', 'ssl');