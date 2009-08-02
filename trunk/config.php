<?php
defined('__bbug') or die();
   // параметры соединения с БД
   $db['host'] = "localhost"; // хост; обычно localhost
   $db['user'] = "root"; // имя пользователя БД
   $db['pass'] = ""; // пароль пользователя БД
   $db['db'] = "realmd"; // база данных MaNPOT
       
   
   $config['registered'] = 1; // если установлено 0- незарегистрированные пользователи могут писать сообщения, если 1- только зарегистрированные
   $config['minlen_subject'] = 5; // минимальное количество символов в названии баг-репорта
   $config['minlen_report'] = 20; // минимальное количество символов в описании баг-репорта
   $config['site_name'] = "MaNPOT"; //Название сайта, отображаемое в верху сайта
   
   
   
   $allowed_types = "gif,jpg,jpeg,png,doc,docx,bmp,zip,rar,7z"; // список доступных для вложений расширений
   define('allowed_types', $allowed_types);
?>
 