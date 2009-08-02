<?php
  /* Main class */
  
defined('__bbug') or die();
define("BBPATH", "http://".substr($_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"], 0, strrpos($_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"],"/") ) );
  
class Main { 
	var $db = 0;
	var $user = 0;
	function Main($db){
		$this->db=$db; 
		$this->user = new User();
	}
   
	function headStart(){
?>
        <html>
			<head> 
				<title><? echo SITE_NAME; ?></title>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
				<link type="text/css" href="bug.css" rel="stylesheet" />
				<link type="text/css" href="js/jquery.wysiwyg.css" rel="stylesheet" />
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
				<script type="text/javascript" src="js/init.js"></script>         
				<script>
					$(document).ready(function(){
						$('#ProjTab').hide();
						$("#ProjToggle").toggle(function () {
							$('#ProjTab').fadeIn();
						},
						function () {
							$('#ProjTab').hide();
						});
					});
				</script>
			</head>
			<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
				<table align="center" width="1000" cellspacing="0" cellpadding="0">
					<tr>
						<td><img src="images/logo.png" width="259" height="71" alt=""></td>
						<td id="bbtop" width="100%"><div style='float: right;padding-right: 20px;'><? echo $this->user->loginForm();?></div></td>
					</tr>
					<tr>
						<td colspan="2">
							<table width="100%" cellspacing="0" cellpadding="0" align="center">
								<tr>
									<td align="left" valign="top"><img src="images/rep-ll.gif" width="9" height="25" alt=""></td>
									<td id="bbbot" width="100%" valign="top" >
										<table width="55%" style="padding-top: 6px;" align="left" cellspacing="0" cellpadding="0">
											<tr>
												<td><a href="?">Главная</a></td>
												<td><a href="?cmd=submit">Сообщить о ошибке</a></td>
											<?php
												$counter = array();
												$counter['open'] = $this->db->first("SELECT count(*) FROM list WHERE `status`='1' AND `parent`='0' ");
												$counter['closed'] = $this->db->first("SELECT count(*) FROM list WHERE `status`='0' AND `parent`='0' ");
												$counter['feature'] = $this->db->first("SELECT count(*) FROM list WHERE `type`='1' AND `parent`='0' ");
												$counter['bug'] = $this->db->first("SELECT count(*) FROM list WHERE `type`='0' AND `parent`='0' ");
												?>
												<td onmouseover="ticketMenuShow();"><a href="?">Сообщения</a> <a href="#" style="margin-top: 2px; margin-left: 3px; position: absolute;" id="TICKETS" onclick=""><img src="images/arrow.png" border="0" width="10" height="10" /></a>
													<div id="ticketMenu" onmouseout="ticketMenuHide();" style="display: none; visibility: hidden;">
														<a href="?specialrefiner=all">Показать Всё (<?php echo $counter['open']+$counter['closed']; ?>)</a>
														<a href="?specialrefiner=open">Показать Активные (<?php echo $counter['open']; ?>)</a>
														<a href="?specialrefiner=closed">Показать Закрытые (<?php echo $counter['closed']; ?>)</a>
														<div id="headings-small">Категории</div>
													<?php             
														$tpr = $this->db->query("SELECT * FROM projects ORDER BY `name` ASC");
														while($r = mysql_fetch_array($tpr))
														echo '<a href="?specialrefiner='.$r['id'].'">'.$r['name'].'</a>';
														?>
													</div>
												</td>
											<!--<td><a href="?cmd=todo">TO-DO</a></td>-->
												<td><a href="?cmd=reports">Статистика</a></td>
										<?php 
											if(isset($_GET["id"]) && $_GET["cmd"]=="view" && $this->user->adminCheck()){ 
											?>
												<td></td>
												<td onmouseover="thisTicketShow();"><a href="?cmd=view&id=<?php echo $_GET["id"]; ?>"><font size=1 color=red><b>Опции</b></font></a> 
													<a href="#" style="margin-top: 2px; margin-left: 3px; position: absolute;" id="TICKETMENUUNI" onclick="">
														<img src="images/arrow.png" border="0" width="10" height="10" />
													</a>
													<div id="ticketMenuUniq"  onmouseout="thisTicketHide();" style="display: none; visibility: hidden;">
													<div id="headings-small">Опции Сообщений</div>
													<a href="javascript:;" onclick="$('#status').empty();$('#status').append('Open');$.post('ajax.php', {openticket: 'true', tickid: '<?php echo $_GET["id"];?>', username:'<?php echo $_SESSION["userName"];?>', password: '<?php echo $_SESSION["passWord"];?>'}, function(data){ alert(data); });">Открыть</a>
													<a href="javascript:;" onclick="$('#status').empty();$('#status').append('Closed');$.post('ajax.php', {closeticket: 'true', tickid: '<?php echo $_GET["id"];?>', username:'<?php echo $_SESSION["userName"];?>', password: '<?php echo $_SESSION["passWord"];?>', by: '<?php echo $r["by"];?>'}, function(data){ alert(data); });">Закрыть</a>
												</td>
										<?php 
											} 
											?>  
											</tr>
										</table>
									</td>
									<td align="right" valign="top"><img src="images/rep-lr.gif" width="9" height="25" alt=""></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>        
				<div class="clear"></div> 
<?php
    }
	
	function body(){
		if(isset($_GET["cmd"])){
			$cmd = $_GET["cmd"];
		}else{
			$cmd = "";
		}
		if($cmd == "submit"){
			include('submit.php');
		}elseif($cmd == "delete"){
			//check if its parent ticket
			$bugid = $this->db->clean($_GET["id"], '', '');
			if($this->db->first("SELECT `parent` FROM list WHERE id='$bugid'") == 0){
				$this->db->del("list", "id='$bugid'");
				$this->db->del("list", "parent='$bugid'");
			}else{
				$this->db->del("list", "id='$bugid'", '1');   
			}
			$this->message("Запись удалена.");   
		}elseif($cmd == "reports"){
			include('report.php');
		}elseif($cmd == "edit"){
			include('edit.php');
		}elseif($cmd == "bugs"){
			include('bugs.php');
		}elseif($cmd == "features"){
			include('features.php');
		}elseif($cmd == "view"){
			include('view.php');
		}elseif($cmd == "todo"){
			include('todo.php');
		}elseif(isset($_GET["admin"])){
			include('admin/admin.php');       
		}elseif($cmd == "" && !isset($_GET["admin"])){
			include('home.php');
		}
	}
	
	function message($string){
		echo "
		<center>
		<div id='messageShow' style='display: none; width: 90%;' width='90%'>$string</div><script>$('#messageShow').fadeIn('1000'); setTimeout(\"$('#messageShow').fadeTo('slow', .33)\", 1000);  setTimeout(\"$('#messageShow').fadeOut()\", 3000); </script></center>";
	}
	
	function message_error($string){
		echo "
		<center>
		<div id='messageErrorShow' style='display: none; width: 100%;' width='100%'>$string</div><script>$('#messageErrorShow').fadeIn('10000');</script></center>";
	}
   
	function footStart(){
	?>
			<center class="copyright">Адаптировал для RMDC DeusModus. Дополнен и модифицирован LaMerXaKer</a>
			</center>
		</body>
	</html>
    <?php
	}   
   /* end class */
}  
?>