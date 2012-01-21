<?php
define( '_JEXEC', 1 );

define('JPATH_BASE', dirname(__FILE__) . '/../../..' );

define( 'DS', DIRECTORY_SEPARATOR );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

/**
 * CREATE THE APPLICATION
 */
$mainframe =& JFactory::getApplication('site');

/**
 * INITIALISE THE APPLICATION
 */
//$mainframe->initialise();


$params = &JComponentHelper::getParams('com_maxposter');

jimport('maxposter.maxThumbnail');
//require_once(dirname(__FILE__).'/../lib/api/lib/maxThumbnail.php');

$maxThumb = new maxThumbnail(array(
  'dealer_id' => $params->get('dealer_id'),
  'photo_dir' => dirname(__FILE__) . '/cache', # TODO: в настройки передавать результат работы хелпера
  // а вот эти параметры и правда бы хотелось в настройки вынести
  'allowed_photo_sizes' => array(
    '640x480'  => array('width' => 640, 'height' => 480),
    '120x90'   => array('width' => 120, 'height' =>  90)
  )
));

$maxThumb->getPhoto($_SERVER['REQUEST_URI']);
