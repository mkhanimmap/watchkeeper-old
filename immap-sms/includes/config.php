<?php
session_start();
ob_start();

$connstr = "host=54.235.150.26 port=9191 dbname=watchkeeper1 user=watchkeeper password=w@tchk33p3r";

define("CONNSTR",$connstr);
define('FULL_PATH',"http://linux.oasiswebservice.org/watchkeepernew/immap-sms/");


define('ROOT',"http://linux.oasiswebservice.org/watchkeepernew/immap-sms/");
define("LIMIT",15);

define("TBLADMIN","sms_admin");
define("TBLGROUP","sms_group");
define("TBLSUBGROUP","sms_subgroup");
define("TBLORG","sms_organization");
define("TBLCON","sms_country");
define("TBLUSER","sms_user");
define("TBLMSG","sms_message");
define("TBLOB","sms_outbound");

//USA Eastern Time Zone
putenv("TZ=US/Eastern");

error_reporting(E_ALL);


?>