<?php
class MaxPosterVehicleHelper
{
    /**
     * Конструктор
     */
    public function __construct(DomDocument $doc, DomElement $vehicle)
    {
        $this->document = $doc;
        $this->vehicle = $vehicle;
        $this->xpath = new DomXPath($this->document);
    }


    private function getXmlVehicle()
    {
        return $this->vehicle;
    }


    private function getOptionsQueries()
    {
        return array(
            './abs'                        => 'Антиблокировочная система (АБС)',
            './asr'                        => 'Антипробуксовочная система',
            './esp'                        => 'Система курсовой стабилизации',
            './parktronic'                 => 'Парктроник',
            './airbag'                     => 'Подушки безопасности:',
            './alarm_system'               => 'Охранная система',
            './central_lock'               => 'Центральный замок',
            './nav_system'                 => 'Навигационная система',
            './light_alloy_wheels'         => 'Легкосплавные диски',
            './sensors/rain'               => 'Датчик дождя',
            './sensors/light'              => 'Датчик света',
            './headlights/washer'          => 'Омыватель фар',
            './headlights/xenon'           => 'Ксеноновые фары',
            './compartment'                => 'Салон:',
            './windows/tinted'             => 'Тонированные стекла',
            './hatch'                      => 'Люк',
            './engine/gas_equipment'       => 'Газобалонное оборудование',
            './cruise_control'             => 'Круиз-контроль',
            './trip_computer'              => 'Бортовой компьютер',
            './steering_wheel/power'       => 'Усилитель рулевого управления:',
            './steering_wheel/adjustment'  => 'Регулировка руля:',
            './steering_wheel/heater'      => 'Обогрев руля',
            './mirrors/power'              => 'Электрозеркала',
            './mirrors/defroster'          => 'Обогрев зеркал',
            './windows/power'              => 'Электростеклоподъемники:',
            './seats/heater'               => 'Обогрев сидений',
            './seats/driver_adjustment'    => 'Регулировка сиденья водителя:',
            './seats/passanger_adjustment' => 'Электропривод сиденья пассажира',
            './climate_control'            => 'Управление климатом:',
            './audio'                      => 'Стереосистема:',
        );
    }


    /**
     * Название авто
     *
     * @return string
     */
    public function getName()
    {
        $vehicle = $this->getXmlVehicle();

        return sprintf(
            '%s %s',
            trim($vehicle->getElementsByTagName('mark')->item(0)->nodeValue),
            trim($vehicle->getElementsByTagName('model')->item(0)->nodeValue)
        );
    }


    /**
     * Получить цену авто
     *
     * @param  boolean  $inRub
     * @return string
     */
    public function getPrice($inRub = false)
    {
        $prefix = '';
        $suffix = '';
        $priceNode = $this->getXmlVehicle()->getElementsByTagName('price')->item(0);
        $priceValueNode = $priceNode->getElementsByTagName('value')->item(0);

        if ($inRub) {
            $priceNum = $priceValueNode->getAttribute('rub_price');
            $suffix = '&nbsp;руб.';
        } else {
            switch ($priceValueNode->getAttribute('unit')) {
                case 'eur':
                    $prefix = '€&nbsp;';
                    break;
                case 'usd':
                    $prefix = '$&nbsp;';
                    break;
                case 'rub':
                    $suffix = '&nbsp;руб.';
                    break;
            }
            $priceNum = $priceValueNode->nodeValue;
        }

        $priceNum = str_replace(' ', "&nbsp;", number_format($priceNum, 0, '.', ' '));

        return trim(sprintf('%2$s%1$s%3$s', $priceNum, $prefix, $suffix));
    }


    /**
     * Пробег
     *
     * @return string
     */
    public function getMileage()
    {
        $suffix = '';
        $node = $this->getXmlVehicle()->getElementsByTagName('mileage')->item(0)->getElementsByTagName('value')->item(0);
        switch ($node->getAttribute('unit')) {
            case 'mile':
                $suffix = '&nbsp;миль';
                break;
            case 'km':
            default:
                $suffix = '&nbsp;км.';
                break;
        }

        return sprintf('%s%s', str_replace(' ', '&nbsp;', number_format($node->nodeValue, 0, '.', ' ')), $suffix);
    }


    /**
     * Получить опции авто
     *
     * @return array
     */
    public function getOptions()
    {
        $options = array();
        foreach ($this->getOptionsQueries() as $xQuery => $label) {
            $items = $this->xpath->query($xQuery, $this->getXmlVehicle());
            if ($items->length) {
                $item = $this->xpath->query($xQuery, $this->vehicle)->item(0)->nodeValue;
                $options[] = $label . ($item ? ' ' . trim($item) : '');
            }
            unset($items, $item);
        }

        return $options;
    }


    /**
     * Описание авто
     *
     * @return string
     */
    public function getDescription()
    {
        $description = $this->getXmlVehicle()->getElementsByTagName('description');
        $value = '';
        if (0 < $description->length) {
            $value = trim($description->item(0)->nodeValue);
            if (function_exists('mb_ereg_replace')) {
                mb_regex_encoding('utf-8');
                $value = mb_ereg_replace("(\n|\r\n)", '<br />', $value, 'm');
                $value = mb_ereg_replace("\r", '', $value, 'm');
            } elseif (function_exists('preg_replace')) {
                $value = preg_replace(array("/(\n|\r\n)/um", "/\r/"), array('<br />', ''), $value);
            } else {
                $value = str_replace(array("\r", "\n"), array('', '<br />'), $value);
            }
        }

        return $value;
    }


    /**
     * Место осмотра
     *
     * @return string
     */
    public function getLocation()
    {
        $place = '';
        $places = $this->getXmlVehicle()->getElementsByTagName('inspection_place');
        if ($places->length) {
            $place = trim($places->item(0)->nodeValue);
        }

        return $place;
    }


    /**
     * Контакты
     *
     * @param  boolean  $asString
     * @return array
     */
    public function getPhones($asString = false)
    {
        $q = $this->xpath->query('contact/phone', $this->getXmlVehicle());
        $phones = array();
        if ($length = $q->length) {
            for ($i=0;$i<$length;$i++) {
                $phones[] = $q->item($i)->nodeValue;
            }
        }

        if ($asString) {
            $phones = implode(', ', $phones);
        }

        return $phones;
    }


    /**
     * Путь к фото
     *
     * @param  string  $fileName
     * @param  mixed   $size
     * @param  boolean $isMultiDealer
     * @return string
     */
    public function getPhotoPath($fileName, $size, $isMultiDealer = false)
    {
        $vehicle = $this->getXmlVehicle();
        $url = JURI::root($pathOnly = false) . 'media/maxposter/photos/cache/';
        if ($isMultiDealer) {
            $url .= $vehicle->getAttribute('dealer_id') . '/';
        }
        $url .= $vehicle->getAttribute('vehicle_id') . '/';

        if (is_array($size) or (is_string($size) && ($size != 'source'))) {
            $size = (array) $size;
            $sizeCnt = count($size);
            if ($sizeCnt == 1) {
                list($size['0'], $size['1']) = explode('x', $size);
            }
            $url .= (int) $size['0'] . 'x' . (int) $size['1'] . '/';
        } else {
            $url .= 'source/';
        }

        return $url . $fileName;
    }


    /**
     * Получить значение выбранного элемента
     *
     * @param  mixed
     * @return string
     */
    private function _getString($element)
    {
        $text = '';
        switch (get_class($element)) {
            case 'DOMAttr':
                $text = $element->value;
                break;
            case 'DOMElement':
                $text = $element->nodeValue;
        }

        return $text;
    }


    /**
     * Получить результат xPath запроса
     *
     * @param  string  $xpath
     * @return mixed   string or array of strings or false
     */
    public function get($xpath)
    {
        $res = $this->xpath->query($xpath, $this->getXmlVehicle());
        $ret = false;
        if (1 < $res->length) {
            $ret = array();
            for ($i=0;$i<$res->length;$i++) {
                $ret[] = $this->_getString($res->item($i));
            }
        } elseif (0 < $res->length) {
            $ret = $this->_getString($res->item(0));
        }

        return $ret;
    }

}
