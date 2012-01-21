<?php
/**
* Maxposter Administrator entry point
*/

// no direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_maxposter')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

$controller	= JController::getInstance('Maxposter');
$controller->execute(JRequest::getCmd('task', 'display'));
$controller->redirect();
