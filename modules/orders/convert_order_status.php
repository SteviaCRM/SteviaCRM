<?php
session_start();
if(!isset($_SESSION['userID'])) { // check if user is logged in
header("Location: ../../index.php");
}


require_once "../system/db.php";
require_once "../../lang/".$_SESSION['lang'].".php";

$orderID = $_GET['orderID'];
$orderStatusID = $_GET['orderStatusID'];


$query = "UPDATE ".$orders." SET orderStatusID='".$orderStatusID."' WHERE orderID=".$orderID;
if (!$Result= mysql_db_query($DBName, $query, $Link)) {
           	print "No database connection <br>".mysql_error();
        } else {
				print $LANG['order_status_changed'];
		  }

