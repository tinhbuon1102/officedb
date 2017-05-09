<?php
define('IS_LIVECHAT', true);
include_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-config.php');

return array (
  'dbHost' => DB_HOST,
  'dbPort' => '3306',
  'dbUser' => DB_USER,
  'dbPassword' => DB_PASSWORD,
  'dbName' => DB_NAME,
  'superUser' => 'admin',
  'superPass' => 'envyme1122',
  'services' => 
  array (
    'mailer' => 
    array (
      'smtp' => '',
      'smtpSecure' => 'ssl',
      'smtpHost' => '',
      'smtpPort' => '465',
      'smtpUser' => '',
      'smtpPass' => '',
    ),
  ),
  'appSettings' => 
  array (
    'contactMail' => 'kyoko@heart-hunger.com',
  ),
);

?>