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

// FIXME: перенести в хелпер
// Доступные размеры фотографий
$db = JFactory::getDBO();
$query = "SELECT id, value FROM #__maxposter WHERE `name`= 'photo_size' ORDER BY id";
$db->setQuery($query, 0, $limit=100);
$rows = $db->loadObjectList();

$photoSize = array();
foreach ($rows as $row) {
    list ($width, $height) = explode('x', $row->value);
    $photoSize[$row->value] = array(
        'width'  => $width,
        'height' => $height,
    );
}

jimport('maxposter.maxThumbnail');

$maxThumb = new maxThumbnail(array_merge(array(
    'photo_dir' => dirname(__FILE__) . '/cache', # TODO: в настройки передавать результат работы хелпера
    // для больших фото - orig, для маленьких - original (640x480)
    'source_photo_url' => 'http://www.maxposter.ru/photo/%s/%s/' . ($params->get('view_photo_source_original', 0) ? 'orig' : 'original') . '/%s',
    'allowed_photo_sizes' => array_merge(array(
        '640x480'  => array('width' => 640, 'height' => 480),
        '120x90'   => array('width' => 120, 'height' =>  90),
    ), $photoSize),
), $params->toArray()));

// TODO: сделать очистку кеша по времени модификации файла, очищать старше чем - для крона
$maxThumb->getPhoto($_SERVER['REQUEST_URI']);
