<?php
  defined('__bbug') or die(); 
  class Bugs {
    var $db = 0;
    var $user = 0;
    var $git;
    var $clientexec; 
    function Bugs($dblink){
       $this->db=$dblink; 
       $this->user=new User();
    }
      
    function tableHeader(){
     ?>
     <script>$(document).ready(function() { $('#list').tablesorter(); } ); </script>
     <table width="100%" id="list" class="tablesorter" border="0" align="center" cellpadding="0" cellspacing="0">
      <thead>
      <tr>
              <td class="rankingHeader">#</td>
              <td class="rankingHeader">Тип</td> 
              <td class="rankingHeader" width="45%">Название</td>
              <td class="rankingHeader" width="25">Статус</td>
              <?php /* taken out to reduce clutter <th>By</th> */ ?>
              <td class="rankingHeader">Категория</td>
              <td class="rankingHeader" width="75">Приоритет</td>
              <td class="rankingHeader" width="150">Сообщено</td>
              <td class="rankingHeader">Исправлено</td>
			  <?php if ($this->user->adminCheck()){ ?><td class="rankingHeader">Удалить</td><?php } ?>
              </tr>
      </thead>
      <tbody>
      
     <?php    
    }

     /* take a pid and get its name */
    function ProjectIDtoName($id){
        return $this->db->first("SELECT `name` FROM projects WHERE `id`='$id'");
    }
    
    /* create the bug list */
    function bblist($type=-1){
		if(isset($_POST['deleterer'])){
			$tdid = $this->db->clean($_POST['deleterer'], '', '');
        	$this->db->del("list", "`id`='$tdid'", 1);
        	Main::message("Report deleted.");
        }
        if(isset($_GET["page"]))
        	$page = $_GET["page"];
        else
        	$page = "";
        if(isset($_GET['specialrefiner'])){
         $_SESSION['esql'] = "";
         $srf = $_GET['specialrefiner'];
         if($srf == "open")
             $_SESSION['esql'] = "AND `status`='1'";
         elseif($srf == "closed")
             $_SESSION['esql'] = "AND `status`='0'";
         elseif($srf == "all")
            $_SESSION['esql'] = "";
         elseif(is_numeric($srf))
            $_SESSION['esql'] = "AND `project`='".$this->db->clean($srf, '', 'num')."'";
        }
        
        if(!isset($_SESSION['esql']))
        	$_SESSION['esql'] = "";
        /* Handle paging */
      /*  if( isset($_GET["page"]) )
        	$_GET["page"]=$_GET["page"];
        else
        	$_GET["page"] = 1; */
        if(isset($_GET["page"])){
            $page = ($_GET["page"]);
            $lower = ($page * $this->db->pagenums)-$this->db->pagenums;
            $limit = "LIMIT $lower,".$this->db->pagenums;
        }else $limit = "LIMIT 0,".$this->db->pagenums;
     
     
     if($page == 1) 
        $limit = "LIMIT 0,".$this->db->pagenums;
        if($type == 0)
            $this->db->query("SELECT * FROM list WHERE `type`='0' AND `parent`='0' ".$_SESSION['esql']." ORDER BY `id` DESC $limit;");
        if($type == 1)
            $this->db->query("SELECT * FROM list WHERE `type`='1' AND `parent`='0' ".$_SESSION['esql']." ORDER BY `id` DESC $limit;");
        if($type == -1)
            $this->db->query("SELECT * FROM list WHERE `parent`='0' ".$_SESSION['esql']." ORDER BY `id`  DESC $limit;"); 
       $cssclass = "L1";
      while($r = $this->db->fetch_array()){
          
          if($cssclass == "L1") 
            $cssclass = "L2";
          elseif($cssclass == "L2") 
          $cssclass = "L1";
       ?>   <script>
            function deletetd(id){
            	var c = confirm("Удалить этот список?");
            	if(c)
            		document.getElementById('delete'+id).submit();
            }
          </script> 
         <tr class="<?php echo $cssclass;?>">
        <td align="center"><?php echo $r["id"];?></td>
        <td width="16" align="center"><div style='position: relative;'><img src="<?php echo $this->img($r["type"]);?>" style='' />
        <?php if($r["status"] == 0){ ?><img id=cansel src="images/cancel.png"/><?php } ?></div></td>
        <td><a href="?cmd=view&id=<?php echo $r["id"];?>&open=<?php echo $r["status"];?>"><?php if($r["title"] == "") echo "[Без Названия]"; else echo $r["title"];?></a>
		<?php $comments = $this->db->first("SELECT COUNT(*) FROM list WHERE parent=".$r["id"].";");
		if($comments!=0) {
			echo "<font color=#A9A9A9 size=1>[Комментариев: $comments]</font>";
			}
		?>
		</td>
        <td align="center"><?php if($r["status"] == 1)
                                echo "Открыто";
                                else
                                    echo "Закрыто"; ?></td>
       <?php
        /* <td> Taken out to reduce clutter 
        if($r[by] == 0) echo "Гость";
        else echo $this->user->uidToName($r[by]);
        </td>
        */
        ?>
        <td align="center"><?php echo $this->ProjectIDtoName($r['project']); ?></td>
        <td align="center" class="pri<?php echo $r["priority"];?>" id="<?php echo $r["priority"];?>"><?php echo $this->adminPriHover($r["id"], $r["priority"]);?></td>
        <td align="center"><?php echo $this->the_date($r["started"]);?></td>
        <td><?php
        
        if($r["finished"] == 0)
            echo "Никогда";
        else echo $this->the_date($r["finished"]);
        
        ?></td>
		<?php
		if($this->user->adminCheck()){
              	echo "<td><form name='' style='margin: 0; padding: 0; float: left; ' method='post' action='' id='delete$r[id]'><input type='hidden' name='deleterer' value='$r[id]' /></form><input type='image' src='images/bin_closed.png' onclick='deletetd($r[id]);' name='delete' /></td>";
              }?>
        </tr><?php 
      }
       ?>
         
       </tbody>
       <?php 
       if(REGISTERED == 0 || isset($_SESSION["userName"])){
       $this->quickadd(); } ?>
       </table>
       
     <div style="width: 100%;" width="100%" id="subnav">     
     <?php
     
     
     if($type == 0)
            $this->db->paginate("SELECT * FROM list WHERE `type`='0' AND `parent`='0' ".$_SESSION['esql']." ORDER BY `id` DESC;");
     if($type == 1)
            $this->db->paginate("SELECT * FROM list WHERE `type`='1' AND `parent`='0' ".$_SESSION['esql']." ORDER BY `id` DESC;");
     if($type == -1)
            $this->db->paginate("SELECT * FROM list WHERE `parent`='0' ".$_SESSION['esql']." ORDER BY `id`  DESC;"); 
            
     ?>
     </div>
     
     <div style="clear:both;"/></div>
     <?php 
     
    }  
  /* returns date from time() */
  function the_date($timestamp){
   return date('m/d/y h:m:s A', $timestamp);
  } 
  
  /* hover menu */
   function adminPriHover($id, $current){
      $adminCheck = $this->user->adminCheck();
      if($current == 1) $current = "Высокий";
      elseif($current == 2) $current = "Средний";
      elseif($current == 3) $current = "Низкий";
   ?>
   <a href="javascript:void(<?php echo $current;?>);" id="<?php echo $id;?>PriB"><?php echo $current;?></a>
   <?php if($adminCheck == true) { ?>     
      <script>$(document).ready(function(){$('#<?php echo $id;?>Pri').hide();
$("#<?php echo $id;?>PriB").toggle(function () {$('#<?php echo $id;?>Pri').fadeIn();},function () {$('#<?php echo $id;?>Pri').hide();});
        });
      </script>
      <div id="<?php echo $id;?>Pri" class="PriMenu" style='position: absolute; z-index: 99; border: 1px dotted #ababab; margin-left: -5px; background-color: #EFEFEF; width: 50px; margin-top: -55px;'>
      <a href="javascript:;" onclick="$.post('ajax.php', { changepri: '1', id: '<?php echo $id;?>', username: '<?php echo $_SESSION["userName"];?>', password: '<?php echo $_SESSION["passWord"];?>' } ); $('#<?php echo $id;?>PriB').empty(); $('#<?php echo $id;?>PriB').append('Высокий');">Высокий</a>
      <a href="javascript:;" onclick="$.post('ajax.php', { changepri: '2', id: '<?php echo $id;?>', username: '<?php echo $_SESSION["userName"];?>', password: '<?php echo $_SESSION["passWord"];?>' } ); $('#<?php echo $id;?>PriB').empty(); $('#<?php echo $id;?>PriB').append('Средний');">Средний</a>
      <a href="javascript:;" onclick="$.post('ajax.php', { changepri: '3', id: '<?php echo $id;?>', username: '<?php echo $_SESSION["userName"];?>', password: '<?php echo $_SESSION["passWord"];?>' } ); $('#<?php echo $id;?>PriB').empty(); $('#<?php echo $id;?>PriB').append('Низкий');">Низкий</a></div> 
      <?php } ?>
    
    
   <?php   
  }
  /* returns image for type */ 
  function img($num){
    /* 0 bug; 1 feature */
    if($num == 0) return "images/smbug.png";
    if($num == 1) return  "images/feature.png";
  }  
   function quickadd(){
       if(isset($_POST["quickadd"])){
        $bugData = array('id' => 'null', 'project' => $_POST["project"], 'parent' => 0, 'title' => strip_tags($_POST["title"]), 
        'report' => $_POST[report], 'status' => '1', 'by' => $reportedby, 'priority' => 3, 
        'type' => $_POST[type], 'started' => time(), 'finished' => '0', 'due' => '0', 'character' => $_POST["character"], 'assigned' => '0');
        $this->db->query_insert("list", $bugData);
       	   echo "<script>window.location='index.php';</script>";   
              
       }
   ?>
   <script> 
   function quickAdd(){
    var ch = document.getElementById('title').value;
    if(ch == "Quickly add a bug... ")
        document.getElementById('title').value = '';
   }
   function quickAddU(){
       var ch = document.getElementById('title').value;
     if(ch == "")
        document.getElementById('title').value = 'Quickly add a bug... ';
   } 
   </script>
   <!--
     <form name="" method="POST" action="">
     <tr class="L1">
     <td colspan="9" align="center">
    <img src="images/smbug.png" id="smbug" onclick="document.getElementById('smbug').style.borderBottom='2px solid black'; document.getElementById('feat').style.borderBottom='0px solid black';document.getElementById('type').value='0';">
    
    <input type="hidden" name="type" id="type" value="">
    <select name="project">
             <?php 
             $tpr = $this->db->query("SELECT * FROM projects ORDER BY `name` ASC");
             while($r = mysql_fetch_array($tpr))
                echo '<option value="'.$r['id'].'">'.$r['name'].'</option>';
           ?>
    </select>
    <input name="title" id='title' class="quick" style="width: 400px;" onfocus="quickAdd()" onblur="quickAddU()" value="Быстрая отправка сообщения... " />
     
     <input type="submit" name="quickadd" value="Добавить">
     </td>
     </tr>
     </form>
	 -->
   <?php   
  }
}
  
class View extends Bugs {
    var $db = 0;
    function View($db){
        $this->db=$db;
        $this->user=new User();
    }
    
    /* from php.net or something -- regex to convert text-links to html links */
    function make_clickable($text, $ce, $git){
       
        if (ereg("[\"|'][[:alpha:]]+://",$text) == false)
        {
            $text = ereg_replace('([[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/])', '<a target=\"_new\" href="\\1">\\1</a>', $text);
        }
        $patterns = array('#\[ce\](.*?)\[/ce\]#is', 
        					'#\[git\](.*?)\[/git\]#is');
        $replacements = array(
        			'<a href="'.$ce.'index.php?fuse=support&view=ViewTicketDetails&ticketID=$1" target="blank">\[CE-Ticket \#$1\]</a>', 
        			'<a href="'.$git.'$1">\[GitHub: $1\]</a>'); 
       // $text = preg_replace('#\[ce\](.*?)\[/ce\]#is', '<a href="'.$ce.'index.php?fuse=support&view=ViewTicketDetails&ticketID=$1" target="blank">\[CE-Ticket \#$1\]</a>', $text);
       $text = preg_replace($patterns, $replacements, $text);
        //'#\[wow\](.*?)\[/wow\]#is'
        return($text);
    }
    
    function edit($parent, $commentid){
    	
    	$report = $this->db->first("SELECT `report` FROM list WHERE `id`='$commentid'");
    	$title = $this->db->first("SELECT `title` FROM list WHERE `id`='$commentid'");
     if( $this->user->adminCheck() ){ 
    	if(isset($_POST["save"])){
    		$this->db->query_update("list", array('report' => nl2br($_POST["report"]), 'title' => $_POST["subject"]), "`id`='$commentid' LIMIT 1");
    		echo '<script>window.location="?cmd=view&id='.$parent.'";</script>';
    	}
    	?>
    	
    	<form name="" method="POST" action="">
<table width="90%" cellspacing="2" align="center">
<tr>
<td colspan="2"><div id="headings">Редактировать Комментарий #<?php echo $commentid; ?></div>
</td>
</tr>

<tr>
	<td valign="top" width="50%">
		<table width="100%" cellspacing="2" align="center">
			<tr>
				<td><label for="subject" >Название</label></td>
			</tr>
			<tr>
				<td><input type="text" value="<?php echo $title; ?>"class="input" name="subject" /></td>
			</tr>
			<tr>
				<td><label for="subject" >Комментарий</label></td>
			</tr>
			<tr>
				<td><textarea name="report" class="textarea"><?php echo str_replace('<br />', '', $report); ?></textarea></td>
			</tr>
			<tr>
				<td><div id="working"><img src="/loader.gif" id="loader" /> <b>В Процессе...</b></div> 

            </div> <input type="submit" name="save" value="Сохранить" onclick="$('#working').fadeIn(); document.getElementById('working').style.visibility='visible';"></td>
			</tr>
		</table>
	</td>
	</tr>
	</table>
		</form>
    	<?php
    	}else{
    		echo "Доступ запрещен";
    	}
    }
    
    function original($bugid){
    $q = $this->db->query("SELECT * FROM list WHERE `id`='$bugid'");


        while($r = $this->db->fetch_array()){
        	$this->clientexec = $this->db->first("SELECT client_exec FROM projects WHERE `id`='".$r["project"]."' ");
			$this->git = $this->db->first("SELECT github FROM projects WHERE `id`='".$r["project"]."' ");
         ?>
         <?php if( $this->user->adminCheck() ){ ?>
         	<script>
         		function saveTitle(){
         			$.post('ajax.php', {titlechange: document.getElementById('title').value, tickid: '<?php echo $r["id"];?>', username:'<?php echo $_SESSION["userName"];?>', password: '<?php echo $_SESSION["passWord"];?>'}, function(data){ alert(data); } );
         			
         			$('#tickettitle').replaceWith("<span id='ticketitle'>" + document.getElementById('title').value + "</span>");	
         		}
         		function editTitle(){
         			//$('#tickettitle').empty();
         			$('#tickettitle').replaceWith("<span id='tickettitle'><input name='edittitle' id='title' onblur='saveTitle()' value='" + $('#tickettitle').text() + "' /></span>");
         		}
         	</script>
         	<?php
         }
         ?>
        <table class="bugreport alt1" align="center">
         	<tr>
         		<td>
					<div id="headings" style="float: left;" class="dark">
						<img src="<?php echo $this->img($r["type"]);?>" style='' /> <?php echo $r["title"];?>
					</div>
					<?php if( $this->user->adminCheck() ){ ?>
					<div style="float: right;">
						<small class="small">
							<a href="?cmd=edit&parent=<?php echo $_GET["id"]; ?>&commentid=<?php echo $r["id"]; ?>">редактировать</a>
						</small>
					</div>
					<?php } ?>
         		</td>
         	</tr>
         	<tr>
         		<td>
					<div id="subheading" >Сообщил <?php echo $this->user->uidToName($r["by"]);?> | <?php if ($r["character"]!=0) echo "Персоонаж ".$this->user->charuidToName($r["character"])." | "; echo date("M, d Y H:m:A",$r["started"]); ?></div>         		
         		</td>
         	</tr>
         	<tr>
				<td id="reportarea">
					<?php echo stripslashes($this->make_clickable($r["report"], $this->clientexec, $this->git )); ?>
				</td>
         	</tr>
         	<?php         	
         	if(strlen($r["attachment"]) > 0){
         		$fn = strpos($r["attachment"], '-');
			?>
         			<tr>
						<td>
							<div id="headings-small">Вложение</div>
						</td>
         			</tr>
         			<tr>
						<td>
							<a href="<?php echo $r["attachment"]; ?>" target="_blank"><?php echo str_replace('uploads/', '',substr($r["attachment"], 0, $fn)); ?></a>
						</td>
					</tr>
         		<?php  	}	?>
         </table>
         
           <?php /*<table width="90%" class="bugreport" align="center">
                   <tr>
                        </td>
                   </tr>
                   <tr>
                   <td width="150" align="center" valign="top">
                        <img src="<?php echo $this->img($r["type"]);?>" style='' /> <hr style='border: 0;'>
                       <b> Assigned to:</b> <br /><span id="assto"><?php echo $this->user->assigned($r["assigned"]);?></span>  <hr  style='border: 0;'>
                        <b>Priority:</b> <br /><?php echo $this->adminPriHover($r["id"], $r["priority"]);?>  <br>     <br>
                        <b>Status</b>: <span id="status"><?php if($r["status"] == 1) echo "Открыто";
                                        else  echo "Закрыто"; ?></span>
                   </td>
                        <td valign="top">
                        
                        <h3 style='border-bottom: 1px solid #ACACAC;'>
                       <?php if($this->user->adminCheck() ){ ?> <img src="images/page_edit.png" onclick="editTitle();"> <?php } ?>
                        <span id="tickettitle"><?php echo $r["title"];?> </span>
                        <small>by <?php echo $this->user->uidToName($r["by"]);?></small></h3>
                        <?php echo stripslashes( $this->make_clickable($r["report"]) );?>
                        
                        </td>
                        
                   </tr>
                   <tr>
                        <td colspan="2" align="right">
                        <?php if($this->user->adminCheck()) { ?><a href="?cmd=delete&id=<?php echo $r["id"];?>" style='color: red;'>Delete</a><?php } ?>
                        <a href="javascript:;" id="reply">Reply</a></td>
                   </tr>
           </table>
           <script>
        $(document).ready(function() { $('#replyForm').hide();
        $('#reply').toggle(function () {$('#replyForm').slideDown();},function () {$('#replyForm').slideUp();});
         $('#assignlink').toggle(function () {$('#assign').show();},function () {$('#assign').hide();}); $('#assign').hide(); } );</script>
       */ ?>  <?php   
        }

    }
    function responses($bugid){
     $q = $this->db->query("SELECT * FROM list WHERE `parent`='$bugid' ORDER BY `id` ASC");
        $counter = 1;
        $cssclass = "alt1";
        while($r = $this->db->fetch_array()){
            $counter++;
            if($cssclass == "alt1")
            	$cssclass = "alt2";
            elseif($cssclass == "alt2")
            	$cssclass = "alt1";
         ?>

         <table width="80%" class="bugreport <?php echo $cssclass; ?>" align="center" onmouseover="$('#<?php echo $r["id"];?>edit').css('visibility','visible');$('#<?php echo $r["id"];?>edit').css('display','block'); " onmouseout="$('#<?php echo $r["id"];?>edit').css('visibility','hidden');$('#<?php echo $r["id"];?>edit').css('display','none'); ">
         	<tr>
         		<td>
					<div id="headings" style="float: left; "class="dark">
						<?php echo $r["title"];?>
					</div>
					<?php if( $this->user->adminCheck() ){ ?>
					<div style="float: right; visibility: hidden; display: none;" id="<?php echo $r["id"];?>edit">
						<small class="small">
						<form name='' style='margin: 0; padding: 0; float: left; ' method='post' action='' id='delete<?php echo $r["id"]; ?>'><input type='hidden' name='deletecomm' value='<?php echo $r["id"];?>' /></form><input type='image' src='images/bin_closed.png' onclick='deletetd(<?php echo $r["id"];?>);' name='delete' />

							<a href="?cmd=edit&parent=<?php echo $_GET["id"]; ?>&commentid=<?php echo $r["id"]; ?>">Редактировать</a>
						</small>

					</div>
					<?php } ?>
         		</td>
         	</tr>
         	<tr>
         		<td>
					<div id="subheading">Сообщил <?php echo $this->user->uidToName($r["by"]);?> | <?php if ($r["character"]!=0) echo "Персоонаж ".$this->user->charuidToName($r["character"])." | ";  echo date("M, d Y H:m:A",$r["started"]); ?></div>
         		</td>
         	</tr>
         	<tr>
				<td id="reportarea" >
					<?php echo stripslashes($this->make_clickable($r["report"], $this->clientexec, $this->git )); ?>
				</td>
         	</tr>
         	<?php         	
         	if(strlen($r["attachment"]) > 0){
         		$fn = strpos($r["attachment"], '-');
         		?>
         	<tr>
         		<td>
					<div id="headings-small">Вложение</div>
				</td>
         	</tr>
         	<tr>
				<td>
					<a href="<?php echo $r["attachment"]; ?>" target="_blank"><?php echo str_replace('uploads/', '',substr($r["attachment"], 0, $fn)); ?></a>
				</td>
			</tr>
         		<?php } ?>
         </table>
		<?php /*
           <table width="90%" class="bugreport" align="center" style='border: 1px solid #efefef;'>
                  
                   <tr>
                   <td width="150" align="center" valign="top">
                    <h1><?php echo $counter;?></h1>    
                   </td>
                        <td valign="top">
                        
                        <h3 style='border-bottom: 1px solid #ACACAC;'><?php echo $r["title"];?> <small>by <?php echo $this->user->uidToName($r["by"]);?></small></h3>
                        <?php echo stripslashes($this->make_clickable($r["report"]));?>
                        
                        </td>
                        
                   </tr>
                   <tr>
                        <td colspan="2" align="right"style='background-color: #e8e8e8;'>
                        <?php if($this->user->adminCheck()) { ?><a href="?cmd=delete&id=<?php echo $r["id"];?>" style='color: red;'>Delete</a><?php } ?></td>
                   </tr>
           </table>
           */ ?>
         <?php   
        }
    }
    
    function reply($bugid){
		$reportedby = $this->user->getUID();
		if (isset($_POST['subject'])){
			$subject = $_POST['subject'];
		}else{
			$subject = "";
		}
		if (isset($_POST['report'])){
			$report = $_POST['report'];
		}else{
			$report = "";
		}
     ?>
	<form name="" method="POST" action="" enctype="multipart/form-data">
		<input type="hidden" name="MAX_FILE_SIZE" value="2000000">
		<table width="90%" cellspacing="2" align="center">
			<tr>
				<td colspan="2">
					<div id="headings">Добавить Комментарий</div>
				</td>
			</tr>
			<tr>
				<td valign="top" width="50%">
					<table width="100%" cellspacing="2" align="center">
						<tr>
							<td>
								<label for="subject" >Название</label>
							</td>
						</tr>
						<tr>
							<td>
								<input type="text" class="input" name="subject" value="<?=$subject;?>" />
							</td>
						</tr>
						<?php
							if ($reportedby){
						?>
						<tr>
							<td>
								<label for="character">Персоонаж</label>
							</td>
						</tr>
						<tr>
							<td>
								<select name="character" class="select"><? echo $this->listCharacters($reportedby);?></select>
							</td>
						</tr>
						<?php 
							} else {
						?>
						<input type="hidden" name="character" value="0">
						<?php } ?>
						<tr>
							<td>
								<label for="subject" >Комментарий</label>
							</td>
						</tr>
						<tr>
							<td>
								<textarea name="report" class="textarea"><?=$report;?></textarea>
							</td>
						</tr>
						<tr>
							<td>
								<label for="attachment">Вложение</label>
							</td>
						</tr>
						<tr>
							<td>
								<input type="file" name="attachment" />
							</td>
						</tr>
						<tr>
							<td>
								<div id="working">
									<img src="/loader.gif" id="loader" /> 
									<b>В Процессе...</b>
								</div>
								<input type="submit" name="submitReport" value="Отправить" onclick="$('#working').fadeIn(); document.getElementById('working').style.visibility='visible';">
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</form>
              
    <?php
    } 
   
    function reports(){
      ?>  
     <div class="clear"></div>
<div id="reports">
<?php 
// get projects
  $q = $this->db->query("SELECT * FROM `projects` ORDER BY `name` AND `mini` ASC");
  while($r = $this->db->fetch_array()){
      $unfinished = $this->db->first("SELECT count(*) FROM `list` WHERE `parent`='0' AND `project`='$r[id]' AND `status`='1' AND `type`='0'");
      $finished = $this->db->first("SELECT count(*) FROM `list` WHERE `parent`='0' AND `project`='$r[id]' AND `status`='0' AND `type`='0'");
        if($unfinished == 0) $unfinished = $finished; 
      $percent = @round(($finished/$unfinished), 2);
      $backgroundpos = 300-($percent * 100 * 3);
   ?>
     <h2><?php echo $r["name"];?></h2>
     <div>
        <table cellspacing="0" cellpadding="0" border="1" width="300">
        <tr>
            <td align="center" style="background-image: url('<?php echo BBPATH; ?>/images/bar.gif'); background-position: -<?php echo $backgroundpos;?>px 0px; background-repeat: no-repeat;  color: black;">
            <?php echo $finished;?>/<?php echo $unfinished;?>
            </td>
        <tr>
        </table> 
     </div>
      <?php 
      $unfinished = $this->db->first("SELECT count(*) FROM `list` WHERE `parent`='0' AND `project`='$r[id]' AND `status`='1' AND `type`='0'");
      $finished = $this->db->first("SELECT count(*) FROM `list` WHERE `parent`='0' AND `project`='$r[id]' AND `status`='0' AND `type`='0'");
        if($unfinished == 0) $unfinished = $finished; 
      $percent = @round(($finished/$unfinished), 2);
      $backgroundpos = 300-($percent * 100 * 3);
      //echo ;
      ?>
     <div>
   <?php   
  }
?>
</div>
<div class="clear"></div>
<?php   
    }
    
    
  function listProjects($current = null){
   $q = $this->db->query("SELECT `id`, `name`, `mini` FROM `projects` ORDER BY `name` ASC");
   while($r = $this->db->fetch_array())
    echo "<option value='$r[id]'>$r[name]</option>";    
  }
  function listCharacters($acc_id, $current = null){
   $q = $this->db->query("SELECT `guid`, `name` FROM `characters`.`characters` WHERE `account`='$acc_id' ORDER BY `name` ASC");
   while($r = $this->db->fetch_array())
    echo "<option value='$r[guid]'>$r[name]</option>";    
  }  
  
 
}  
?>
