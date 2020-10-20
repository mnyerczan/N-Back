<?php 

require 'Test/init.php';
require APPLICATION.'Controllers/SettingsController.php';


$sC = new SettingsController();
/*
// Password change test.
// Bad old password
$d['update-user-old-password'] = 'xyzv..';
$d['update-user-password'] = 'almafa';
$d['update-user-retype-password'] = 'almafa';
print_r($sC->validatePass($d));

// Different new password
$d['update-user-old-password'] = 'almafa';
$d['update-user-password'] = 'csokibeka';
$d['update-user-retype-password'] = 'csokibe';
print_r($sC->validatePass($d));

// Too short password
$d['update-user-old-password'] = 'almafa';
$d['update-user-password'] = 'cso';
$d['update-user-retype-password'] = 'cso';
print_r($sC->validatePass($d));

// Good passwords
$d['update-user-old-password'] = 'almafa';
$d['update-user-password'] = 'csokibeka';
$d['update-user-retype-password'] = 'csokibeka';
print_r($sC->validatePass($d));
*/