<?php
defined('__bbug') or die();
   // параметры соединени€ с Ѕƒ
   $db['host'] = "localhost"; // хост; обычно localhost
   $db['user'] = "root"; // им€ пользовател€ Ѕƒ
   $db['pass'] = ""; // пароль пользовател€ Ѕƒ
   $db['db'] = "test"; // база данных MaNPOT
       
   
   $config['registered'] = 0; // если установлено 0- незарегистрированные пользователи могут писать сообщени€, если 1- только зарегистрированные
   $config['minlen_subject'] = 5; // минимальное количество символов в названии баг-репорта
   $config['minlen_report'] = 20; // минимальное количество символов в описании баг-репорта
   
   
   
   $allowed_types = "gif,jpg,jpeg,png,doc,docx,bmp,zip,rar,7z"; // список доступных дл€ вложений расширений
   define('allowed_types', $allowed_types);
?>
 