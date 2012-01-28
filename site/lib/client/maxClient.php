<?php
/**
 * Клиент
 */
class maxClient extends maxCacheHtmlClient
{
    protected $xslParams = array();

  /**
   * Добавление к хэшу xsl-параметров, чтобы кэш страницы 1 отличался от кэша
   * страницы №2 и кэш описния авто с активным фото №1 отличался от кэша
   * с активным фото 2 при отключенном JS
   *
   * @param unknown_type $_themeName
   * @return unknown
   */
  protected function getHtmlCacheHashKey($_themeName)
  {
    return parent::getHtmlCacheHashKey($_themeName).$this->getRequestParamsAsString($this->xslParams);
  }

  /**
   * Добавление переменной в XSLT шаблон
   *
   * @param DOMDocument $_xsl
   * @param DOMNode $_root
   * @param string $_name
   * @param string $_value
   * @return DOMDocument
   */
  protected function appendVariable(DOMDocument $_xsl, DOMNode $_root, $_name, $_value)
  {
    $newNode = $_xsl->createElementNS('http://www.w3.org/1999/XSL/Transform', 'xsl:variable', $_value);
    $attr = $_xsl->createAttribute('name');
    $attr->appendChild($_xsl->createTextNode($_name));
    $newNode->appendChild($attr);
    $_root->appendChild($newNode);

    return $_xsl;
  }

  /**
   * Установка специфических для темы параметров
   *
   * @param DOMDocument $_xsl
   * @return DOMDocument
   */
  protected function setXslParams(DOMDocument $_xsl)
  {
    // Корневой элемент, в который добавляются переменные
    $root = $_xsl->getElementsByTagName('stylesheet')->item(0);

    //Установка общих параметров для XSLT-преобразования
    $xslParams = array(
      'url_vehicles' => $this->getOption('url_vehicles'),
      'url_vehicle' => $this->getOption('url_vehicle'),
      'url_photo' => $this->getOption('url_photo'),
      'url_empty_thumbnail' => $this->getOption('url_empty_thumbnail'),
      'prefix' => $this->getOption('prefix'),
      'dealer_id' => $this->getOption('dealer_id'),
    );
    foreach ($xslParams as $name => $value)
    {
      $_xsl = $this->appendVariable($_xsl, $root, $name, $value);
    }

    switch ($this->getResponseThemeName())
    {
      case 'vehicles':
        // Добавление номера страницы и количество авто на странице
          $_xsl = $this->appendVariable($_xsl, $root, 'page', $this->xslParams['page']);
          $_xsl = $this->appendVariable($_xsl, $root, 'rows', $this->getOption('rows_by_page'));
        break;
    }

    return $_xsl;
  }

  /**
   * Метод перекрыт для добавления параметров, используемых при XSL-преобразовании
   *
   * @param unknown_type $_xstlName
   * @return unknown
   */
  protected function getXslDom($_xstlName)
  {
    $xsl = parent::getXslDom($_xstlName);
    $xsl = $this->setXslParams($xsl);
    return $xsl;
  }

  /**
   * Установка заголовка ответа 404 или 500 в зависимости от типа ошибки
   */
  protected function setErrorHeader()
  {
    // Сделать исключения Joomla
  }

  /**
   * Метод перекрыт для возвращения заголовков об ошибке
   *
   * @param maxException $_e  Исключение
   */
  protected function setErrorXml(maxException $_e)
  {
    parent::setErrorXml($_e);
    $this->setErrorHeader();
  }

  /**
   * Метод перекрыт для валидации значений запроса в максимально ранний момент
   */
  protected function loadXml()
  {
    parent::loadXml();

    // Валидация значений, используемых при XSLT-преобразовании ответа
    switch ($this->getResponseThemeName())
    {
      case 'vehicles':
      case 'full_vehicles':
        // Валидация номера страницы на максимальное значение
        $pager = $this->xml->getElementsByTagName('pager');
        /*
        $maxPage = ceil($pager->length / $this->getOption('rows_by_page'));
        $maxPage = ($maxPage < 1) ? 1 : $maxPage;
        */
        $maxPage = (int) ceil($pager->item(0)->getAttribute('items_total') / $this->getOption('rows_by_page'));
        if ($maxPage < $this->xslParams['page']) {
            //throw maxException::getException(maxException::ERR_404);
        }
        break;
      case 'error':
        $this->setErrorHeader();
        break;
    }
  }

  public function setPage($_page)
  {
    $this->xslParams['page'] = (int) $_page;
    $this->setGetParameters(array(
        'page'      => (int) ceil($this->xslParams['page'] / ($this->getOption('rows_by_request') / $this->getOption('rows_by_page'))),
        'page_size' => (int) $this->getOption('rows_by_request'),
    ));
  }

  public function getCarTitle()
  {
    $this->getXml();
    if ('vehicle' == $this->getResponseThemeName()) {
        $ret = $this->getXml()->getElementsByTagName('mark')->item(0)->nodeValue.' '
                  .$this->getXml()->getElementsByTagName('model')->item(0)->nodeValue.' '
                  .$this->getXml()->getElementsByTagName('year')->item(0)->nodeValue.' г.в.'
             ;
    } elseif ('error' == $this->getResponseThemeName()) {
        $ret = 'Ошибка 404. У нас нет ответа на Ваш запрос.';
    } else {
      throw maxException::getException(maxException::ERR_404);
    }
    return $ret;
  }


    /**
     * Идентификатор запроса
     *
     * @return string
     */
    public function getRequestCacheId()
    {
        return $this->getXmlCacheHashKey($this->getRequestThemeName());
    }


    /**
     * Идентификатор запроса
     *
     * @return string
     */
    public function getHtmlCacheId()
    {
        return $this->getHtmlCacheHashKey($this->getRequestThemeName());
    }


    /**
     * Время жизни кеша
     *
     * @return array
     */
    public function getCacheTimes()
    {
        return $this->getResponseHeaders();
    }

}
