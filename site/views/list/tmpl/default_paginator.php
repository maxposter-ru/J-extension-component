<?php
/**
 * Постраничная навигация
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$xpath = new DOMXPath($this->xml);
$pager = $this->pager;
$per_page = $this->per_page;
$page = $this->page;

?>
<?php $pager_pages = ceil($pager->getAttribute('items_total') / $per_page) ?>
<?php $pager_start = (($page - floor($this->params->get('pager_links', 10) / 2)) > 0 ? $page - floor($this->params->get('pager_links', 10) / 2) : 1) ?>
<?php $pager_end = (($page+floor($this->params->get('pager_links', 10)/2)) <= $pager_pages ? $page+floor($this->params->get('pager_links', 10)/2) : $pager_pages) ?>
<?php if ($pager_pages > 1) : ?>
    <div class="maxposter-pages">
        <span>Страницы: </span>
    <ul class="maxposter-paginator">
    <?php $query = JUri::buildQuery(array(sprintf('%ssearch', $this->params->get('prefix', '')) => $this->search_params)) ?>
    <?php # first page ?>
    <?php if ($pager_pages > floor($this->params->get('pager_links', 10)/2)+1) : ?>
        <?php if ($page > 1) : ?>
            <li><a href="<?php echo JRoute::_(sprintf('index.php?option=com_maxposter&view=list%s', ($query ? '&'.$query : ''))) ?>"><?php echo JText::_('JLIB_HTML_START') ?></a></li>
            <?php if ($page-1 == 1) : ?>
                <li><a href="<?php echo JRoute::_(sprintf('index.php?option=com_maxposter&view=list%s', ($query ? '&'.$query : ''))) ?>"><?php echo JText::_('JPREV') ?></a></li>
            <?php else : ?>
                <li><a href="<?php echo JRoute::_(sprintf('index.php?option=com_maxposter&view=list%s&page=%d', ($query ? '&'.$query : ''), $page-1)) ?>"><?php echo JText::_('JPREV') ?></a></li>
            <?php endif ?>
        <?php endif?>
    <?php endif ?>
    <?php # pages ?>
    <?php for ($p=$pager_start;$p<=$pager_end;$p++) : ?>
        <li>
            <?php if ($p == $page) : # current ?>
                <span class="current"><?php echo $p ?></span>
            <?php elseif ($p == 1) : # first ?>
                <a href="<?php echo JRoute::_(sprintf('index.php?option=com_maxposter&view=list%s', ($query ? '&'.$query : ''))) ?>"><?php echo $p ?></a>
            <?php else : # link ?>
                <a href="<?php echo JRoute::_(sprintf('index.php?option=com_maxposter&view=list%s&page=%d', ($query ? '&'.$query : ''), $p)) ?>"><?php echo $p ?></a>
            <?php endif ?>
        </li>
    <?php endfor ?>
    <?php # last page ?>
    <?php if ($pager_pages > floor($this->params->get('pager_links', 10)/2)+1) : ?>
        <?php if ($page < $pager_pages) : ?>
            <li><a href="<?php echo JRoute::_(sprintf('index.php?option=com_maxposter&view=list%s&page=%d', ($query ? '&'.$query : ''), $page+1)) ?>"><?php echo JText::_('JNEXT') ?></a></li>
            <li><a href="<?php echo JRoute::_(sprintf('index.php?option=com_maxposter&view=list%s&page=%d', ($query ? '&'.$query : ''), $pager_pages)) ?>"><?php echo JText::_('JLIB_HTML_END') ?></a></li>
        <?php endif ?>
    <?php endif ?>
    </ul>
    </div>
<?php endif ?>
