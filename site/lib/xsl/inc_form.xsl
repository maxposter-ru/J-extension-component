<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template match="search_form">
    <div class="search">
      <form method="post" id="search_form">
        <xsl:attribute name="action">
          <xsl:call-template name="url_vehicles" />
        </xsl:attribute>
        <xsl:call-template name="hidden_field">
          <xsl:with-param name="element" select="./*[@name='search[price][unit]']"/>
          <xsl:with-param name="value" select="'rub'"/>
        </xsl:call-template>
        
        <h2>Параметры поиска</h2>
        <div class="search_form">
          <table>
            <tr class="fields">
              <xsl:call-template name="field">
                <xsl:with-param name="label" select="'Марка'"/>
                <xsl:with-param name="element" select="./*[@name='search[mark_id]']"/>
              </xsl:call-template>
              <td class="between"></td>
              <th>
                <label for="{$prefix}{translate(./*[@name='search[year][from]']/@name, '[]','_')}">Год выпуска</label>
              </th>
              <td class="right">
                <div class="half">
                  <xsl:text>с </xsl:text>
                  <xsl:apply-templates select="./*[@name='search[year][from]']" />
                </div>
                <div class="half">
                  <xsl:text> по </xsl:text>
                  <xsl:apply-templates select="./*[@name='search[year][to]']" />
                </div>
                <xsl:call-template name="error">
                  <xsl:with-param name="element" select="./*[@name='search[year][from]']"/>
                </xsl:call-template>
              </td>
            </tr>
            <tr class="fields">
              <xsl:call-template name="field">
                <xsl:with-param name="label" select="'Модель'"/>
                <xsl:with-param name="element" select="./*[@name='search[model_id]']"/>
                <xsl:with-param name="disabled" select="1"/>
              </xsl:call-template>
              <td class="between"></td>
              <th>
                <label for="{$prefix}{translate(./*[@name='search[price][from]']/@name, '[]','_')}">Цена</label>
              </th>
              <td class="right">
                <div class="half">
                  <xsl:text>от </xsl:text>
                  <xsl:apply-templates select="./*[@name='search[price][from]']" />
                </div>
                <div class="half">
                  <xsl:text> до </xsl:text>
                  <xsl:apply-templates select="./*[@name='search[price][to]']" />
                </div>
                <div class="suffix">&#160;руб.</div>
                <xsl:call-template name="error">
                  <xsl:with-param name="element" select="./*[@name='search[price][from]']"/>
                </xsl:call-template>
              </td>
            </tr> 
            <tr class="control">
              <td colspan="5">
                <input type="submit" value="Найти" class="button"/>
                <a id="clear_filter">
                  <xsl:attribute name="href">
                    <xsl:call-template name="url_vehicles" />
                  </xsl:attribute>
                  <xsl:text>Сбросить</xsl:text>
                </a>
              </td>
            </tr>
          </table>
        </div>
      </form>
    </div>
    <br class="clear"/>
  </xsl:template>
  
  <xsl:template name="field">
    <xsl:param name="label"/>
    <xsl:param name="element"/>
    <xsl:param name="disabled" value="0"/>
    <th>
      <label for="{$prefix}{translate($element/@name, '[]','_')}">
        <xsl:value-of select="$label" />
      </label>
    </th>
    <td class="left">
      <xsl:apply-templates select="$element">
        <xsl:with-param name="disabled" select="$disabled"/>
      </xsl:apply-templates>
      <xsl:call-template name="error">
        <xsl:with-param name="element" select="$element"/>
      </xsl:call-template>
    </td>
  </xsl:template>
  
  <xsl:template match="list">
    <xsl:param name="empty_option" select="1" />
    <xsl:param name="disabled" select="0" />
    
    <select name="{$prefix}{@name}" id="{$prefix}{translate(@name, '[]','_')}">
      <xsl:if test="$disabled">
        <xsl:attribute name="disabled">1</xsl:attribute>
      </xsl:if>
      <xsl:if test="$empty_option">
        <option value=''></option>
      </xsl:if>
      <xsl:apply-templates select="*"/>
    </select>
  </xsl:template>
  
  <xsl:template match="optgroup">
    <xsl:apply-templates select="./*">
      <xsl:with-param name="model" select="1"/>
    </xsl:apply-templates>
  </xsl:template>
  
  <xsl:template match="option">
    <xsl:param name="model" value="0"/>
    
    <option>
      <xsl:attribute name="value">
        <xsl:value-of select="@value"/>
      </xsl:attribute>
      <xsl:if test="@selected">
        <xsl:attribute name="selected">
          <xsl:text>selected</xsl:text>
        </xsl:attribute>
      </xsl:if>
      <xsl:if test="1 = $model">
        <xsl:attribute name="class">
          <xsl:text>mark</xsl:text>
          <xsl:value-of select="../@mark_id"/>
        </xsl:attribute>
      </xsl:if>
      <xsl:value-of select="."/>
    </option>
  </xsl:template>
  
  <xsl:template match="@*">
    <xsl:copy>
      <xsl:apply-templates select="@*"/>
    </xsl:copy>
  </xsl:template>
  
  <xsl:template match="field">
    <input name="{$prefix}{@name}" id="{$prefix}{translate(@name, '[]','_')}" value="{@value}" size="{@size}" />
  </xsl:template>
  
  <xsl:template name="hidden_field">
    <xsl:param name="element"/>
    <xsl:param name="value"/>
    <input name="{$prefix}{$element/@name}" id="{$prefix}{translate($element/@name, '[]','_')}" value="{$value}" type="hidden" />
  </xsl:template>
  
  <xsl:template name="error">
    <xsl:param name="element"/>
    <xsl:if test="$element[@error]">
      <br class="clear"/>
      <div class="error">
        <xsl:value-of select="$element/@error" />
      </div>
    </xsl:if>
  </xsl:template>
</xsl:stylesheet>