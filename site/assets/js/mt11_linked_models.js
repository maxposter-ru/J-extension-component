var LinkedModel = function(_markList, _modelList){
  
  var $models;
  
  function refreshList() {
    $(_modelList).setProperty('disabled', false).getElements('[class]').each(function(el){el.remove()});
    if ($(_markList).getProperty('value')) {
      $models.getElements('.mark'+$(_markList).getProperty('value')).clone(true, false).inject($(_modelList));
    } else {
      $(_modelList).setProperty('disabled', true);
    }
  };
    
  if ($(_modelList)) {
    var $models = $(_modelList).clone(true, false);
    
    refreshList();
    
    $(_markList).addEvent('change', function(){
      refreshList();
      $(_modelList).setProperty('value', '');
    });
  }
}