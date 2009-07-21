<?php
 define('__bbug', 1);
include('includes/main.php');
include('config.php');
include('includes/db.php');

include('includes/bug.php');
include('includes/user.php');
$mydb = new Database($db['host'], $db['user'], $db['pass'], $db['db'], '', 20);
   $mydb->NewConnection();
$main = new Main($mydb);
$user = new User();
//print_r($_POST);
// test
$userName = $_POST["username"];
$passWord = $_POST["password"];
               
                $adminCheck = $mydb->first("SELECT `gmlevel` FROM `account` WHERE `username`='$userName' AND `sha_pass_hash`='".SHA1(strtoupper($username.':'.$password))."';");
                if($adminCheck === "3")
                    $isadmin = 1;
                elseif(!$adminCheck || $adminCheck== "")
                    $isadmin = 0;
                else
                    $isadmin = 0;
                    
                    //echo $adminCheck;
                
if(isset($_POST["assignto"]) && $isadmin == 1){
 $assignto = $mydb->clean($_POST["assignto"], '', '');
 $assignedname = $mydb->first("SELECT username FROM account WHERE id='$assignto'");
 $tickid = $mydb->clean($_POST["tickid"], '', '');
 $mydb->query_update('list', array('assigned' => $assignto), "id='$tickid'"); 
// echo $isadmin;
 echo $assignedname;  
 
}

if(isset($_POST["titlechange"]) && $isadmin == 1){
	$tickid = $mydb->clean($_POST["tickid"], '', '');
	$mydb->query_update("list", array('title' => $_POST["titlechange"]), "id='$tickid'");
	echo "Title changed.";
}

if(isset($_POST["closeticket"]) && $isadmin == 1){
 $closeticket = $mydb->clean($_POST["tickid"], '', '');
 $mydb->query_update('list', array('status' => 0, 'finished' => time() ), "id='$closeticket'");
 echo "Ok";   
      }
     
if(isset($_POST["openticket"]) && $isadmin == 1){
 $closeticket = $mydb->clean($_POST["tickid"], '', '');
 $mydb->query_update('list', array('status' => 1, 'finished' => ''), "id='$closeticket'");
 echo "Ok";   
}
if(isset($_POST["changepri"]) && $isadmin == 1){
 $changepri = $mydb->clean($_POST["changepri"], '', '');
 $id = $mydb->clean($_POST["id"], '', '');
 $mydb->query_update('list', array('priority' => $changepri), "id='$id'");
 //print_r($_POST);   
}

// adds to do items
if(isset($_POST["addtodo"])){
	$id = $mydb->clean($_POST["id"], '', '');
	$mydb->query_insert('todo_list', array('id' => 'null', 'tid' => $_POST['id'], 'content' => $_POST['item'], 'status' => 0) );
	echo $mydb->lastID();
}
if(isset($_POST["markfinish"])){
	$mydb->query_update('todo_list', array('status' => 1), "id='".$mydb->clean($_POST["id"], '', '')."'");
}


//print_r($_POST);
?>
