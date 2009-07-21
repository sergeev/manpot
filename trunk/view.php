<?php
  defined('__bbug') or die(); 
  $bugid = $this->db->clean($_GET["id"], '', '');
  $reportedby = $this->user->getUID();
  
  $bugView = new View($this->db);
  
if(isset($_POST["submitReport"])){ 
   if(empty($_POST['subject']))
  {
  	$this->message("<center><h3>Не указано название!</h3></center>");
  }
  elseif(empty($_POST['report']))
  {
  	$this->message("<center><h3>Отсутствует комментарий!</h3></center>");
  }
  else
  { 
   // do file uploads
  $target = "";
  $file_name = "";
  if(strlen($_FILES['attachment']['name'])>0){
  	$target = "uploads/" ;
  	
  	// get file ext
  	$file_ext = end(explode('.', $_FILES['attachment']['name']));
  	
  	// check against config values
  	$allowed_ext = explode(',', allowed_types);
  	
  	if(in_array($file_ext, $allowed_ext)){
  		$file_name = $_FILES['attachment']['name']."-".time().".".$file_ext;
  		if(move_uploaded_file($_FILES['attachment']['tmp_name'], $target.$file_name)){
  			//echo ""
  		}else{
  			echo "<center>Ваше сообщение было опубликовано, но вложение не было загружено. Свяжитесь с Системным Администратором для проверки доступа к загрузке файлов.</center>";
  			$file_name = "";
  			$target = "";
  			
  		}
  	}else{
  		echo "Ваше сообщение было опубликовано, но вложение не было загружено. Запрещенный тип файла.";
  		$file_name = "";
  			$target = "";
  	}
  }else{
  	
  }
  
  
  $bugData = array('id' => 'null', 'project' => $this->db->first("SELECT `project` FROM list WHERE `id`='$bugid'", 0, 0),
  'parent' => $bugid, 'title' => $_POST["subject"], 
        'report' => nl2br(strip_tags($_POST["report"])), 'status' => '', 'by' => $reportedby, 'priority' => 0, 
        'type' => 0, 'started' => time(), 'finished' => '', 'due' => '', 'assigned' => '', 'attachment' => $target.$file_name);
                $this->db->query_insert('list', $bugData);
                $this->message("<center><h3>Комментарий добавлен.</h3></center>");
                  unset($_POST);
				  
  }
  }
?>  
<?php 
// view original ticket 
if($bugid > 0){
$bugView->original($bugid);

$bugView->responses($bugid);

$bugView->reply($bugid);
}else{
	echo "Неверный номер сообщения.";
}
?>
<div class="clear"></div>