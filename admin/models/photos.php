<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * Model
 */
class MaxposterModelPhotos extends JModelList
{
    /**
     * Method to build an SQL query to load the list data.
     *
     * @return    string    An SQL query
     */
    protected function getListQuery()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id,value');
        $query->from('#__maxposter');
        $query->where('name = \'photo_size\'');

        return $query;
    }

}
