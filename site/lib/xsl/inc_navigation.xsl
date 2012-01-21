<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <!--
      Разбивка длинных XML на страницы 
      требует наличия рассчитанных переменных:
       - $count - количество строк всего в XML;
       - $page - номер текущей страницы;
       - $rows - количество строк на одной странице
  -->
  
  <xsl:variable name="pagecount" select="ceiling($count div $rows)"/>

  <xsl:variable name="linkcount" select="9"/>

  <xsl:variable name="startpage">
    <xsl:variable name="teststart" select="$page - floor($linkcount div 2)" />
     <xsl:choose>
        <xsl:when test="($teststart > 1) and ($pagecount >= ($teststart + $linkcount))">
          <xsl:value-of select="$page - floor($linkcount div 2)"/>
        </xsl:when>
        <xsl:when test="(($teststart + $linkcount) > $pagecount)  and (($pagecount - $linkcount) >= 1)">
          <xsl:value-of select="$pagecount - $linkcount"/>
        </xsl:when>
        <xsl:otherwise>
        	<xsl:value-of select="1"/>
        </xsl:otherwise>
      </xsl:choose>
  </xsl:variable>
  
  <xsl:variable name="finishpage">
    <xsl:choose>
      <xsl:when test="$pagecount >= ($startpage + $linkcount)">
        <xsl:value-of select="$startpage + $linkcount"/>
      </xsl:when>
      <xsl:otherwise>
      	<xsl:value-of select="$pagecount"/>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:variable>
  
  <xsl:variable name="filter">
    <xsl:for-each select="/response/search_form/list/option[@selected]">
      <xsl:text disable-output-escaping="yes">&amp;</xsl:text>
      <xsl:value-of select="$prefix"/>
      <xsl:value-of select="../@name" />
      <xsl:text>=</xsl:text>
      <xsl:value-of select="./@value" />
    </xsl:for-each>
    <xsl:for-each select="/response/search_form/field['' != @value]">
      <xsl:text disable-output-escaping="yes">&amp;</xsl:text>
      <xsl:value-of select="$prefix"/>
      <xsl:value-of select="@name"/>
      <xsl:text>=</xsl:text>
      <xsl:value-of select="./@value" />
    </xsl:for-each>
  </xsl:variable>
  
  <xsl:template name="NavigationItem">
    <xsl:param name="currentpage"/>
    <li>
      <xsl:choose>
        <xsl:when test="$page=$currentpage">
          <xsl:value-of select="$currentpage"/>
        </xsl:when>
        <xsl:otherwise>
          <a>
            <xsl:attribute name="href">
              <xsl:call-template name="url_vehicles">
                <xsl:with-param name="page" select="$currentpage"/>
                <xsl:with-param name="filter" select="$filter"/>
              </xsl:call-template>
            </xsl:attribute>
            <xsl:value-of select="$currentpage"/>
          </a>
        </xsl:otherwise>
      </xsl:choose>
    </li>
    
    <xsl:if test="$finishpage > $currentpage">
      <xsl:call-template name="NavigationItem">
        <xsl:with-param name="currentpage" select="$currentpage+1"/>
      </xsl:call-template>
    </xsl:if>
  </xsl:template>

  <xsl:template name="Navigation">
    <xsl:if test="$pagecount>1">
      <div class="pagination">
        <xsl:text>Страницы: </xsl:text>
        <ul>
          <xsl:if test="$page > 1">
            <xsl:if test="$pagecount > 10">
              <li class="first">
                <a>
                  <xsl:attribute name="href">
                    <xsl:call-template name="url_vehicles">
                      <xsl:with-param name="page" select="1"/>
                      <xsl:with-param name="filter" select="$filter"/>
                    </xsl:call-template>
                  </xsl:attribute>
                  <xsl:text>|&lt;</xsl:text>
                </a>
              </li>
            </xsl:if>
            <li class="prev">
              <a>
                <xsl:attribute name="href">
                  <xsl:call-template name="url_vehicles">
                    <xsl:with-param name="page" select="$page - 1"/>
                    <xsl:with-param name="filter" select="$filter"/>
                  </xsl:call-template>
                </xsl:attribute>
                <xsl:text>&lt;</xsl:text>
              </a>
            </li>
          </xsl:if>
          
          <xsl:call-template name="NavigationItem">
            <xsl:with-param name="currentpage" select="$startpage"/>
          </xsl:call-template>
          
          <xsl:if test="$pagecount > $page">
            <li class="next">
              <a>
                <xsl:attribute name="href">
                  <xsl:call-template name="url_vehicles">
                    <xsl:with-param name="page" select="$page + 1"/>
                    <xsl:with-param name="filter" select="$filter"/>
                  </xsl:call-template>
                </xsl:attribute>
                <xsl:text>&gt;</xsl:text>
              </a>
            </li>
            <xsl:if test="$pagecount > 10">
              <li class="last">
                <a>
                  <xsl:attribute name="href">
                    <xsl:call-template name="url_vehicles">
                      <xsl:with-param name="page" select="$pagecount"/>
                      <xsl:with-param name="filter" select="$filter"/>
                    </xsl:call-template>
                  </xsl:attribute>
                  <xsl:text>&gt;|</xsl:text>
                </a>
              </li>
            </xsl:if>
          </xsl:if>
        </ul>
      </div>
    </xsl:if>
  </xsl:template>
</xsl:stylesheet>