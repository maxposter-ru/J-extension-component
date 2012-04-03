<?php
/**
 * Список авто
 *
 * @param  DomDocument  $this->xml
 * @param  string       $this->cache_id
 * @param  string       $this->cache_lifetime
 * @param  ?            $this->params           Параметры компонента + настройки в меню
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

# Output cache - not the best place?
$this->cache = JFactory::getCache('com_maxposter', 'output');
$this->cache->setCaching((bool) $this->cache_lifetime);
$this->cache->setLifeTime($this->cache_lifetime);
$this->cache->_getStorage()->_lifetime = $this->cache_lifetime;

# Кеширование всей страницы компонента целиком <<<START
if (!$this->cache->start(sprintf('list_autos_%s', $this->cache_id))) {

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');

$xpath = new DOMXPath($this->xml);
$this->pager = $xpath->query('/response/pager')->item(0);

?>
<div id="maxposter" class="maxposter<?php echo $this->escape($this->params->get('pageclass_sfx', '')); ?>">
<?php # заголовок h1 на странице ?>
<?php if ($this->params->get('show_page_heading', 1)) : ?>
<h1>
    <?php if ($this->escape($this->params->get('page_heading'))) :?>
        <?php echo $this->escape($this->params->get('page_heading')); ?>
    <?php else : ?>
        <?php echo $this->escape($this->params->get('page_title')); ?>
    <?php endif; ?>
</h1>
<?php endif; ?>

<?php # 404 ошибка - нет данных в запросе поиска ?>
<?php if (404 === $this->params->get('error', false)) : ?>
    <?php echo $this->loadTemplate('error'); ?>
    <?php
    if (defined('JDEBUG') && constant('JDEBUG')) {
        ?><div class="maxposter-debug profiler"><pre><code><?php
            echo $this->escape($this->xml->saveXml());
        ?></code></pre></div><?php
    }
    ?>
<?php # список ?>
<?php else: ?>
    <?php # форма поиска ?>
    <?php if ($this->params->get('show_search', 1)) : ?>
        <?php echo $this->loadTemplate('search_form'); ?>
    <?php endif; ?>

    <?php echo $this->loadTemplate('autos') ?>

    <?php # постраничка ?>
    <?php if (ceil($this->pager->getAttribute('items_total') / $this->per_page) > 1) : ?>
        <?php echo $this->loadTemplate('paginator') ?>
    <?php endif ?>

<?php endif; ?>
</div>
<?php
    $this->cache->end();
}
# Кеширование всей страницы компонента целиком END>>>
?>
