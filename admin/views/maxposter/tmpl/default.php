<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
?>
<div class="adminform">
    <h3><?php echo JText::_('JOPTIONS') ?></h3>
    <p><?php echo JText::_('Задайте параметры компонента в панели выше') ?></p>
    <p>
        <a class="modal" href="index.php?option=com_config&amp;view=component&amp;component=com_maxposter&amp;tmpl=component" rel="{handler: 'iframe', size: {x: 875, y: 550}, onClose: function() {}}">
            <?php echo JText::_('JACTION_ADMIN') ?>
        </a>
    </p>
    <p>
        <a href="index.php?option=com_maxposter&view=photos">
            <?php echo JText::_('Изменить доступные размеры фотографий') ?>
        </a>
    </p>
</div>
<div class="clr"></div>
