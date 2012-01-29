<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * List of autos
 */
class MaxposterViewList extends JView
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
    public function setStylesheets()
    {
        # base
        JHtml::stylesheet('maxposter/style.css', array(), true, false, false);
        # client overrides
        JHtml::stylesheet('com_maxposter.css', array(), true);
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

        $JInput = $this->app->input;
        if (1 > $page = $JInput->get('page', 1, 'UINT')) {
            $JInput->set('page', $page = 1);
        }

        $this->assign('page', $page);
        $this->assign('per_page', $params->get('rows_by_page', 20));

        $search = $JInput->get(sprintf('search', $params->get('prefix', '')), array(), 'ARRAY');
        $this->assign('search_params', $search);

        // Определение, какие данные необходимы от Интернет-сервиса
        $client = new maxClient(MaxPosterHelper::getConfig());
        $client->setRequestThemeName('vehicles');
        $client->setRequestParams(array('search' => $search));
        $client->setPage($page);

        // FIXME: другая версия MT, возможно стоит переписать скрипты
        // FIXME: не подключать если на странице нет формы поиска
        // FIXME: если кто-то уже подключил (модуль?) - ?
        // Добавление js
#        JHTML::script('mt11_linked_models.js', 'components/com_maxposter/assets/js/', true);
#        $this->document->addScriptDeclaration(sprintf('window.addEvent("domready", function(){
#          LinkedModel("%1$ssearch_mark_id", "%1$ssearch_model_id");
#        });', $client->getOption('prefix')));

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

        switch ($responceId) {
            case 'error':
                JResponse::setHeader('status', '404 Not Found');
                $params->set('error', 404);
                $this->setTitle('Ошибка 404. У нас нет ответа на Ваш запрос.');
                $cache->setCaching(false);
                $cache->setLifeTime(0);
                $cache->_getStorage()->_lifetime = 0;
                break;
        }
        // TODO: $xml->getElementsByTagName('search_form')->item(0);

        $this->assign('xml', $xml);
        $this->assign('cache_id', $client->getHtmlCacheId());
        $this->assign('cache_lifetime', $cache->_getStorage()->_lifetime);

        $cache->setCaching(false);
        // Display the view
        parent::display($tpl);
    }

}
