<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <!-- Шаблон вывода списка автомобилей -->

    <xsl:import href="functions/functions.xsl" />
    <xsl:import href="functions/vehicle.xsl" />

    <xsl:import href="inc_navigation.xsl" />

    <xsl:variable name="count" select="/response/pager/@items_total" />
    <!-- floor(), ceiling(), round() -->
    <xsl:variable name="startpos">
        <xsl:value-of select="(($page - 1)*$rows + 1) - (/response/pager/@items_per_page * (ceiling($page div (/response/pager/@items_per_page div $rows)) - 1))" />
    </xsl:variable>


    <!--  -->
    <xsl:template match="vehicles">
        <div class="maxposter-cars">
            <a name="cars" />
            <xsl:call-template name="vehicles_header" />

            <xsl:choose>
                <xsl:when test="$count > 0">
                    <xsl:call-template name="vehicles_list" />
                    <xsl:call-template name="Navigation" />
                </xsl:when>
                <xsl:otherwise>
                    <p>К сожалению, по вашему запросу ничего не найдено.</p>
                </xsl:otherwise>
            </xsl:choose>

        </div>
    </xsl:template>


    <!-- Заголовок над списком авто -->
    <xsl:template name="vehicles_header">
        <h2>
            <xsl:text>Автомобили в продаже</xsl:text>
            <xsl:text> (</xsl:text>
            <xsl:call-template name="vehicles_count" />
            <xsl:text> шт.)</xsl:text>
        </h2>
    </xsl:template>


    <!-- Список авто -->
    <xsl:template name="vehicles_list">
        <xsl:apply-templates select="/response/vehicles/vehicle[position()>=$startpos and ($startpos - 1 + $rows)>=position()]" />
    </xsl:template>


    <!-- Одно авто в списке -->
    <xsl:template match="vehicle">
        <div>
            <xsl:attribute name="class">
                <xsl:text>maxposter-list-auto</xsl:text>
                <xsl:choose>
                    <xsl:when test="position() mod 2 = 1">
                        <xsl:text> maxposter-odd</xsl:text>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:text> maxposter-even</xsl:text>
                    </xsl:otherwise>
                </xsl:choose>
            </xsl:attribute>

            <div class="maxposter-list-auto-thumbnail">
                <xsl:call-template name="vehicle_thumbnail" />
            </div>

            <xsl:variable name="url">
                <xsl:call-template name="url_vehicle">
                    <xsl:with-param name="vehicle_id" select="@vehicle_id" />
                </xsl:call-template>
            </xsl:variable>
            <div class="maxposter-list-auto-text">
                <h2><a href="{$url}">
                    <xsl:call-template name="vehicle_name" />
                </a></h2>

                <div class="maxposter-list-auto-price">
                    <xsl:text>Цена:&#160;</xsl:text>
                    <xsl:call-template name="vehicle_price" />
                </div>

                <!-- Краткое описание авто -->
                <p>
                    <xsl:call-template name="ucfirst">
                        <xsl:with-param name="str" select="./body/type" />
                    </xsl:call-template>
                    <xsl:text>, </xsl:text>
                    <xsl:value-of select="year" />
                    <xsl:text> года выпуска, двигатель </xsl:text>
                    <xsl:value-of select="./engine/type" />
                    <xsl:text> объёмом </xsl:text>
                    <xsl:call-template name="vehicle_engine_volume" />
                    <xsl:text>, пробег </xsl:text>
                    <xsl:call-template name="vehicle_mileage" />
                    <xsl:text>, </xsl:text>
                    <xsl:value-of select="./gearbox/type" />
                    <xsl:text> КПП</xsl:text>
                    <xsl:text>, цвет кузова </xsl:text>
                    <xsl:value-of select="./body/color" />
                    <xsl:text>.</xsl:text>
                    <br />
                    <xsl:text>Состояние: </xsl:text>
                    <xsl:value-of select="./condition" />
                </p>
            </div>
        </div>
    </xsl:template>


    <!-- Превьюшка -->
    <xsl:template name="vehicle_thumbnail">
        <xsl:variable name="url">
            <xsl:call-template name="url_vehicle">
                <xsl:with-param name="vehicle_id" select="@vehicle_id" />
            </xsl:call-template>
        </xsl:variable>
        <a href="{$url}">
            <img>
                <xsl:attribute name="src">
                    <xsl:choose>
                        <xsl:when test="./photo/@file_name">
                            <xsl:call-template name="url_photo">
                                <xsl:with-param name="all_dealer_ids" select="$dealer_id" />
                                <xsl:with-param name="dealer_id" select="@dealer_id" />
                                <xsl:with-param name="vehicle_id" select="@vehicle_id" />
                                <xsl:with-param name="size_id" select="'120x90'" />
                                <xsl:with-param name="file_name" select="./photo/@file_name" />
                            </xsl:call-template>
                        </xsl:when>
                        <xsl:otherwise>
                            <xsl:call-template name="url_empty_thumbnail" />
                        </xsl:otherwise>
                    </xsl:choose>
                </xsl:attribute>
            </img>
        </a>
    </xsl:template>

</xsl:stylesheet>
