<?php
defined('__bbug') or die();
   // ��������� ���������� � ��
   $db['host'] = "localhost"; // ����; ������ localhost
   $db['user'] = "root"; // ��� ������������ ��
   $db['pass'] = ""; // ������ ������������ ��
   $db['db'] = "test"; // ���� ������ MaNPOT
       
   
   $config['registered'] = 0; // ���� ����������� 0- �������������������� ������������ ����� ������ ���������, ���� 1- ������ ������������������
   
   
   $allowed_types = "gif,jpg,jpeg,png,doc,docx,bmp,zip,rar,7z"; // ������ ��������� ��� �������� ����������
   define('allowed_types', $allowed_types);
?>
 