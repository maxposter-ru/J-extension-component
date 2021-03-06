<?php
/**
 * Список авто
 *
 * @param  DOMDocument  $this->xml
 * @param  integer      $this->page
 * @param  integer      $this->per_page
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$xpath = new DOMXPath($this->xml);
$vehicles = $xpath->query('./vehicle', $this->xml->getElementsByTagName('vehicles')->item(0));
$pager = $this->pager;
$vCount = $vehicles->length;
$per_page = $this->per_page;
if ($this->page > ceil($pager->getAttribute('items_total') / $per_page)) {
    $this->page = ceil($pager->getAttribute('items_total') / $per_page);
}
$page = $this->page;

?>
<?php
if (defined('JDEBUG') && constant('JDEBUG')) {
    JProfiler::getInstance('Application')->mark('onBeforeLoop');
}
?>

<?php if ($vCount && ($page > 0)) : ?>
<?php
    $start_pos = (($page - 1)*$per_page + 1) - ($pager->getAttribute('items_per_page') * (ceil($page / ($pager->getAttribute('items_per_page') / $per_page)) - 1));
    $end_pos = (($page*$per_page > $pager->getAttribute('items_total')) ? ($start_pos-1 + ($pager->getAttribute('items_total') - ($page-1)*$per_page)) : $start_pos-1+$per_page);

    $tileWidth = floor(100 / $this->params->get('items_per_row', 3));
?>
    <?php for ($i=$start_pos-1;$i < $end_pos;$i++) : ?>
        <?php
        if (defined('JDEBUG') && constant('JDEBUG')) {
            ?><div class="maxposter-debug profiler"><?php
                echo $i;
            ?></div><?php
        }
        $localPos = ($i-$start_pos+1);
        ?>
        <?php if (0 === ($localPos % $this->params->get('items_per_row', 3))) : ?>
            <?php if ($localPos > 0) : ?>
                </div>
            <?php endif ?>
            <div class="maxposter-tile-row">
        <?php endif ?>
        <div class="maxposter-tile-item<?php echo (0 === ($localPos-1 % $this->params->get('items_per_row', 3))) ? ' maxposter-tile-item-last' : '' ; ?>" style="width:<?php echo $tileWidth ?>%">
        <?php // кешируем каждый элемент для использования при разных запросах ?>
        <?php if (!$this->cache->start(sprintf('list_auto_vehicle_%d', $vehicles->item($i)->getAttribute('vehicle_id')))) : ?>
            <?php $this->vehicle = simplexml_import_dom($vehicles->item($i)) ?>
            <?php echo $this->loadTemplate('auto') ?>
            <?php $this->cache->end() ?>
        <?php endif ?>
        </div>
    <?php endfor ?>
    </div>
<?php endif ?>

<?php
if (defined('JDEBUG') && constant('JDEBUG')) {
    JProfiler::getInstance('Application')->mark('onAfterLoop');
}
?>
