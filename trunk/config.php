<?php
defined('__bbug') or die();
   // ��������� ���������� � ��
   $db['host'] = "localhost"; // ����; ������ localhost
   $db['user'] = "root"; // ��� ������������ ��
   $db['pass'] = ""; // ������ ������������ ��
   $db['db'] = "test"; // ���� ������ MaNPOT
       
   
   $config['registered'] = 0; // ���� ����������� 0- �������������������� ������������ ����� ������ ���������, ���� 1- ������ ������������������
   $config['minlen_subject'] = 5; // ����������� ���������� �������� � �������� ���-�������
   $config['minlen_report'] = 20; // ����������� ���������� �������� � �������� ���-�������
   
   
   
   $allowed_types = "gif,jpg,jpeg,png,doc,docx,bmp,zip,rar,7z"; // ������ ��������� ��� �������� ����������
   define('allowed_types', $allowed_types);
?>
 