<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

// libraries
jimport('maxposter.maxCacheHtmlClient');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

require_once (JPATH_ROOT.DS.'components'.DS.'com_maxposter'.DS.'lib'.DS.'client'.DS.'maxClient.php');
require_once (JPATH_ROOT.DS.'components'.DS.'com_maxposter'.DS.'helpers'.DS.'helper.php');

class JFormFieldFilter extends JFormFieldList
{
    protected $type = 'Filter';

    protected function getClient()
    {
        $client = new maxClient(MaxPosterHelper::getConfig());
        $client->setRequestThemeName('search_form');

        return $client;
    }


    protected function getXml()
    {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
        $client = $this->getClient();

        // кеширование XML
        $cache = JFactory::getCache('com_maxposter', '');
        $cache->setCaching(true);
        $cache->setLifeTime(60);
        $cache->_getStorage()->_lifetime = 60;
        $cacheId = $client->getRequestCacheId();

        if (!$rawXml = $cache->get($cacheId)) {
            $xml = $client->getXml();
            $responceId = $xml->getElementsByTagName('response')->item(0)->getAttribute('id');
            if ('error' != $responceId) {
                list($cacheActualAt, $cacheExpiresAt) = $client->getCacheTimes();
                $cacheLife = (int) $cacheExpiresAt - time(); # кешируем в секундах
                $cache->setLifeTime($cacheLife);
                $cache->_getStorage()->_lifetime = $cacheLife;
                if ($cacheLife > 1) {
                    $cache->store($xml->saveXml(), $cacheId);
                }
            }
        } else {
            $xml = new DOMDocument();
            $xml->loadXML($rawXml);
            $responceId = $xml->getElementsByTagName('response')->item(0)->getAttribute('id');
        }
        $cache->setCaching(false);

        $responceId = $xml->getElementsByTagName('response')->item(0)->getAttribute('id');

        return $xml;
    }


    /**
     * Field name to search in XML & to get available values from
     *
     * @return string
     */
    protected function getField()
    {
        return (string) $this->element['property'];
    }


    // TODO: группировка optgroup
    // TODO: возможно скрипты? вообще что будет при фильтрации по марке 1 и модели от марки 2?
    protected function processResult($result, &$options = array())
    {
        foreach ($result->childNodes as $option) {
            if ($option->tagName == 'optgroup') {
                $label = $option->attributes->getNamedItem('label')->value;
                $opts = array();
                $this->processResult($option, $opts);
                $options = array_merge($options, $opts);
            } elseif ($option->tagName == 'option') {
                $opt = JHtml::_('select.option', trim((string) $option->attributes->getNamedItem('value')->value),
                        JText::alt(trim((string) $option->nodeValue), preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)), 'value', 'text', false);
                $options[] = $opt;
                //$options[$option->attributes->getNamedItem('value')->value] = trim($option->nodeValue);
            }
        }
    }


    public function getOptions()
    {
        $options = array();

        $dom = $this->getXml();
        $xpath = new DomXPath($dom);
        $result = $xpath->query('//search_form//*[@name=\'' . sprintf('search[%s]', $this->getField()) . '\']');
        if (!$this->required) {
            $options[] = '';
        }
        if ($result->length) {
            $this->processResult($result->item(0), &$options);
        }
        reset($options);

        return $options;
    }

}
