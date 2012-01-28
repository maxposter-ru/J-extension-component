<?php
define( '_JEXEC', 1 );

define('JPATH_BASE', realpath(dirname(__FILE__) . '/../../../'));

define( 'DS', DIRECTORY_SEPARATOR );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

/**
 * CREATE THE APPLICATION
 */
$app = JFactory::getApplication('site');
$component = JComponentHelper::getComponent('com_maxposter');
$menu = $app->getMenu();
$items = $menu->getItems('component_id', $component->id);

// TODO: возможно это не лучший способ получения id, т.к. их может быть много
if (!is_array($items)) {
    $Itemid = '';
} else {
    $Itemid = isset($items[0]->id) ? $items[0]->id : '';
}

$params = JComponentHelper::getParams('com_maxposter');

if ($Itemid) {
    $menuparams = $menu->getParams($Itemid);
    $params->merge($menuparams);
}
if ($params instanceof JRegistry) {
    $params = $params->toArray();
}

jimport('maxposter.maxThumbnail');

$maxThumb = new maxThumbnail(array_merge(array(
  'photo_dir' => dirname(__FILE__) . '/cache', # TODO: в настройки передавать результат работы хелпера
  // а вот эти параметры и правда бы хотелось в настройки вынести
  'allowed_photo_sizes' => array(
    '640x480'  => array('width' => 640, 'height' => 480),
    '120x90'   => array('width' => 120, 'height' =>  90),
  )
), $params));

// TODO: сделать очистку кеша по времени модификации файла, очищать старше чем - для крона
$maxThumb->getPhoto($_SERVER['REQUEST_URI']);
