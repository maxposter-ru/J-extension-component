<?php
/**
 * Автомобиль в списке
 *
 * @param  mixed    $this->vehicle
 */
defined('_JEXEC') or die('Restricted access');


$priceStatus = (string) $this->vehicle->price['status'];
$prevPrice = (string) $this->vehicle->price->previous;
list($width, $height) = explode('x', $this->params->get('view_photo_size_list', '120x90'));
?>
<div class="maxposter-photo">
    <a href="<?php echo JRoute::_(sprintf('index.php?option=com_maxposter&view=car&vehicle_id=%d', $this->vehicle['vehicle_id'])) ?>" title="<?php printf('%s %s', $this->vehicle->mark, $this->vehicle->model) ?>">
        <?php if ((string)$this->vehicle->photo) : ?>
            <img src="<?php echo JURI::root(true), 'media/maxposter/photos/cache/', (strpos($this->params->get('dealer_id'), '_') ? $this->vehicle['dealer_id'] . '/' : ''), $this->vehicle['vehicle_id'], '/', $this->params->get('view_photo_size_list', '120x90'), '/', $this->vehicle->photo['file_name'] ?>" alt="<?php printf('%s %s', $this->vehicle->mark, $this->vehicle->model) ?>" width="<?php echo $width ?>" height="<?php echo $height ?>" />
        <?php else: ?>
            <?php echo JHtml::image('maxposter/no_photo_120x90.gif', sprintf('%s %s', $this->vehicle->mark, $this->vehicle->model), array('width' => $width, 'height' => $height), true) ?>
        <?php endif ?>
        <?php if ($this->params->get('tile_show_special_icon', 0) && ($priceStatus == 'special')) : ?>
            <span class="maxposter-icon-special">&nbsp;</span>
        <?php endif ?>
    </a>
</div>
<div class="maxposter-text">
    <h3><a href="<?php echo JRoute::_(sprintf('index.php?option=com_maxposter&view=car&vehicle_id=%d', $this->vehicle['vehicle_id'])) ?>"><?php printf('%s %s', $this->vehicle->mark, $this->vehicle->model) ?></a><span></span></h3>
    <?php if ($this->params->get('tile_show_price', 1)) : ?>
        <p class="maxposter-price<?php $priceStatus ? ' maxposter-price-' . $priceStatus : ''; ?>">
            <?php switch ($this->vehicle->price->value['unit']) :
                case 'eur': ?>€ <?php break; case 'usd': ?>$ <?php break; ?>
            <?php endswitch ?>
            <?php echo str_replace(' ', '&nbsp;', number_format((string) $this->vehicle->price->value, 0, '.', ' ')) ?>
            <?php if ('rub' == $this->vehicle->price->value['unit']) : ?>
                руб.
            <?php endif ?>
        </p>
    <?php endif ?>
    <?php if ($this->params->get('tile_show_priceold', 0)) : ?>
        <?php if ($priceStatus && $prevPrice) : ?>
            <p class="maxposter-previous-price">
                <?php switch ($this->vehicle->price->value['unit']) :
                    case 'eur': ?>€ <?php break; case 'usd': ?>$ <?php break; ?>
                <?php endswitch ?>
                <?php echo str_replace(' ', '&nbsp;', number_format($prevPrice, 0, '.', ' ')) ?>
                <?php if ('rub' == $this->vehicle->price->value['unit']) : ?>
                    руб.
                <?php endif ?>
            </p>
        <?php else: ?>
            <p class="maxposter-previous-price-placeholder">&nbsp;</p>
        <?php endif ?>
    <?php endif ?>
    <?php if ($this->params->get('tile_show_year', 1)) : ?>
    <p class="maxposter-year"><?php echo $this->vehicle->year ?> г.в.</p>
    <?php endif ?>
</div>
