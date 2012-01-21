<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output method = "html" encoding="utf-8"/>
  
  <xsl:include href="inc_url.xsl"/>
  
  <xsl:template match="/">
    <div class="maxposter">
      <h1><xsl:text>У нас нет ответа на Ваш запрос</xsl:text></h1>
      <p>Скорее всего Вы запросили данные, которые уже удалены.</p>
      <p>Например, если эта страница отображается в ответ на запрос описания автомобиля, 
        то скорее-всего автомобиль уже продан. 
        Вы можете посмотреть 
        <a>
          <xsl:attribute name="href">
            <xsl:call-template name="url_vehicles" />
          </xsl:attribute>
          <xsl:text>другие автомобили</xsl:text>
        </a>
        , которые находятся в продаже прямо сейчас.
      </p>
    </div>
  </xsl:template>
</xsl:stylesheet>