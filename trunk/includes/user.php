<?php
  defined('__bbug') or die();  
  
  Class User extends Database {
   
   function User(){
     
    //get_parent_class($this);
   // parent::Database($this->host, $this->user, $this->pass, $this->database, '');
   } 
   
   /* user id to name */
   function uidToName($uid){ 
    $x = $this->first("SELECT `username` FROM `account` WHERE id='$uid';");
    if($x == "")
        return "Гость";
    else
        return $x;
   }  
   /* get a users id */
   function getUID(){
     if(isset($_SESSION["userName"]) && isset($_SESSION["passWord"])){
              $userName = $_SESSION["userName"];
              $passWord = $_SESSION["passWord"];
               
                $uid = $this->first("SELECT `id` FROM `account` WHERE `username`='$userName' AND `sha_pass_hash`='".SHA1(strtoupper($userName.':'.$passWord))."';");
                return $uid;
   }else{
    return 0;
   }   
  }
  
  function assigned($var){
      if($var == "0")
        return "Not Assigned";
      else
        return $this->first("SELECT username FROM account WHERE id='".$this->clean($var, '', '')."'");
  }
  
   function loginForm(){
      //print_r($_SESSION);
      if(isset($_GET['logout'])){
        unset($_SESSION["userName"]);
        unset($_SESSION["passWord"]);
        echo "<script>document.location='?';</script>";
      }
       
      
          if(isset($_POST["login"]) && isset($_POST["username"]) && isset($_POST["password"]) ){
          $userName = $this->clean($_POST["username"], '', '');
          $passWord = $this->clean($_POST["password"], '', '');
          
          // see if they exist in the db and if theyre PWs match, otherwise error out.
            $result = $this->first("SELECT count(*) FROM `account` WHERE `username`='$userName' AND `sha_pass_hash`='".SHA1(strtoupper($userName.':'.$passWord))."';");
            if($result == 1){ // success
               $_SESSION["userName"] = $userName;
               $_SESSION["passWord"] = $passWord; 
            }else{
                echo "<script>alert('Неверное имя/пароль');</script>";
            }
          }
          if(isset($_SESSION["userName"]) && isset($_SESSION["passWord"])){
              $userName = $_SESSION["userName"];
              $passWord = $_SESSION["passWord"];
               
                $adminCheck = $this->first("SELECT `gmlevel` FROM `account` WHERE `username`='$userName' AND `sha_pass_hash`='".SHA1(strtoupper($userName.':'.$passWord))."';");
                if($adminCheck == 3)
                    $admin = "<a href='?admin'><small>(admin)</small></a>";
                else
                    $admin = "";
               echo "<div id='loggedin'>$admin Привет, ".$_SESSION["userName"]."! <a href='?logout=1'> <img src='images/logout.png' border='0' style='position: relative; top: 3px;'/>  выйти</a></div>";
          } 
          if(!isset($_SESSION["userName"])){
        ?>
          <form name="" method="POST" action="">
                 <input class="loginForm" style='width: 75px;' id='Lusername' name="username" />
                 <input class="loginForm" style='width: 75px;' id='Lpassword' type="password" name="password"  />
                 <input type="submit" name="login" value="Войти" />
                 <div sytle='position: relative; top: 30px;'><small>Логин <span style='padding-left: 51px;'>Пароль</span>
                 </small></div>
          </form>
        <?php
        }      
  }
     
  /* check admin */
  function adminCheck(){
   if(isset($_SESSION["userName"]) && isset($_SESSION["passWord"])){
              $userName = mysql_escape_string($_SESSION["userName"]);
              $passWord = mysql_escape_string($_SESSION["passWord"]);
               
                $adminCheck = $this->first("SELECT `gmlevel` FROM `account` WHERE `username`='$userName' AND `sha_pass_hash`='".SHA1(strtoupper($userName.':'.$passWord))."';");
                if($adminCheck == 3)
                    return true;
   }else{
    return false;
   }   
  }
  
  
     
  }
?>
