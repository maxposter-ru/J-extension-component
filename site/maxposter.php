<?php
// no direct access
defined('_JEXEC') or die;

// Require the com_content helper library
jimport('joomla.application.component.controller');

$controller = JController::getInstance('MaxPoster');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
