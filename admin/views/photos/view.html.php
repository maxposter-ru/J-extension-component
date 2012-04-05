<?php
/**
 * MaxPoster Component Administrator View
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

/**
 * Maxposter Administrator View
 *
 * @package com_maxposter
 * @subpackage components
 */
class MaxposterViewPhotos extends JView
{
    /**
     * Maxposter view display method
     *
     * @return void
     **/
    function display( $tpl = null )
    {
        $this->addToolBar();

        $items = $this->get('Items');
        $pagination = $this->get('Pagination');

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        $this->items = $items;
        $this->pagination = $pagination;

        parent::display($tpl);
    }


    /**
     * Setting the toolbar
     */
    protected function addToolBar()
    {
        JToolBarHelper::title(JText::_('COM_MAXPOSTER_MANAGER_MAXPOSTER'));

        JToolBarHelper::deleteList('', 'photos.delete');
        JToolBarHelper::editList('photo.edit');
        JToolBarHelper::addNew('photo.add');
        JToolBarHelper::divider();
        JToolBarHelper::preferences('com_maxposter');
    }

}
