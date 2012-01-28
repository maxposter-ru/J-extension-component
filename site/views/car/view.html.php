<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Single auto
 */
class MaxposterViewCar extends JView
{
    protected $app = null;
    protected $document; // @see JView

    public function __construct()
    {
        parent::__construct();
        $this->app = JFactory::getApplication();
    }


    /**
     * Replace title with new one
     *
     * @param  string  $title
     * @return void
     */
    protected function setTitle($title)
    {
        if ($this->app->getCfg('sitename_pagetitles', 0) == 1) {
            $title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
        } elseif ($this->app->getCfg('sitename_pagetitles', 0) == 2) {
            $title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
        }
        $this->document->setTitle($title);
    }


    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument()
    {
        $params = $this->app->getParams();

        $this->setTitle($params->get('page_title'));
        if ($params->get('menu-meta_description')) {
            $this->document->setDescription($params->get('menu-meta_description'));
        }
        if ($params->get('menu-meta_keywords')) {
            $this->document->setMetadata('keywords', $params->get('menu-meta_keywords'));
        }
        if ($params->get('robots')) {
            $this->document->setMetadata('robots', $params->get('robots'));
        }
    }


    /**
     * Добавляет стили
     *
     * @return void
     */
    protected function setStylesheets()
    {
        # base
        JHtml::stylesheet('maxposter/style.css', array(), true, false, false);
        # client overrides
        JHtml::stylesheet('com_maxposter.css', array(), true);
    }


    /**
     * Добавляет стили
     *
     * @return void
     */
    protected function setJavascripts()
    {
        # base
        JHtml::script('maxposter/gallery.js', false, true, false, false);
        # client overrides
        JHtml::script('com_maxposter.gallery.js', false, true);
        $this->document->addScriptDeclaration("window.addEvent('load', function(){
            MaxGallery.init($('maxposter-auto-photo-big'), $$('#maxposter-auto-photos-thumbnails'));
        });");
    }


    /**
     * Добавляет скрипты для всплывающих окошек
     *
     * @return void
     */
    protected function setModals()
    {
        JHTML::_('behavior.framework',true);
        $uncompressed = JFactory::getConfig()->get('debug') ? '-uncompressed' : '';
        JHTML::_('script','system/modal'.$uncompressed.'.js', true, true);
        JHTML::_('stylesheet','media/system/css/modal.css');
    }


    /**
     * View
     *
     * @param  string  $tpl
     * @return void
     */
    function display($tpl = null)
    {
        $params = $this->app->getParams();
        $this->assignRef('params', $params);

        $this->setDocument();
        $this->setStylesheets();
        $this->setModals();
        $this->setJavascripts();

        $JInput = $this->app->input;

        // Определение, какие данные необходимы от Интернет-сервиса
        $client = new maxClient(MaxPosterHelper::getConfig());
        $client->setRequestThemeName($JInput->get('vehicle_id', null, 'UINT'));

        // кеширование XML
        $cache = JFactory::getCache('com_maxposter', '');
        $cache->setCaching(true);
        $cache->setLifeTime(60);
        $cache->_getStorage()->_lifetime = 60;
        $cacheId = $client->getRequestCacheId();

        if (!$rawXml = $cache->get($cacheId)) {
            $xml = $client->getXml();
            list($cacheActualAt, $cacheExpiresAt) = $client->getCacheTimes();
            $cacheLife = (int) $cacheExpiresAt - time(); # кешируем в секундах
            $cache->setLifeTime($cacheLife);
            $cache->_getStorage()->_lifetime = $cacheLife;
            if ($cacheLife > 1) {
                $cache->store($xml->saveXml(), $cacheId);
            }
        } else {
            $xml = new DOMDocument();
            $xml->loadXML($rawXml);
        }

        $responceId = $xml->getElementsByTagName('response')->item(0)->getAttribute('id');
        switch ($responceId) {
            case 'error':
                JResponse::setHeader('status', '404 Not Found');
                $params->set('error', 404);
                $this->setTitle('Ошибка 404. У нас нет ответа на Ваш запрос.');
                break;
        }

        $vehicle = $xml->getElementsByTagName('vehicle')->item(0);
        $this->setTitle(sprintf(
            '%s %s %4d г.в.',
            $vehicle->getElementsByTagName('mark')->item(0)->nodeValue,
            $vehicle->getElementsByTagName('model')->item(0)->nodeValue,
            $vehicle->getElementsByTagName('year')->item(0)->nodeValue
        ));
#        // Добавление js
#        JHTML::script('mt11_2step_photo.js', 'components/com_maxposter/assets/js/', true);
#        $document->addScriptDeclaration('window.addEvent("domready", function(){
#            PhotoManager("#original", "#thumbnails");
#        });');

        $this->assign('xml', $xml);
        //$this->addHelperPath(JPATH_COMPONENT . '/helper');
        $this->loadHelper('vehicle');
        $this->assignRef('vhelper', new MaxPosterVehicleHelper($xml, $vehicle));
        $this->assign('cache_id', $client->getHtmlCacheId());
        $this->assign('cache_lifetime', $cache->_getStorage()->_lifetime);

        $cache->setCaching(false);
        // Display the view
        parent::display($tpl);
    }

}
