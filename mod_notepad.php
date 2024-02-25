<?php
/**
 * @copyright	@copyright	Copyright (c) 2022 R2H (https://www.r2h.nl). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;

// get the user object
$user 			= Factory::getApplication()->getIdentity();
$isAdmin 		= false;
$hasRightToView = false;
$canEdit 		= false;

// If user is not logged in return
if ($user->guest) {
	return;
}

// File path info
$location 				= $params->get('location', '');
$location 				= ltrim($location, '/');
$savePath 				= JPATH_SITE . '/' . $location;
$downloadPath			= URI::root() . '/' . $location;

$file_info = pathinfo($savePath);

if (!is_dir($file_info['dirname'])) {
	echo 'mod_notes has wrong directory path';
	return;
}

// https://parsedown.org/
include_once('src/Parsedown.php');
$Parsedown = new Parsedown();

// Get the module settings
$groups = $params->get('usergroup', []);

// Check if user is Super Admin
$isAdmin = (bool) Factory::getApplication()->getIdentity()->authorise('core.admin');

// Check is the user has right to view
$hasRightToView = (bool) count(array_intersect($groups, $user->groups));

if ($isAdmin === true || $hasRightToView === true) {
	$canEdit = true;
}

if (isset($_POST['text'])) {
    $new_content = $_POST['text'];
    file_put_contents($savePath, $new_content);
}

$fileContent = '';

if (file_exists($savePath)) {
	$fileContent = file_get_contents ($savePath);
}

require ModuleHelper::getLayoutPath('mod_notepad', $params->get('layout', 'default'));
