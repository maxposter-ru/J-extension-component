<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
?>
<form action="<?php echo JRoute::_('index.php?option=com_maxposter&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="helloworld-form">
    <div class="width-60 fltlft">
        <fieldset class="adminform">
            <legend><?php echo empty($this->item->id) ? JText::_('COM_MAXPOSTER_PHOTO_NEW_PHOTO') : JText::sprintf('COM_MAXPOSTER_PHOTO_EDIT_PHOTO', $this->item->id) ?></legend>
            <ul class="adminformlist">
                <?php foreach($this->form->getFieldset() as $field): ?>
                    <li><?php echo $field->label, $field->input ; ?></li>
                <?php endforeach; ?>
            </ul>
        </fieldset>
    </div>
    <div class="width-40 fltrt">
    </div>
    <div>
        <input type="hidden" name="task" value="photo.edit" />
        <?php echo JHtml::_('form.token') ?>
    </div>
    <div class="clr"></div>
</form>
