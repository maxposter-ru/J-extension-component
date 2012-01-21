<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <!--
        Генерация URL собрана в одном месте для упрощения изменений.
    -->

    <!-- Путь к странице списка авто -->
    <xsl:template name="url_vehicles">
        <xsl:param name="page" select="0" />
        <xsl:param name="filter" select="''" />

        <xsl:value-of select="$url_vehicles" disable-output-escaping="yes" />
        <xsl:if test="not(contains($url_vehicles, '?')) and (($page > 1) or ($filter != ''))">
            <xsl:text>?</xsl:text>
        </xsl:if>
        <xsl:if test="$page > 1">
            <xsl:text disable-output-escaping="yes">&amp;page=</xsl:text>
            <xsl:value-of select="$page" disable-output-escaping="yes" />
        </xsl:if>
        <xsl:if test="$filter != ''">
            <xsl:value-of select="$filter" disable-output-escaping="yes" />
        </xsl:if>
    </xsl:template>


    <!-- Путь к странице просмотра авто -->
    <xsl:template name="url_vehicle">
        <xsl:param name="vehicle_id" />

        <xsl:value-of select="substring-before($url_vehicle, '%vehicle_id%')" disable-output-escaping="yes" />
        <xsl:value-of select="$vehicle_id" disable-output-escaping="yes" />
        <xsl:value-of select="substring-after($url_vehicle, '%vehicle_id%')" disable-output-escaping="yes" />
    </xsl:template>


    <!-- Пустое фото -->
    <xsl:template name="url_empty_thumbnail">
        <xsl:value-of select="$url_empty_thumbnail"/>
    </xsl:template>


    <!-- Путь к фотографии -->
    <xsl:template name="url_photo">
        <xsl:param name="all_dealer_ids" select="''" />
        <xsl:param name="dealer_id" select="''" />
        <xsl:param name="vehicle_id" />
        <xsl:param name="size_id" />
        <xsl:param name="file_name" />

        <xsl:value-of select="$url_photo" />
        <xsl:if test="contains($all_dealer_ids, '_')">
            <xsl:value-of select="$dealer_id" />
            <xsl:text>/</xsl:text>
        </xsl:if>
        <xsl:value-of select="$vehicle_id" />
        <xsl:text>/</xsl:text>
        <xsl:value-of select="$size_id" />
        <xsl:text>/</xsl:text>
        <xsl:value-of select="$file_name" />
    </xsl:template>

</xsl:stylesheet>
