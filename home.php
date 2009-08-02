<?php
	defined('__bbug') or die();
  
	$list = new Bugs($this->db);
	$list->tableHeader();
	$list->bblist();
	//$list->quickadd();
?>