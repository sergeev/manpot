<?php
  defined('__bbug') or die();
  // security check
                $userName = $_SESSION["userName"];
                $passWord = $_SESSION["passWord"];
                $adminCheck = $this->db->first("SELECT `gmlevel` FROM `account` WHERE `username`='$userName' AND `sha_pass_hash`='".SHA1(strtoupper($userName.':'.$passWord))."';");
                if($adminCheck != 3 || $adminCheck == "")
                die("Forbidden");
  // end
?>
<table width="100%" align="center" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" id="adminLeft" width="150">
			<div id="headings-small">���������</div>
   			<a href="?admin&adm=addproject">�������� ���������</a>
   			<a href="?admin&adm=listprojects">������ ���������</a>
			<div id="headings-small">������������</div>
   			<a href="?admin&adm=listusers">������ ������������� </a>
		</td>
		
		
		<td valign="top" id="adminContent">
		<?php 
		if(isset($_GET["adm"]))
			$adm = $_GET["adm"];
		else
			$adm = "";
        if($adm == "listusers"){
            
            if(isset($_GET["delete"])){
              $uid = (int)$_GET["delete"];
              $this->db->del('users', "id='$uid'", 1); 
              $this->message("<center><h3>������������ ������.</h3></center>");  
            }
            ?>
              <h3>������ �������������</h3>
              <table width="550" border="0" cellspacing="2" align="center">
                      <tr style='font-weight: bold;'>
                        <td>ID</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>������� �������</td>
                      </tr>
              <?php
                $this->db->query("SELECT * FROM `account` ORDER BY `id` ASC");
                while($r = $this->db->fetch_array()){
                 ?>
                   <tr>
                        <td><? echo $r["id"];?></td>
                        <td><? echo $r["username"];?></td>
                        <td><? echo $r["email"];?></td>
                        <td><? echo $r["gmlevel"];?></td>
                      </tr>
                 <?php   
                }
              
              ?>
              </table>
            <?php
        }elseif($adm == "addproject"){
            if(isset($_POST["add_project"])){
                // name mini description
                $bugData = array('id' => 'null', 'name' => $_POST["name"], 'mini' => $_POST["mini"], 'description' => $_POST["description"]);
                $this->db->query_insert('projects', $bugData);
                $this->message("<center><h3>��������� ���������.</h3></center>");
            }
            ?>
              <h3>�������� ���������</h3>
              <form name="" method="POST">
              <table width="400" border="0" cellspacing="2" align="center">
                <tr>
                     <td valign="top">��������:</td>
                     <td valign="top"><input name="name" id="name" onclick="this.form.name.select();" class="input" value="{�����}" /></td>
                </tr>
                 <tr>
                     <td valign="top">������:</td>
                     <td valign="top"><input name="mini" class="input"  value="1.0" /> <br /></td>
                </tr>
                
                <tr>
                     <td valign="top">�������� ��������:</td>
                     <td valign="top"><textarea class="textinput" name="description"></textarea></td>
                </tr>
                <tr> <td colspan="2" align="center"><input type="submit" style='width: 200px;' name="add_project" onclick="$('#working').fadeIn();" value="+ �������� ���������"></td></tr>
              </table>    
              </form>                                  
             <div id="working"><img src="/loader.gif" id="loader" /> <b>� ��������...</b></div> 
            <?php
        }
        
    elseif($adm == "listprojects"){
    	if(isset($_POST["projid"])){
    		$this->db->query_update('projects', array('client_exec' => $_POST["client_exec"], 'github' => $_POST["github"]), 'id="'.$_POST["projid"].'"');
    		$this->message("Client Exec URL updated.");
    	}
     ?>
       <h3>���������</h3>
       <table width="500" align="center" cellspacing="2" cellpadding="2" border="0">
       <tr>
       <td><b>���������</b></td>
       <td><b>������</b></td>
       <td><b>�����</b></td>
       </tr>
       <?
       if(isset($_GET["delete"])){
        $delid = (int)$_GET["delete"];
        $this->db->del("projects", "id='$delid'", 1);
        $this->db->del("list", "project='$delid'");
        $this->message("Project deleted.");
       }
       $this->db->query("SELECT * FROM projects ORDER BY name, mini ASC");
       while($r = $this->db->fetch_array()){
        ?>
        <form name="" method="POST" action="">
        <input type="hidden" name="projid" value="<?php echo $r["id"]; ?>" />
        <?php
        echo "<tr>";
        echo "<td>$r[name]</td>";
        echo "<td>$r[mini]</td>";
        echo "<td><a href='?admin&adm=listprojects&delete=$r[id]'>�������</a></td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td colspan='3'>";
        echo "</td></tr>";
        echo "</form>";
       }   
        
       ?></table><?php
    }
    ?>
		</td>
	</tr> </table>
	<div class="clear"></div>