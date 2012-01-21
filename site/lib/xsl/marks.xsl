<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output method="html" encoding="utf-8" indent="yes" />

  <xsl:include href="inc_url.xsl"/>

  <xsl:template match="/">
    <div class="maxposter">
      <ul class="marks">
        <xsl:apply-templates select="/response/marks/mark"/>
      </ul>
    </div>
  </xsl:template>

  <xsl:template match="mark">
    <li class="mark">
      <a>
        <xsl:attribute name="href">
          <xsl:call-template name="url_vehicles">
            <xsl:with-param name="filter">
              <xsl:text disable-output-escaping="yes">&amp;</xsl:text>
              <xsl:value-of select="$prefix"/>
              <xsl:text>search[mark_id]=</xsl:text>
              <xsl:value-of select="@mark_id"/>
            </xsl:with-param>
          </xsl:call-template>
        </xsl:attribute>
        <xsl:value-of select="name" />
        <xsl:text> (</xsl:text>
        <xsl:value-of select="count" />
        <xsl:text> шт.)</xsl:text>
      </a>
      <ul class="models">
        <xsl:apply-templates select="./models/model"/>
      </ul>
    </li>
  </xsl:template>

  <xsl:template match="model">
    <li class="model">
      <a>
        <xsl:attribute name="href">
          <xsl:call-template name="url_vehicles">
            <xsl:with-param name="filter">
              <xsl:text disable-output-escaping="yes">&amp;</xsl:text>
              <xsl:value-of select="$prefix"/>
              <xsl:text>search[model_id]=</xsl:text>
              <xsl:value-of select="@model_id"/>
            </xsl:with-param>
          </xsl:call-template>
        </xsl:attribute>
        <xsl:value-of select="name" />
        <xsl:text> (</xsl:text>
        <xsl:value-of select="count" />
        <xsl:text> шт.)</xsl:text>
      </a>
    </li>
  </xsl:template>
</xsl:stylesheet>
