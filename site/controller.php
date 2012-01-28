<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

// MaxPoster libraries
jimport('maxposter.maxCacheHtmlClient');

require_once (JPATH_COMPONENT.DS.'lib'.DS.'client'.DS.'maxClient.php');
require_once (JPATH_COMPONENT.DS.'helpers'.DS.'helper.php');

class MaxPosterController extends JController
{

    /**
     * Контроллер для страницы со списком автомобилей
     */
    function display($cachable = false, $urlparams = false)
    {
        // Set the default view name from the Request.
        $vName = JRequest::getCmd('view', 'list');
        JRequest::setVar('view', $vName);

        parent::display($cachable, $urlparams);
        return $this;
    }

}
