<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <!-- генерирует <br> на месте \n -->
    <xsl:template name="break">
        <xsl:param name="text" />
        <xsl:choose>
            <xsl:when test="contains($text,'&#xa;')">
                <xsl:value-of select="substring-before($text, '&#xa;')"  disable-output-escaping="yes" />
                <br/>
                <xsl:call-template name="break">
                    <xsl:with-param name="text" select="substring-after($text,'&#xa;')" />
                </xsl:call-template>
            </xsl:when>
            <xsl:otherwise>
                <xsl:value-of select="$text" disable-output-escaping="yes" />
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>


    <!-- Заменяет первую букву строки заглавной -->
    <xsl:template name="ucfirst">
        <xsl:param name="str" />
        <xsl:param name="strLen" select="string-length($str)" />
        <xsl:variable name="firstLetter" select="substring($str,1,1)" />
        <xsl:variable name="restString" select="substring($str,2,$strLen)" />
        <xsl:variable name="lower" select="'йцукенгшщзхъфывапролджэячсмитьбю'" />
        <xsl:variable name="upper" select="'ЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮ'" />
        <xsl:variable name="translate" select="translate($firstLetter,$lower,$upper)" />

        <xsl:value-of select="concat($translate,$restString)" />
    </xsl:template>


    <!-- Формат даты
         из YYYY-MM-DD в DD.MM.YYYY
         или в D.M.YYYY
         или в D.<month>.YY
     -->
    <xsl:template name="dateFormat">
        <xsl:param name="date" /><!-- дата формата YYYY-MM-DD -->
        <xsl:param name="D" select="'2'" /><!-- кол-во цифр в числе: 1|2 -->
        <xsl:param name="M" select="'2'" /><!-- кол-во цифр в месяце: 1|2|text|textUF -->
        <xsl:param name="Y" select="'4'" /><!-- кол-во цифр в году -->

        <xsl:variable name="year">
            <xsl:choose>
                <xsl:when test="$Y = '2'">
                    <xsl:value-of select="substring($date,3,2)"/>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:value-of select="substring($date,1,4)"/>
                </xsl:otherwise>
            </xsl:choose>
        </xsl:variable>

        <xsl:variable name="month">
            <xsl:choose>
                <xsl:when test="starts-with($M, 'text')">
                    <xsl:choose>
                        <xsl:when test="substring($date,6,2) = '01'">Января</xsl:when>
                        <xsl:when test="substring($date,6,2) = '02'">Февраля</xsl:when>
                        <xsl:when test="substring($date,6,2) = '03'">Марта</xsl:when>
                        <xsl:when test="substring($date,6,2) = '04'">Апреля</xsl:when>
                        <xsl:when test="substring($date,6,2) = '05'">Мая</xsl:when>
                        <xsl:when test="substring($date,6,2) = '06'">Июня</xsl:when>
                        <xsl:when test="substring($date,6,2) = '07'">Июля</xsl:when>
                        <xsl:when test="substring($date,6,2) = '08'">Августа</xsl:when>
                        <xsl:when test="substring($date,6,2) = '09'">Сентября</xsl:when>
                        <xsl:when test="substring($date,6,2) = '10'">Октября</xsl:when>
                        <xsl:when test="substring($date,6,2) = '11'">Ноября</xsl:when>
                        <xsl:when test="substring($date,6,2) = '12'">Декабря</xsl:when>
                    </xsl:choose>
                </xsl:when>
                <xsl:when test="$M = '1'">
                    <xsl:if test="substring($date,6,1) != '0'">
                        <xsl:value-of select="substring($date,6,1)" />
                    </xsl:if>
                    <xsl:value-of select="substring($date,7,1)" />
                </xsl:when>
                <xsl:otherwise>
                    <xsl:value-of select="substring($date,6,2)" />
                </xsl:otherwise>
            </xsl:choose>
        </xsl:variable>

        <xsl:variable name="day">
            <xsl:choose>
                <xsl:when test="$D = '1'">
                    <xsl:if test="substring($date,9,1) != '0'">
                        <xsl:value-of select="substring($date,9,1)" />
                    </xsl:if>
                    <xsl:value-of select="substring($date,10,1)" />
                </xsl:when>
                <xsl:otherwise>
                    <xsl:value-of select="substring($date,9,2)" />
                </xsl:otherwise>
            </xsl:choose>
        </xsl:variable>

        <!-- Output: -->
        <xsl:value-of select="$day" />

        <xsl:choose>
            <xsl:when test="starts-with($M, 'text')">
                <xsl:text> </xsl:text>
            </xsl:when>
            <xsl:otherwise>
                <xsl:text>.</xsl:text>
            </xsl:otherwise>
        </xsl:choose>

        <xsl:variable name="case_upper" select="'ЯФМАИСОНД'" />
        <xsl:variable name="case_lower" select="'яфмаисонд'" />
        <xsl:choose>
            <xsl:when test="$M = 'textUF'">
                <xsl:value-of select="$month" />
            </xsl:when>
            <xsl:otherwise>
                <xsl:value-of select="translate($month, $case_upper, $case_lower)" />
            </xsl:otherwise>
        </xsl:choose>

        <xsl:choose>
            <xsl:when test="starts-with($M, 'text')">
                <xsl:text> </xsl:text>
            </xsl:when>
            <xsl:otherwise>
                <xsl:text>.</xsl:text>
            </xsl:otherwise>
        </xsl:choose>

        <xsl:value-of select="$year" />
    </xsl:template>


    <!-- Аналог PHP explode(). Вместо массива генерирует nodeset с именами элементов $nodename. Рекурсивная. -->
    <xsl:template name="explode-string">
        <xsl:param name="string" />
        <xsl:param name="delimiter" />
        <xsl:param name="nodename" select="'span'" />
        <xsl:choose>
            <xsl:when test="contains($string, $delimiter)">
                <xsl:variable name="l" select="substring-before($string, $delimiter)"/>
                <xsl:variable name="r" select="substring-after($string, $delimiter)"/>
                <xsl:element name="{$nodename}">
                    <xsl:value-of select="normalize-space($l)" />
                </xsl:element>
                <xsl:call-template name="explode-string">
                    <xsl:with-param name="string" select="normalize-space($r)" />
                    <xsl:with-param name="delimiter" select="$delimiter" />
                    <xsl:with-param name="nodename" select="$nodename" />
                </xsl:call-template>
            </xsl:when>
            <xsl:otherwise>
                <xsl:element name="{$nodename}">
                    <xsl:value-of select="normalize-space($string)" />
                </xsl:element>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>

</xsl:stylesheet>
