<?php
/**
 * Автомобиль в списке
 *
 * @param  mixed    $this->vehicle
 */
defined('_JEXEC') or die('Restricted access');

?>
<div class="maxposter-photo">
    <a href="<?php echo JRoute::_(sprintf('index.php?option=com_maxposter&view=car&vehicle_id=%d', $this->vehicle['vehicle_id'])) ?>">
        <?php if ((string)$this->vehicle->photo) : ?>
            <img src="<?php echo JURI::root(true), 'media/maxposter/photos/cache/', (strpos($this->params->get('dealer_id'), '_') ? $this->vehicle['dealer_id'] . '/' : ''), $this->vehicle['vehicle_id'], '/120x90/', $this->vehicle->photo['file_name'] ?>" alt="<?php printf('%s %s', $this->vehicle->mark, $this->vehicle->model) ?>" width="120" height="90" />
        <?php else: ?>
            <?php echo JHtml::image('maxposter/no_photo_120x90.gif', sprintf('%s %s', $this->vehicle->mark, $this->vehicle->model), array('width' => '120', 'height' => '90'), true) ?>
        <?php endif ?>
    </a>
</div>
<div class="maxposter-text">
    <h3><a href="<?php echo JRoute::_(sprintf('index.php?option=com_maxposter&view=car&vehicle_id=%d', $this->vehicle['vehicle_id'])) ?>"><?php printf('%s %s', $this->vehicle->mark, $this->vehicle->model) ?></a></h3>
    <p class="maxposter-price">
        Цена:
        <?php switch ($this->vehicle->price->value['unit']) :
            case 'eur': ?>€ <?php break; case 'usd': ?>$ <?php break; ?>
        <?php endswitch ?>
        <?php echo str_replace(' ', "&nbsp;", number_format((string) $this->vehicle->price->value, 0, '.', ' ')) ?>
        <?php if ('rub' == $this->vehicle->price->value['unit']) : ?>
            руб.
        <?php endif ?>
    </p>
    <p><?php
        printf(
            '%1$s, %2$4d&nbspгода&nbsp;выпуска, двигатель&nbsp;%3$s объёмом&nbsp;%4$d&nbsp;см&sup3;, пробег&nbsp;%5$s&nbsp;%6$s, %7$s&nbsp;КПП, цвет&nbsp;кузова&nbsp;%8$s. Состояние&nbsp;%9$s.',
            $this->vehicle->body->type, # корпус
            $this->vehicle->year, # год
            $this->vehicle->engine->type,
            $this->vehicle->engine->volume,
            str_replace(' ', "&nbsp;", number_format((string) $this->vehicle->mileage->value, 0, '.', ' ')),
            ((string)$this->vehicle->mileage->value['unit'] == 'mile' ? 'миль' : 'км.'),
            $this->vehicle->gearbox->type,
            $this->vehicle->body->color,
            $this->vehicle->condition
        );
    ?></p>
</div>
