<?php
session_start();
if(!isset($_SESSION['userID'])) { // check if user is logged in
header("Location: ../../index.php");
}

include_once "../../lang/".$_SESSION['lang'].".php";
include_once "../system/db.php";

$regNumber = str_replace(" ", "", $_GET['regNumber']);

$query = "SELECT companyName, companyCity from ".$companies."  WHERE regNumber=".$regNumber;

	if (!$Result= mysql_db_query($DBName, $query, $Link)) {
           print "No database connection <br>".mysql_error();
        }

    if(!$Row = mysql_fetch_assoc($Result)) {
		   print "No record found <br>".mysql_error();
        }
  
  	
?>

<h1 class="ui-widget-header ui-corner-all" style="width:100%;padding:3px 3px 3px 10px;font-weight:bold"><?php print $Row['companyName']; ?></h1>
<table class="ui-widget-content ui-corner-all" style="width:600px" >
<tr>
<td><?php print $LANG['org_number'].": ".$regNumber; ?></td>
<td><?php print $LANG['companyCity'].": ".$Row['companyCity']; ?></td>
</tr>
<tr>
<td>
<a href="#" onclick="Javascript:getAccounts('<?php print $regNumber; ?>','<?php print $Row['companyName']; ?>','<?php print $Row['companyCity']; ?>')">
<img src="images/logo-proff.png" alt="" >
</a>
</td>

</tr>


</table>


<div id="AccountArea" style="float:left;"></div>
