<?php
/**
 * Авто
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
if (!$this->cache->start(sprintf('car_auto_%s', $this->cache_id))) {
?>
<div id="maxposter" class="maxposter<?php echo $this->escape($this->params->get('pageclass_sfx', '')); ?>">
<?php # 404 ошибка - нет такого авто ?>
<?php if (404 === $this->params->get('error', false)) : ?>
    <?php echo $this->loadTemplate('error'); ?>
    <?php
    if (defined('JDEBUG') && constant('JDEBUG')) {
        ?><div class="maxposter-debug profiler"><pre><code><?php
            echo $this->escape($this->xml->saveXml());
        ?></code></pre></div><?php
    }
    ?>
<?php # авто ?>
<?php else: ?>
    <?php echo $this->loadTemplate('auto') ?>
<?php endif; ?>
</div>
<?php
    $this->cache->end();
}
# Кеширование всей страницы компонента целиком END>>>
?>
