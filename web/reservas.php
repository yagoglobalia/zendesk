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

//$g->table = "reservas";

$g->select_command = "SELECT locata, vueloidar, vuelovueltar, vueloidae, vuelovueltae from reservas where ciaida = 'AIR_EUROPA' or ciavuelta = 'ciavuelta'";

$col = array();
$col["title"] = "Localizador"; // caption of column, can use HTML tags too
$col["name"] = "locata"; // grid column name, same as db field or alias from sql
$col["width"] = "40"; // width on grid
$col["editable"] = false;
$cols[] = $col;   

$col = array();
$col["title"] = "Coste Vuelo Ida AEA"; // caption of column, can use HTML tags too
$col["name"] = "vueloidar"; // grid column name, same as db field or alias from sql
$col["width"] = "40"; // width on grid
$col["editable"] = false;
$cols[] = $col;   

$col = array();
$col["title"] = "Coste Vuelo vuelta AEA"; // caption of column, can use HTML tags too
$col["name"] = "vuelovueltar"; // grid column name, same as db field or alias from sql
$col["width"] = "40"; // width on grid
$col["editable"] = false;
$cols[] = $col;   

$col = array();
$col["title"] = "Coste Vuelo Ida Económico"; // caption of column, can use HTML tags too
$col["name"] = "vueloidae"; // grid column name, same as db field or alias from sql
$col["width"] = "40"; // width on grid
$col["editable"] = true;
$cols[] = $col;   

$col = array();
$col["title"] = "Coste Vuelo Vuelta Económico"; // caption of column, can use HTML tags too
$col["name"] = "vuelovueltae"; // grid column name, same as db field or alias from sql
$col["width"] = "40"; // width on grid
$col["editable"] = true;
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
