<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('maxposter.maxCacheHtmlClient');

require_once (JPATH_COMPONENT.DS.'lib'.DS.'client'.DS.'maxClient.php');
require_once (JPATH_COMPONENT.DS.'helpers'.DS.'helper.php');

jimport( 'joomla.application.component.controller' );

class MaxPosterController extends JController
{

  /**
   * Контроллер для страницы со списком автомобилей
   */
  function display($cachable = false, $urlparams = false)
  {
    // Определение, какие данные необходимы от Интернет-сервиса
    $client = new maxClient(MaxPosterHelper::getConfig());
    $client->setRequestThemeName('vehicles');
    $client->setRequestParams(array('search' => JRequest::getVar($client->getOption('prefix').'search', array())));
    $client->setPage(JRequest::getInt('page', 1));

    // Добавление css
    MaxPosterHelper::addStyle();

    $document = JFactory::getDocument();
    // Добавление title & description
    $title = 'Автомобили в продаже (стр. '.JRequest::getInt('page', 1).').';
    $document->setTitle($title);
    $document->setDescription($title);
    // Добавление js
    JHTML::script('mt11_linked_models.js', 'components/com_maxposter/assets/js/', true);
    $document->addScriptDeclaration(sprintf('window.addEvent("domready", function(){
      LinkedModel("%1$ssearch_mark_id", "%1$ssearch_model_id");
    });', $client->getOption('prefix')));

    echo $client->getHtml();
  }

  /**
   * Контроллер для страницы с описанием автомобиля
   *
   */
  function car()
  {
    // Определение, какие данные необходимы от Интернет-сервиса
    $client = new maxClient(MaxPosterHelper::getConfig());
    $client->setRequestThemeName(JRequest::getVar('vehicle_id'));

    // Добавление css
    MaxPosterHelper::addStyle();

    $document = &JFactory::getDocument();
    // Добавление title & description
    $title = $client->getCarTitle();
    $document->setTitle($title);
    $document->setDescription($title);
    // Добавление js
    JHTML::script('mt11_2step_photo.js', 'components/com_maxposter/assets/js/', true);
    $document->addScriptDeclaration('window.addEvent("domready", function(){
      PhotoManager("#original", "#thumbnails");
    });');

    echo $client->getHtml();
  }
}
