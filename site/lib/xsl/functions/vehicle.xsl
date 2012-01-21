<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:import href="functions.xsl" />


    <!-- Название авто: марка и модель -->
    <xsl:template name="vehicle_name">
        <xsl:value-of select="./mark" />
        <xsl:text> </xsl:text>
        <xsl:value-of select="./model" />
    </xsl:template>


    <!-- Стоимость авто -->
    <xsl:decimal-format name="price" grouping-separator=" "/>

    <xsl:template name="vehicle_price">
        <xsl:choose>
            <xsl:when test="./price/value/@unit = 'eur'">
                <xsl:text>€ </xsl:text>
                <xsl:value-of select="format-number(./price/value, '### ### ###', 'price')" />
            </xsl:when>
            <xsl:when test="./price/value/@unit = 'usd'">
                <xsl:text>$ </xsl:text>
                <xsl:value-of select="format-number(./price/value, '### ### ###', 'price')" />
            </xsl:when>
            <xsl:when test="./price/value/@unit = 'rub'">
                <xsl:value-of select="format-number(./price/value, '### ### ###', 'price')" />
                <xsl:text> руб.</xsl:text>
            </xsl:when>
        </xsl:choose>
    </xsl:template>


    <!-- Пробег -->
    <xsl:template name="vehicle_mileage">
        <xsl:value-of select="./mileage/value" />
        <xsl:choose>
            <xsl:when test="./mileage/value/@unit = 'km'">
                <xsl:text>&#160;км.</xsl:text>
            </xsl:when>
            <xsl:when test="./mileage/value/@unit = 'mile'">
                <xsl:text>&#160;миль</xsl:text>
            </xsl:when>
        </xsl:choose>
    </xsl:template>


    <!-- Объем двигателя -->
    <xsl:template name="vehicle_engine_volume">
        <xsl:value-of select="./engine/volume" />
        <xsl:text>&#160;см&#179;</xsl:text>
    </xsl:template>


    <!-- Количество авто всего -->
    <xsl:template name="vehicles_count">
        <xsl:choose>
            <xsl:when test="$count">
                <xsl:value-of select="$count" />
            </xsl:when>
            <xsl:when test="/response[@id = 'vehicles']">
                <xsl:value-of select="count(/response/vehicles/vehicle)" />
            </xsl:when>
            <xsl:otherwise>
                <xsl:text>0</xsl:text>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>


    <!-- Список телефонов через запятую -->
    <xsl:template name="contact_phones">
        <xsl:for-each select="./contact/phone">
            <xsl:if test="position() > 1">
                <xsl:text>, </xsl:text>
            </xsl:if>
            <xsl:value-of select="." />
        </xsl:for-each>
    </xsl:template>


    <!-- Список контактов с телефонами и временем звонка -->
    <xsl:template name="contacts_full">
        <ul>
            <xsl:call-template name="full-contact">
                <xsl:with-param name="contact" select="./contact" />
                <xsl:with-param name="current" select="'1'" />
                <xsl:with-param name="element" select="'li'" />
            </xsl:call-template>
        </ul>
    </xsl:template>

    <xsl:template name="full-contact">
        <xsl:param name="contact" />
        <xsl:param name="current" />
        <xsl:param name="names" select="$contact/name" />
        <xsl:param name="element" select="'span'" />
        <xsl:variable name="name" select="normalize-space(substring-before($names, ','))" />
        <xsl:variable name="rest-names" select="normalize-space(substring-after($names, ','))" />

        <xsl:if test="$contact/phone[position() = $current]">
            <xsl:element name="{$element}">
                <xsl:variable name="phone" select="$contact/phone[position() = $current]" />
                <xsl:value-of select="$phone" />
                <xsl:text> с </xsl:text>
                <xsl:value-of select="$phone/@from" />
                <xsl:text> до </xsl:text>
                <xsl:value-of select="$phone/@to" />
                <xsl:choose>
                    <xsl:when test="$name">
                        <xsl:text>, </xsl:text>
                        <xsl:value-of select="$name" />
                    </xsl:when>
                    <xsl:when test="$names">
                        <xsl:text>, </xsl:text>
                        <xsl:value-of select="normalize-space($names)" />
                    </xsl:when>
                </xsl:choose>
                <xsl:text>;</xsl:text>
            </xsl:element>
            <xsl:if test="count($contact/phone) > $current">
                <xsl:call-template name="full-contact">
                    <xsl:with-param name="contact" select="$contact" />
                    <xsl:with-param name="current" select="$current + 1" />
                    <xsl:with-param name="names" select="$rest-names" />
                    <xsl:with-param name="element" select="$element" />
                </xsl:call-template>
            </xsl:if>
        </xsl:if>
    </xsl:template>

</xsl:stylesheet>
