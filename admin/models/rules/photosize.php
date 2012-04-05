<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla formrule library
jimport('joomla.form.formrule');

/**
 * Form Rule
 */
class JFormRulePhotosize extends JFormRule
{
    /**
     * The regular expression.
     *
     * @access    protected
     * @var        string
     * @since    2.5
     */
    protected $regex = '^[1-9][0-9]{0,3}x[1-9][0-9]{0,3}$';

}
