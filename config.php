<?php
defined('__bbug') or die();
   // параметры соединения с БД
   $db['host'] = "localhost"; // хост; обычно localhost
   $db['user'] = "root"; // имя пользователя БД
   $db['pass'] = ""; // пароль пользователя БД
   $db['db'] = "test"; // база данных MaNPOT
       
   
   $config['registered'] = 0; // если установлено 0- незарегистрированные пользователи могут писать сообщения, если 1- только зарегистрированные
   
   
   $allowed_types = "gif,jpg,jpeg,png,doc,docx,bmp,zip,rar,7z"; // список доступных для вложений расширений
   define('allowed_types', $allowed_types);
?>
 