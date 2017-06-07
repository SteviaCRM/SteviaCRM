<?php
include_once "../../lang/".$_SESSION['lang'].".php";
include_once "../system/db.php";

$orgnr = $_GET['regNumber'];
$name = strtolower(str_replace(" ", "-", $_GET['companyName']));
$by = strtolower($_GET['companyCity']);


$handle = fopen("http://www.proff.no/regnskapdetaljerte/".$name."/".$by."/-/".$orgnr."/", "r");

if ($handle) {
    while (!feof($handle)) {
        $buffer = fgets($handle, 4096);
        $doc = $doc.$buffer;
    }
    fclose($handle);
} else {
	print "<br>no handle";
}


$dom = new domDocument;

    /*** load the html into the object ***/
    $dom->loadHTML($doc);

    /*** discard white space ***/
    $dom->preserveWhiteSpace = false;

    /*** the table by its tag name ***/
    $tables = $dom->getElementsByTagName('table');

    /*** get all rows from the table ***/
    $rows = $tables->item(0)->getElementsByTagName('tr');

echo '<h1>'.$s_accounts.'</h1>';    
echo '<table class="ui-widget-content ui-corner-all" style="font-size:0.9em;">';

echo '<tr><td valign="top"><table align="left" style="font-size:1.2em;">';
    /*** loop over the table rows ***/
    $i = 0;
    
    
    foreach ($rows as $row)
    {
        /*** get each column by tag name ***/
        $cols = $row->getElementsByTagName('td');  // get data
        $heads = $row->getElementsByTagName('th');  // get headings
    
        
      if($i==0) {
      	  
echo '<tr class="tablehead">';
echo '<td></td>';

$h=0;
foreach($heads as $head) {
if($head->nodeValue!="Graf") {  // don't include this header
$fieldValue[] = $head->nodeValue;
echo '<td style="width:100px;">'.$head->nodeValue.'</td>';
}
$h++;
}
echo '</tr>';

}

      if($i>0 && $i!=26 && $i!=27 && $i!=31 && $i!=94 && $i!=105 && $i!=113 && $i!=106  && $i<125) {    // include only these rows

			if($i==32 || $i==33 || $i==63 || $i==73 || $i==95 || $i==107 || $i==114 || $i==123) {  // these are headings 
       	echo '<tr class="tablehead">';
			} else {       																								// these are data 
       	echo '<tr class="'.$class_value.'">';
       	}

       	echo '<td>'.$i.'</td>';
       	echo '<td style="width:150px;">'.trim(utf8_decode($heads->item(0)->nodeValue)).'</td>';
			
			if($i == 124) {	
			$start_str = 1;
			} else {
			$start_str = 0;		
			}			
			
			for ($a = $start_str; $a < $cols->length-1; $a++) {	 	
			
			$value = trim(utf8_decode($cols->item($a)->nodeValue));		 		
					 	
		 	if($value=="?"){ 		
				$value="0";		 	
		 		}
		 		
		 	$value = str_replace("fax", "", $value);	
		 	$value = str_replace("<br>", "", $value);	
			$value = str_replace("&nbsp;", "", $value);	

			$value = preg_replace( '/[\x7f-\xff]/', '', $value );
			$value = preg_replace( "'\s+'", '', $value );
			
			
			if($i<62) {
		 	//$value = preg_replace('/[\s\W]+/','',$value);	
		 	}	 	
		 	
		 	
		 			 	
		 	$accValue[$a][] = $value; 
		 	
		 		echo '<input type="hidden" name="'.trim(utf8_decode($heads->item(0)->nodeValue)).'" value="'.$value.'">'."\n";
		 			echo '<td>'.$value.'</td>';    
					
					}  // end listing col 
			echo '</tr>';
						if($class_value == "blank") {
						$class_value = "colored";
						} else {
							$class_value = "blank";
							} 			
						
			
      }
	
		$i++;
    }


echo '</table>';
echo '</td></tr></table>';


?>
