<?php
define('__bbug', 1);
  // installer
  include('config.php');
  include('includes/db.php');
  include('includes/main.php');
  include('includes/user.php');
  $mydb = new Database($db['host'], $db['user'], $db['pass'], $db[db], '', 20);
  $mydb->NewConnection();
  $main = new Main($mydb); 
  ?>
 <html>
        <head> 
        <title>MaNPOT</title>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
        <link type="text/css" href="bug.css" rel="stylesheet">
        <!--[if lt IE 8.]>
        <style type="text/css">
        #cLeft { margin-left: -5px; }
        #cRight { margin-right: -4px; }    
        #topWrapper { margin-left: -5px; }
        </style>>
        <![endif]-->

    <!--[if lt IE 7.]>
    <script defer type="text/javascript" src="js/pngfix.js"></script>
    <![endif]-->                

        <script type="text/javascript" src="js/jquery-1.2.6.min.js"></script>
        <script type="text/javascript" src="js/jquery.wysiwyg.pack.js"></script>
         <script type="text/javascript" src="js/jq-sort.js"></script> 
         

        </head>
        <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"> 
       
<div class="clear"></div>
    <div align="center">
        <div id="topContent" width="90%">
            <img src="images/index_09.gif" id="cLeft" />
            <img src="images/index_11.gif" id="cRight" />        
        </div>
        
        <div id="contentArea" width="91%">
        <h1>��������� MaNPOT...</h1>
        
        <?php
        if($_POST){
            $mydb->query_insert("users", array('id' => null, 'username' => $_POST[username], 'password' => md5($_POST[password]), 'email' => $_POST[email], 'acl' => 0));
            $main->message("������� �������������� ������");
            
            echo "<h2 style='color: red;'>������� ���� ���� �� ��������� �������� �������� �������������� ������� ��������������.</h2>";
        }else{
          $listSQL = "CREATE TABLE `list` (
  `id` int(11) NOT NULL auto_increment,
  `project` int(11) NOT NULL default '0',
  `parent` int(11) NOT NULL default '0',
  `title` varchar(200) NOT NULL default '',
  `report` longtext NOT NULL,
  `status` tinyint(1) NOT NULL default '0',
  `by` int(11) NOT NULL default '0',
  `priority` tinyint(1) NOT NULL default '0',
  `type` tinyint(1) NOT NULL default '0',
  `started` int(11) NOT NULL default '0',
  `finished` int(11) NOT NULL default '0',
  `due` int(11) NOT NULL default '0',
  `assigned` int(11) NOT NULL default '0',
  KEY `id` (`id`,`title`),
  KEY `priority` (`priority`)
) ENGINE=MyISAM DEFAULT CHARSET=UTF8; ";
$mydb->query($listSQL);
if(strlen($mydb->errorno) == 0)
echo "<b>Bug �������������....</b><br/>";

$projectSQL = "
CREATE TABLE `projects` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `mini` varchar(255) NOT NULL default '',
  `description` longtext NOT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `subname` (`mini`)
) ENGINE=MyISAM DEFAULT CHARSET=UTF8; ";
$mydb->query($projectSQL);
if(strlen($mydb->errorno) == 0)
echo "<b>Project �������������....</b><br/>";

$userSQL = "
CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(100) NOT NULL default '',
  `password` varchar(100) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `acl` tinyint(2) NOT NULL default '0',
  UNIQUE KEY `id` (`id`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=UTF8;
";
$mydb->query($userSQL);
if(strlen($mydb->errorno) == 0)
echo "<b>User �������������....</b><br/>";

$todoSQL = "CREATE TABLE `todo_main` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`title` VARCHAR( 100 ) NOT NULL ,
`project` INT( 11 ) NOT NULL ,
INDEX ( `id` )
) ENGINE = MYISAM; ";
$mydb->query($todoSQL);
if(strlen($mydb->errorno) == 0)
echo "<b>ToDo_main �������������....</b><br/>";

$todoLSQL = " CREATE TABLE `todo_list` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`content` TEXT NOT NULL ,
`status` INT( 2 ) NOT NULL ,
INDEX ( `id` )
) ENGINE = MYISAM;";
$mydb->query($todoLSQL);
if(strlen($mydb->errorno) == 0)
echo "<b>ToDo_List �������������....</b><br/>";

$projModSQL = "ALTER TABLE `projects` ADD `client_exec` VARCHAR( 255 ) NOT NULL ;";
$mydb->query($projModSQL);
$mydb->query(" ALTER TABLE `projects` ADD `github` VARCHAR( 255 ) NOT NULL ;");
$mydb->query("ALTER TABLE `list` ADD `attachment` VARCHAR( 100 ) NOT NULL ;");

        
        $main->message("MaNPOT tables created.");
        ?>
        
        
        
        <form name="admin" method="POST" action="" id="admin" style="font-size: 11px;">
        <table width="300" cellspacing="2" cellpadding="2" border="0">
            <tr>
            <td>����� ��������������:</td>
            <td><input name="username" class="installer"></td>
            </tr>
            <tr>
            <td>������ ��������������:</td>
            <td><input name="password" class="installer"></td>
            </tr>
            <tr>
            <td>Email:</td>
            <td><input name="email" class="installer"></td>
            </tr>
            <tr>
                 <td colspan="2" align="center"><input type="submit" name="finish" value="��������� ���������"></td>
                 
            </tr>
        </table>
        </form>
        <?php } ?>
        <div style="clear: both; height: 100px;"></div>
        </div>
        
        
        
        
        <div id="bottomContent" width="90%">
            <img src="images/index_15.gif" id="cLeft"  />
            <img src="images/index_18.gif" id="cRight"  />        
        </div>
        
    </div>
    
    </body>
    </html>