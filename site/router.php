<?php
/**
 * @see http://docs.joomla.org/Supporting_SEF_URLs_in_your_component
 */

/**
 * Сборщик роута
 */
function MaxPosterBuildRoute(&$query)
{
    $segments = array();

    if (!empty($query['layout']) && ($query['layout'] == 'default')) {
        unset($query['layout']);
    }

    if (!empty($query['task'])) {
        $segments[] = $query['task'];
        unset($query['task']);
    }

    if (!empty($query['view'])) {
        if (empty($query['Itemid'])) {
            $segments[] = $query['view'];
        } elseif (!in_array($query['view'], array('list', 'car'))) {
            $segments[] = $query['view'];
        }
        unset($query['view']);
    }

    if (!empty($query['vehicle_id'])) {
        $segments[] = $query['vehicle_id'];
        unset($query['vehicle_id']);
    }

    return $segments;
}


/**
 * Разборщик роута
 */
function MaxPosterParseRoute($segments)
{
    $vars = array();

    if (!empty($segments['0']) && empty($segments['1'])) {
        $vars['view'] = 'car';
        $vars['vehicle_id'] = (int) $segments['0'];
    } elseif (!empty($segments['0']) && !empty($segments['1'])) {
        $vars['vehicle_id'] = (int) $segments['1'];
    } else {
        $vars['view'] = 'list';
    }

    return $vars;
}
