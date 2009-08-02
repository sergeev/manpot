<?php
error_reporting (E_ALL); //Необходимо для вывода всех ошибок
session_start();
define('__bbug', 1);

include('includes/main.php');
include('config.php');
include('includes/db.php');
include('includes/bug.php');
include('includes/user.php');
include('includes/todo.php');

define('REGISTERED', $config["registered"]);
define('SITE_NAME', "$config[site_name]");
$mydb = new Database($db['host'], $db['user'], $db['pass'], $db['db'], '', 20);
$mydb->NewConnection();
$mydb->query("SET NAMES utf8");
$main = new Main($mydb);    
$main->headStart();

?>

	
	<div class="clear"></div>
	<div align="center">
		<table width="1000" align="center" cellspacing="0" cellpadding="0">
		<tr>
			<td width="12"><img src="./images/metalborder-top-left.gif" height="12" width="12" alt=""/></td>
			<td style="background:url('./images/metalborder-top.gif');"></td>
			<td width="12"><img src="./images/metalborder-top-right.gif" height="12" width="12" alt=""/></td>
		</tr>
		<tr>
			<td style="background:url('./images/metalborder-left.gif');"></td>
			<td id="mainbody"><?php $main->body(); ?></td>
			<td style="background:url('./images/metalborder-right.gif');"></td>
		</tr>
		<tr>
			<td><img src="./images/metalborder-bot-left.gif" height="11" width="12" alt=""/></td>
			<td style="background:url('./images/metalborder-bot.gif');"></td>
			<td><img src="./images/metalborder-bot-right.gif" height="11" width="12" alt=""/></td>
		</tr>
		</table>	
	</div>
<?php

  $main->footStart();  
?>