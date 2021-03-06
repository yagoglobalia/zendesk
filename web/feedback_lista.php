<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
/**
 * PHP Grid Component
 *
 * @author Abu Ghufran <gridphp@gmail.com> - http://www.phpgrid.org
 * @version 2.0.0
 * @license: see license.txt included in package
 */

include_once("phpgrid/config.php");

include("phpgrid/lib/inc/jqgrid_dist.php");

// Database config file to be passed in phpgrid constructor
$db_conf = array( 	
					"type" 		=> PHPGRID_DBTYPE, 
					"server" 	=> PHPGRID_DBHOST,
					"user" 		=> PHPGRID_DBUSER,
					"password" 	=> PHPGRID_DBPASS,
					"database" 	=> PHPGRID_DBNAME
				);

$g = new jqgrid($db_conf);

$grid["caption"] = "Sample Grid";
$g->set_options($grid);



$g->select_command = "SELECT locata, email, telefono, update_sent, trustpilot, fechaviaje from reservas where update_sent = 0 or trustpilot = 0";

$g->table = "reservas";

$col = array();
$col["title"] = "Localizador"; // caption of column, can use HTML tags too
$col["name"] = "locata"; // grid column name, same as db field or alias from sql
$col["width"] = "40"; // width on grid
$col["editable"] = false;

$cols[] = $col;   

$col = array();
$col["title"] = "Email"; // caption of column, can use HTML tags too
$col["name"] = "email"; // grid column name, same as db field or alias from sql
$col["width"] = "40"; // width on grid
$col["editable"] = false;

$cols[] = $col; 

$col = array();
$col["title"] = "Teléfono"; // caption of column, can use HTML tags too
$col["name"] = "telefono"; // grid column name, same as db field or alias from sql
$col["width"] = "20"; // width on grid
$col["editable"] = false;

$cols[] = $col; 

$col = array();
$col["title"] = "Viaje"; // caption of column, can use HTML tags too
$col["name"] = "fechaviaje"; // grid column name, same as db field or alias from sql
$col["width"] = "20"; // width on grid
$col["editable"] = false;

$cols[] = $col; 

$col = array();
$col["title"] = "Solicitado?"; // caption of column, can use HTML tags too
$col["name"] = "update_sent"; // grid column name, same as db field or alias from sql
$col["width"] = "8"; // width on grid
$col["editable"] = true;
$col["formatter"] = "boolean";
$cols[] = $col;   

$col = array();
$col["title"] = "Trustpilot"; // caption of column, can use HTML tags too
$col["name"] = "update_sent"; // grid column name, same as db field or alias from sql
$col["width"] = "8"; // width on grid
$col["editable"] = true;
$col["formatter"] = "boolean";
$cols[] = $col;  


// pass the cooked columns to grid
$g->set_columns($cols);

//$g -> set_query_filter("ciaida='AIR_EUROPA' OR ciavuelta='AIR_EUROPA'");


$g->set_actions(array( 
                        "add"=>false,
                        "edit"=>true,
                        "clone"=>false,
                        "bulkedit"=>false,                           
                        "delete"=>false,
                        "view"=>true,
                        "rowactions"=>true,
                        "export"=>true,
                        "autofilter" => true,
                        "search" => "simple",
                        "inlineadd" => true,
                        "showhidecolumns" => true
                    ) 
                );



$out = $g->render("list1");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<link rel="stylesheet" type="text/css" media="screen" href="phpgrid/lib/js/themes/redmond/jquery-ui.custom.css"></link>	
	<link rel="stylesheet" type="text/css" media="screen" href="phpgrid/lib/js/jqgrid/css/ui.jqgrid.css"></link>	
 
	<script src="phpgrid/lib/js/jquery.min.js" type="text/javascript"></script>
	<script src="phpgrid/lib/js/jqgrid/js/i18n/grid.locale-en.js" type="text/javascript"></script>
	<script src="phpgrid/lib/js/jqgrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>	
	<script src="phpgrid/lib/js/themes/jquery-ui.custom.min.js" type="text/javascript"></script>
</head>
<body>
	<div>
	<?php echo $out?>
	</div>
</body>
</html>
