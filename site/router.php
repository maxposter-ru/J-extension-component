<?php
/**
 * @see http://docs.joomla.org/Supporting_SEF_URLs_in_your_component
 */

/**
 * Сборщик роута
 */
function maxposterBuildRoute(&$query)
{
    $segments = array();

    if (!empty($query['task'])) {
        $segments[] = $query['task'];
        unset($query['task']);

        if (!empty($query['vehicle_id'])) {
            $segments[] = $query['vehicle_id'];
            unset($query['vehicle_id']);
        }
    }

    return $segments;
}


/**
 * Разборщик роута
 */
function maxposterParseRoute($segments)
{
    $vars = array();

    if (!empty($segments['0']) && !empty($segments['1']) && ($segments['0'] == 'car')) {
        $vars['task'] = 'car';
        $vars['vehicle_id'] = (int) $segments['1'];
    }

   return $vars;
}
