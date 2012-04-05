<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * Table class
 */
class MaxposterTableMaxposter extends JTable
{
    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct(&$db)
    {
        parent::__construct('#__maxposter', 'id', $db);
    }


    /**
     * Overload the store method for the Maxposter table.
     *
     * @param    boolean    Toggle whether null values should be updated.
     * @return    boolean    True on success, false on failure.
     * @since    1.6
     */
    public function store($updateNulls = false)
    {
        if ($this->id) {
            unset($this->name);
        } else {
            $this->name = 'photo_size';
        }

        // Size is unique
        $table = JTable::getInstance('Maxposter', 'MaxposterTable');
        if ($table->load(array('name' => 'photo_size', 'value' => $this->value)) && ($table->id != $this->id || $this->id == 0)) {
            $this->setError(JText::_('COM_MAXPOSTER_PHOTO_ERROR_UNIQUE_VALUE'));
            return false;
        }

        return parent::store($updateNulls);
    }

}
