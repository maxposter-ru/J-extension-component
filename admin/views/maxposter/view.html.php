<?php
/**
* MaxPoster Component Administrator View
*
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
class MaxposterViewMaxposter extends JView
{
  /**
   * Maxposter view display method
   *
   * @return void
   **/
  function display( $tpl = null )
  {
    JToolBarHelper::title(JText::_('COM_MAXPOSTER_MANAGER_MAXPOSTER'));

    JToolBarHelper::preferences('com_maxposter');

    parent::display( $tpl );
  }
}
