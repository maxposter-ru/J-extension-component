<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach ($this->items as $i => $item) : ?>
    <tr class="row<?php echo $i % 2; ?>">
        <td>
            <a href="index.php?option=com_maxposter&amp;task=photo.edit&amp;id=<?php echo $item->id ?>"><?php echo $item->id ?></a>
        </td>
        <td>
            <?php echo JHtml::_('grid.id', $i, $item->id) ?>
        </td>
        <td>
            <a href="index.php?option=com_maxposter&amp;task=photo.edit&amp;id=<?php echo $item->id ?>"><?php echo $item->value ?></a>
        </td>
    </tr>
<?php endforeach; ?>
