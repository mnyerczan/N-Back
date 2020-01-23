<?php
/**
 * This file calls, id on frontend side is_session variable is TRUE. is_session value is false,
 * if $user->id > 1. It's declare on header.php, and check Check_modify_theme_radio() function
 * in m_emory.js file, so $_SESSION['u_name'] is valid on every times when this file calling.
 */

use \Theme\Theme;
use \DB\EntityGateway;
use Login\UserEntity;

session_start();

if ( @!$_SESSION['u_name'] ) die;

chdir('../../');

require_once 'Class/seria.php';
require_once 'theme.php';
require_once 'DB/entityGateway.php';
require_once 'Class/userEntity.php';

require_once 'special_characters_handler.php';



$user = new UserEntity( EntityGateway::getDB() );


$user->Load( $_SESSION['u_name'], $_SESSION['password'] );


$theme = new Theme( EntityGateway::getDB(), $_GET['theme'] );

$theme->UpdateTheme( $user->id );