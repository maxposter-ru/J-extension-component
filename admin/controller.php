<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * General Controller
 */
class MaxposterController extends JController
{
	/**
	 * display task
	 *
	 * @return void
	 */
	function display($cachable = false, $urlparams = false)
	{
		// set default view if not set
		JRequest::setVar('view', JRequest::getCmd('view', 'maxposter'));

		// call parent behavior
		parent::display($cachable, $urlparams);

		$document = JFactory::getDocument();
		$document->setTitle(sprintf('%s: %s', $document->getTitle(), JText::_('COM_MAXPOSTER_ADMINISTRATION')));
	}
}
