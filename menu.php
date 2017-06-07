<?php 
session_start();
ini_set('session.gc_maxlifetime', 360*60);
setlocale(LC_ALL,'nb_NO.utf8');


if(!isset($_SESSION['userID'])) { // check if user is logged in
header("Location:login.php");
}

if($_GET['project']=="all") {
session_unregister('project'); 
}

if(isset($_COOKIE['style'])) {
		$_SESSION['style'] = $_COOKIE['style'];
	} else if(!isset($_SESSION['style'])) {
		$_SESSION['style'] = "smoothness";
	}	

if($_GET['style']!="") {
	$_SESSION['style'] = $_GET['style'];
	setcookie('style',$_GET['style']);
	}

if(!isset($_SESSION['lang'])) {
	$_SESSION['lang'] = "nb_NO";
	} 

include_once "lang/".$_SESSION['lang'].".php";
include_once "modules/system/db.php";

if($_GET['project']!="") {
$_SESSION['project'] = $_GET['project']; 

$queryPN = "SELECT projectName, projectFirstSalesStage, projectFirstOrderStage from ".$projects." WHERE projectID=:project";

try {
    $stmt = $pdo->prepare($queryPN);
    $stmt->bindParam(':project', $_GET['project']);
    $stmt->execute();
    $RowPN = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Data was not fetched, because: ".$e->getMessage();
}


$_SESSION['projectName'] = $RowPN['projectName'];
$_SESSION['projectFirstSalesStage'] = $RowPN['projectFirstSalesStage'];
$_SESSION['projectFirstOrderStage'] = $RowPN['projectFirstOrderStage'];
} 

if($_GET['project']=="all" || $_SESSION['project']=="")  {
$_SESSION['projectName'] = $LANG['all_projects'];
}


?>
 <!DOCTYPE HTML>
<html>
	<head>  
  <meta charset="UTF-8">
  <script type="text/javascript" language="javascript" src="lib/datatables/media/js/jquery.js"></script>
 		<script type="text/javascript" language="javascript" src="lib/datatables/media/js/jquery.dataTables.js"></script>
		<script type="text/javascript" src="lib/jquery/js/jquery-ui-1.8.5.custom.min.js"></script>
	
	 <script src="lib/jquery/development-bundle/ui/jquery.ui.core.js"></script>
	 <script src="lib/jquery/development-bundle/ui/jquery.ui.widget.js"></script>
	 <script src="lib/jquery/development-bundle/ui/jquery.ui.datepicker.js"></script>
	 <script src="lib/jquery/development-bundle/ui/i18n/jquery.ui.datepicker-<?php print strtolower(substr($_SESSION['lang'],3,2)); ?>.js"></script>
	 <script src="lib/jquery/jquery.alerts.js"></script>	

  <script language="javascript" type="text/javascript" src="lib/jquery/jquery.jqplot.js"></script> 
  <script language="javascript" type="text/javascript" src="lib/jquery/plugins/jqplot.barRenderer.js"></script>
  <script language="javascript" type="text/javascript" src="lib/jquery/plugins/jqplot.pieRenderer.js"></script>
  <script language="javascript" type="text/javascript" src="lib/jquery/plugins/jqplot.categoryAxisRenderer.js"></script>
  <script language="javascript" type="text/javascript" src="lib/jquery/plugins/jqplot.highlighter.js"></script>
  <script language="javascript" type="text/javascript" src="lib/jquery/plugins/jqplot.pointLabels.js"></script>	
	
	 <script language="javascript" type="text/javascript" src="lib/indexFunctions.js"></script> 
  	<script language="javascript" type="text/javascript" src="modules/orders/js/orderFunctions.js"></script> 
  	<script language="javascript" type="text/javascript" src="modules/reports/js/reportFunctions.js"></script> 
	 <script language="javascript" type="text/javascript" src="modules/invoices/js/invoiceFunctions.js"></script> 


	 <link rel="stylesheet" href="lib/jquery/development-bundle/themes/<?php print $_SESSION['style'] ?>/jquery.ui.all.css">
		<link rel="stylesheet" href="lib/jquery/jquery.alerts.css">
	<link rel="shortcut icon" href="images/stevia.ico" type="image/x-icon" />

<style type="text/css" title="currentStyle">
			@import "css/page_styles.css";
			@import "css/table_styles.css";
			@import "css/table_jui.css";
</style>

		 <link href="css/menu_styles.css" type="text/css" rel="stylesheet">


<script type="text/javascript">
var lang = "<?php print strtolower(substr($_SESSION['lang'],3,2)); ?>"; // to be used in indexFunctions.js
// Table Language variables
var lLengthMenu = "<?php print $LANG['LengthMenu']; ?>";
var lZeroRecords ="<?php print $LANG['ZeroRecords']; ?>";
var lInfo = "<?php print $LANG['Info']; ?>";
var lInfoEmpty = "<?php print $LANG['InfoEmpty']; ?>";
var lInfoFiltered ="<?php print $LANG['InfoFiltered']; ?>";
var lSearch = "<?php print $LANG['search']; ?>";
var lFirst = "<?php print $LANG['First']; ?>";
var lPrevious = "<?php print $LANG['Previous']; ?>";
var lNext = "<?php print $LANG['Next']; ?>";
var lLast =  "<?php print $LANG['Last']; ?>";
</script>
<!--  Show Logo -->
<a href="index.php" ><img src="images/logo/logo1.png" style="border:0px;vertical-align:top;margin-left:15px;margin-bottom:0px;margin-top:5px" alt="" ></a>
<!--  End Logo -->


<!--  Start Menu items -->

<ul id="menu" class="ui-widget-header ui-corner-all">
			<li><a href="index.php"><?php print $LANG['home']; ?></a></li>
<?php 
if(strstr($_SESSION['userProjects'], ",") || isset($_SESSION['admin']) ) {
// User is  admin or has more than one project, so show projects menu	
?>	
			<li><a href="index.php"><?php print $LANG['projects']; ?></a>

<!--  List Projects -->				
<ul>
<?php
if(isset($_SESSION['admin'])) {   // check for admin rights
?>
<li><a href="index.php?project=all"><img src="images/folder_blue_16.png" style="border:0px;vertical-align:middle;" alt="" />&nbsp;<?php print $LANG['all_projects'];?></a></li>
<?php
}
?>	

<?php // Get list of user's projects

if(isset($_SESSION['admin'])) { // list all projects for admin
	$userProjects = "";
} else { // list user's projects			
	if(strstr($_SESSION['userProjects'], ",")) {
	$userProjects = "WHERE projectID = ".str_replace(",", " OR projectID = ", $_SESSION['userProjects']);
	} else {
	$userProjects = "WHERE projectID = ".$_SESSION['userProjects'];		
	}
} 

$queryProj = "SELECT projectID, projectName FROM ".$projects." ".$userProjects." ORDER by projectName";

try {
    $resultProj = $pdo->query($queryProj);
} catch (PDOException $e) {
    echo "Data was not fetched, because: ".$e->getMessage();
}

foreach($resultProj as $rowProj) {

?>
<li><a href="index.php?project=<?php print $rowProj['projectID']; ?>"><img src="images/folder_blue_16.png" style="border:0px;vertical-align:middle;" alt="" />&nbsp;<?php print $rowProj['projectName'];?></a></li>
<?php
}
?>
</ul>
<?php 
} // end projects menu
?>
			</li>
			<li><a href="index.php"><?php print $LANG['sales_system']; ?></a>
				<ul>

<!--  Show Sales Stages -->				
<?php 				
 // Get relevant sales stages for project
if($_SESSION['project']!="" && $_SESSION['project']!="all" && $_SESSION['project']!=0 ) { 

$queryStages = "SELECT projectSalesStages FROM ".$projects." WHERE projectID=".$_SESSION['project'];
		
try {
    $resultStages = $pdo->query($queryStages);
} catch (PDOException $e) {
    echo "Data was not fetched, because: ".$e->getMessage();
}

foreach($resultStages as $rowStages) {

$stageStr = "=".str_replace(","," OR salesStageID=",$rowStages['projectSalesStages'])."";
}
	
$querys = "SELECT * FROM ".$salesstages." WHERE salesStageID ".$stageStr." ORDER by salesStageID";

} else { // Get all relevant sales stages for the role of the user 

$querys = "SELECT * FROM ".$salesstages." ORDER by salesStageID";
}

try {
    $results = $pdo->query($querys);
} catch (PDOException $e) {
    echo "Data was not fetched, because: ".$e->getMessage();
}

foreach($results as $Rows) {

?>
<li><a href="show_companies.php?status=<?php print $Rows['salesStageID']; ?>"><img src="images/sales_icons/<?php print str_replace('32','16',$Rows['salesStageIcon']);?>" style="border:0px;vertical-align:middle;" alt="" />&nbsp;<?php print $Rows['salesStageName'];?></a></li>
<?php
}
?>

<!--  Show Customers with orders  -->				

<li><a href="show_companies.php?status=<?php print $LANG['customer'];?>"><img src="images/sales_icons/folder_yellow_16.png" style="border:0px;vertical-align:middle;" alt="" />&nbsp;<?php print $LANG['customers'];?></a></li>



<!--  Show Callinglists -->
					<li><a href="show_callinglists.php"><img src="images/folder_blue_16.png" style="border:0px;vertical-align:middle;" alt="" />&nbsp;<?php print $LANG['calling_lists'];?></a></li>
				</ul>
			</li>

<?php
if(isset($_SESSION['orderModule']) || isset($_SESSION['admin']) ) {
// Show Order Module if users has rights
?>	
			<li><a href="orders_main.php"><?php print $LANG['order_system']; ?></a>

				<ul>

<?php

if($_SESSION['project']!="" && $_SESSION['project']!="all" && $_SESSION['project']!=0 ) {  // get relevant order stages for project


$queryStages = "SELECT projectOrderStages FROM ".$projects." WHERE projectID=".$_SESSION['project'];
		
try {
     $resultStages = $pdo->query($queryStages);
 } catch (PDOException $e) {
     echo "Data was not fetched, because: ".$e->getMessage();
 }   

foreach($resultStages as $rowStages) {

$stageStr = "=".str_replace(","," OR orderStatusID=",$rowStages['projectOrderStages'])."";
}


	
$querys = "SELECT * FROM ".$orderstatus." WHERE orderStatusID ".$stageStr." ORDER by orderStatusID";
} else {
	
$querys = "SELECT * FROM ".$orderstatus." ORDER by orderStatusID";
}

try {
     $results = $pdo->query($querys);
 } catch (PDOException $e) {
     echo "Data was not fetched, because: ".$e->getMessage();
 }   
 
foreach($results as $Rows) {

         
echo'<li><a href="show_orders.php?status='.$Rows['orderStatusID'].'"><img src="images/folder_blue_16.png" style="border:0px;vertical-align:middle;" alt="" />&nbsp;'.$Rows['orderStatusName'].'</a></li>';

}
?>
				
			</ul>
			</li>
			
<?php 
} // end order module
?>			
<!-- invoice system - kommer
<li><a href="index.php?#tabs-3"><?php print $LANG['invoice_system']; ?></a>
 -->
<li><a href="reports_main.php"><?php print $LANG['reports']; ?></a>


<?php
if(isset($_SESSION['admin'])) {   // check for admin rights
?>
	<li><a href="admin_main.php">Admin</a></li>
<?php
}
?>	
	<li style="border-radius:7px;-moz-border-radius:7px;"><a href="#"><?php print $LANG['links'];?></a>
						<ul>
<?php
// Get and print Links
$queryLinks = "SELECT linkName, linkURL from ".$links." WHERE linkPrivate=0 or (linkPrivate=1 && linkOwnerID=".$_SESSION['userID'].")";

try {
     $resultLinks = $pdo->query($queryLinks);
 } catch (PDOException $e) {
     echo "Data was not fetched, because: ".$e->getMessage();
 }

foreach($resultLinks as $RowLink) {
   print "<li><a href=\"http://".$RowLink['linkURL']."\" target=\"_blank\">".$RowLink['linkName']."</a></li>";
}					
?>
						</ul>
	</li>			

	<li>
	<select id="style" onchange=document.location.href='index.php?style='+this.value>
	
<option id="ui-darkness" value="ui-darkness">Darkness</option>	
	<option id="blue_theme" value="blue_theme">Blue theme</option>		
	<option id="smoothness" value="smoothness">Smoothness</option>	
	<option id="humanity" value="humanity">Humanity</option>	
	<option id="sunny" value="sunny">Sunny</option>	
		<option id="vader" value="vader">Vader</option>	
	<option id="dark-hive" value="dark-hive">Dark-Hive</option>	
<option id="eggplant" value="eggplant">Eggplant</option>		
<option id="le-frog" value="le-frog">Le Frog</option>		
<option id="mint-choc" value="mint-choc">Mint choc</option>	
<option id="flick" value="flick">Flick</option>	
<option id="overcast" value="overcast">Overcast</option>	
<option id="cupertino" value="cupertino">Cupertino</option>		
<option id="blitzer" value="blitzer">Blitzer</option>	
<option id="south-street" value="south-street">South Street</option>	
<option id="pepper-grinder" value="pepper-grinder">Pepper Grinder</option>		
	</select>	
	<script type="text/javascript" >
document.getElementById('<?php print $_SESSION['style'];?>').selected = true;
	
</script>
	</li> 

</ul>
<!--  Show Punch in/out --> 
<a href="#" onclick="JavaScript:punch('In','<?php print $_SESSION['userID'];?>');"><img src="images/punchin.png" style="border:0;vertical-align:top;margin-top:5px" alt="<?php print $LANG['punchin'];?>" title="<?php print $LANG['punchin'];?>"></a>
<a href="#" onclick="JavaScript:punch('Out','<?php print $_SESSION['userID'];?>');"><img src="images/punchout.png" style="border:0;vertical-align:top;margin-top:5px" alt="<?php print $LANG['punchout'];?>" title="<?php print $LANG['punchout'];?>"></a>
<!--  End Punch in/out -->
<span  style="float:right;padding:2px;text-align:right;">
<?php print $_SESSION['fullName']." &nbsp <a href=\"login.php?logout=yes\">Log out</a> &nbsp;"; ?>
<div id="punchArea" style="padding-top:0px;padding-bottom:0px;text-align:center">
<?php
if(strstr($_SESSION['punched'],"in")) {
		print "<span style=\"color:blue\">".$LANG['punched']." ".$_SESSION['punched']."</span>"; 
	}

if(strstr($_SESSION['punched'],"out")) {
		print "<span style=\"color:red\">".$LANG['punched']." ".$_SESSION['punched']."</span>"; 
	}
?>
</div>
</span>
<span id="statusBar" style="float:right; width:70px; padding:0px;text-align:center; color:red"></span>
