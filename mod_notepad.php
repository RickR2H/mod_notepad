<?php
/**
 * @copyright	@copyright	Copyright (c) 2022 R2H (https://www.r2h.nl). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;

$location 				= $params->get('location', '');
$location 				= ltrim($location, '/');
$savePath 				= JPATH_SITE . '/' . $location;
$downloadPath			= URI::root() . '/' . $location;

$savePath 			= $savePath;
$downloadPathFile 		= $downloadPath;

// https://parsedown.org/
include_once('src/Parsedown.php');
$Parsedown = new Parsedown();

// get the user object
$user 			= Factory::getApplication()->getIdentity();
$isAdmin 		= false;
$hasRightToView = false;
$canEdit 		= false;

// Get the module settings
$groups = $params->get('usergroup', []);

// Check if user is Super Admin
$isAdmin = (bool) $user->get('isRoot');

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

if (File::exists($savePath)) {
	$fileContent = file_get_contents ($savePath);
}

require ModuleHelper::getLayoutPath('mod_notepad', $params->get('layout', 'default'));
