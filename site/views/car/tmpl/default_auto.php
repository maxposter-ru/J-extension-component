<?php
/**
 * Авто
 *
 * @param  DomDocument  $this->xml
 * @param  string       $this->cache_id
 * @param  string       $this->cache_lifetime
 * @param  ?            $this->params           Параметры компонента + настройки в меню
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<?php # Заголовок ?>
<h1 class="componentheading"><?php echo $this->vhelper->getName() ?></h1>
<?php if ($uin = $this->vhelper->get('./uin')) : ?>
<span style="display:none;"><?php echo '<!-- UIN:', $uin, ' -->'; ?></span>
<?php endif ?>

<?php # Цена ?>
<span class="maxposter-auto-price">Цена: <?php echo $this->vhelper->getPrice() ?></span>

<?php # Основные характеристики ?>
<div id="maxposter-auto-info" class="maxposter-auto-info">
    <h2>Основные характеристики</h2>
    <dl>
        <dt>Год выпуска:</dt>
        <dd><?php echo $this->vhelper->get('./year') ?></dd>
        <dt>Двигатель:</dt>
        <dd><?php
            echo $this->vhelper->get('./engine/type');
            ?>, <?php
            echo $this->vhelper->get('./engine/volume');
            ?>&nbsp;см&sup3;<?php
            if ($power = $this->vhelper->get('./engine/power'))
                echo ', ', $power, '&nbsp;л.с.';
        ?></dd>
        <dt>Кузов:</dt>
        <dd class="maxposter-auto-color-<?php echo $this->vhelper->get('./body/color/@body_color_id') ?>"><?php
            echo $this->vhelper->get('./body/type');
            ?>, цвет&nbsp;<?php
            echo $this->vhelper->get('./body/color');
        ?></dd>
        <dt>Коробка передач:</dt>
        <dd><?php echo $this->vhelper->get('./gearbox/type') ?></dd>
        <dt>Привод:</dt>
        <dd><?php echo $this->vhelper->get('./drive/type') ?></dd>
        <dt>Пробег:</dt>
        <dd><?php echo $this->vhelper->getMileage() ?></dd>
        <dt>Руль:</dt>
        <dd><?php echo $this->vhelper->get('./steering_wheel/place') ?></dd>
        <dt>Состояние:</dt>
        <dd><?php echo $this->vhelper->get('./condition') ?></dd>
        <dt>Наличие<?php # FIXME: добавить опцию в админку
            if ($this->params->get('show_field_customs', 0)) :
                ?>&nbsp;/&nbsp;таможня<?php
            endif;
        ?>:</dt>
        <dd><?php echo $this->vhelper->get('./availability') ?>
            <?php if ($this->params->get('show_field_customs', 0)) : ?>
                &nbsp;/&nbsp;
                <?php echo $this->vhelper->get('./price/without_customs') ? 'растаможен' : '<span class="maxposter-auto-customs">не&nbsp;растаможен</span>'; ?>
            <?php endif ?>
        </dd>
        <?php if ($vin = $this->vhelper->get('./vin')) : ?>
            <dt>VIN:</dt>
            <dd><?php echo $vin ?></dd>
        <?php endif ?>
        <?php if ($pts = $this->vhelper->get('./pts_owner_count')) : ?>
            <dt>Кол-во хозяев по ПТС:</dt>
            <dd><?php echo $pts ?></dd>
        <?php endif ?>
    </dl>
</div>

<?php # Фотографии ?>
<?php if ($primary = (string) $this->vhelper->get('./photos/photo[position() = 1]/@file_name')) : ?>
    <?php $isMultiDealer = strpos($this->params->get('dealer_id'), '_') ?>
    <div id="maxposter-auto-photos">
        <div id="maxposter-auto-photo-big">
            <a href="<?php echo $this->vhelper->getPhotoPath($primary, 'source', $isMultiDealer) ?>" class="modal">
                <img src="<?php echo $this->vhelper->getPhotoPath($primary, array('640', '480'), $isMultiDealer) ?>" width="564" height="423" alt="<?php echo $this->escape($this->vhelper->getName()) ?>" title="<?php echo $this->escape($this->vhelper->getName()) ?>" />
            </a>
        </div>
        <?php if ($photos = (array) $this->vhelper->get('./photos/photo/@file_name')) : ?>
            <div id="maxposter-auto-photos-thumbnails">
                <?php $i=0; foreach ($photos as $file) : ?>
                    <a href="<?php echo $this->vhelper->getPhotoPath($file, 'source', $isMultiDealer) ?>"<?php echo ((3 == ($i % 4)) ? ' class="last"' : '') ?>>
                        <img src="<?php echo $this->vhelper->getPhotoPath($file, array('120', '90'), $isMultiDealer) ?>" width="120" height="90" alt="<?php echo $this->escape($this->vhelper->getName()) ?>" title="<?php echo $this->escape($this->vhelper->getName()) ?>" />
                    </a>
                <?php $i++;endforeach ?>
            </div>
        <?php endif ?>
    </div>
<?php endif ?>

<?php # опции авто ?>
<?php if ($options = (array) $this->vhelper->getOptions()) : ?>
<div class="maxposter-auto-features">
    <h2>Комплектация</h2>
    <ul>
        <?php foreach ($options as $option) : ?>
            <li><?php echo $this->escape($option) ?></li>
        <?php endforeach ?>
    </ul>
</div>
<?php endif ?>

<?php # Описание ?>
<?php if ($description = $this->vhelper->getDescription()) : ?>
    <div class="maxposter-auto-description">
        <h2>Дополнительная информация</h2>
        <p><?php echo $description ?></p>
    </div>
<?php endif ?>

<?php # Контакты и место осмотра ?>
<?php if ($loc = $this->vhelper->getLocation()) : ?>
    <h2>Место осмотра</h2>
    <p><?php echo $loc ?></p>
<?php endif ?>
<?php if ($phones = $this->vhelper->getPhones(true)) : ?>
    <h2>Телефоны</h2>
    <p><?php echo $phones ?></p>
<?php endif ?>
