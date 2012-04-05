<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * View
 */
class MaxposterViewPhoto extends JView
{
    /**
     * display method
     * @return void
     */
    public function display($tpl = null)
    {
        $form = $this->get('Form');
        $item = $this->get('Item');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        $this->form = $form;
        $this->item = $item;

        // Set the toolbar
        $this->addToolBar();

        // Display the template
        parent::display($tpl);
    }

    /**
     * Setting the toolbar
     */
    protected function addToolBar()
    {
        JRequest::setVar('hidemainmenu', true);
        $isNew = ($this->item->id == 0);
        JToolBarHelper::title($isNew ? JText::_('COM_MAXPOSTER_MANAGER_PHOTO_NEW')
                                     : JText::_('COM_MAXPOSTER_MANAGER_PHOTO_EDIT'));
        JToolBarHelper::apply('photo.apply');
        JToolBarHelper::save('photo.save');
        JToolBarHelper::cancel('photo.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
    }

}
