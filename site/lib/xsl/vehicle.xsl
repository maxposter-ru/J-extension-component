<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method = "html" encoding="utf-8" />

    <xsl:include href="functions/vehicle.xsl" />
    <xsl:include href="inc_url.xsl" />

    <!--<xsl:variable name="dealer_id" select="/response/vehicle/@dealer_id" />-->
    <xsl:variable name="vehicle_id" select="/response/vehicle/@vehicle_id" />


    <!--  -->
    <xsl:template match="/">
        <xsl:apply-templates select="response/vehicle" />
    </xsl:template>


    <!-- Отображение описания авто -->
    <xsl:template match="vehicle">
        <div class="maxposter">
            <a name="car" />

            <h1><xsl:call-template name="vehicle_name" /></h1>

            <div class="maxposter-price">
                <xsl:text>Цена:&#160;</xsl:text>
                <xsl:call-template name="vehicle_price" />
            </div>

            <div class="maxposter-auto-key-features">
                <h2>Основные характеристики</h2>
                <div class="maxposter-auto-features-list">
                    <xsl:call-template name="vehicle_key_features" />
                </div>
            </div>

            <xsl:apply-templates select="./photos" />

            <div class="maxposter-auto-options">
                <h2>Комплектация</h2>
                <ul>
                    <xsl:call-template name="vehicle_options" />
                </ul>
            </div>

            <div class="maxposter-auto-description">
                <h2>Дополнительная информация</h2>
                <p>
                    <xsl:call-template name="break">
                        <xsl:with-param name="text" select="./description" />
                    </xsl:call-template>
                </p>
            </div>

            <div class="maxposter-auto-contacts">
                <h2>Контакты</h2>
                <dl>
                    <xsl:if test="./inspection_place">
                        <dt>Место осмотра:</dt>
                        <dd>
                            <xsl:value-of select="./inspection_place" disable-output-escaping="yes" />
                        </dd>
                    </xsl:if>
                    <dt>Телефоны:</dt>
                    <dd>
                        <xsl:call-template name="contacts_full" />
                    </dd>
                </dl>
            </div>
        </div>
    </xsl:template>


    <!-- Основные характеристики авто -->
    <xsl:template name="vehicle_key_features">
        <div class="maxposter-auto-feature">
            <span>Год выпуска:</span>
            <xsl:value-of select="./year" />
        </div>
        <div class="maxposter-auto-feature">
            <span>Привод:</span>
            <xsl:value-of select="./drive/type" />
        </div>
        <div class="maxposter-auto-feature">
            <span>Кузов:</span>
            <xsl:value-of select="./body/type" />
        </div>
        <div class="maxposter-auto-feature">
            <span>Пробег:</span>
            <xsl:call-template name="vehicle_mileage" />
        </div>
        <div class="maxposter-auto-feature">
            <span>Двигатель:</span>
            <xsl:value-of select="./engine/type" />
        </div>
        <div class="maxposter-auto-feature">
            <span>Руль:</span>
            <xsl:value-of select="./steering_wheel/place" />
        </div>
        <div class="maxposter-auto-feature">
            <span>Объем:</span>
            <xsl:call-template name="vehicle_engine_volume" />
        </div>
        <div class="maxposter-auto-feature">
            <span>Цвет:</span>
            <xsl:value-of select="./body/color" />
        </div>
        <div class="maxposter-auto-feature">
            <span>Коробка передач:</span>
            <xsl:value-of select="./gearbox/type" />
        </div>
        <div class="maxposter-auto-feature">
            <span>Состояние:</span>
            <xsl:value-of select="./condition" />
        </div>
    </xsl:template>


    <!-- Опции авто -->
    <xsl:template name="vehicle_options">
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Антиблокировочная система (АБС)'" />
            <xsl:with-param name="element" select="./abs" />
        </xsl:call-template>
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Антипробуксовочная система'" />
            <xsl:with-param name="element" select="./asr" />
        </xsl:call-template>
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Система курсовой стабилизации'" />
            <xsl:with-param name="element" select="./esp" />
        </xsl:call-template>
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Парктроник'" />
            <xsl:with-param name="element" select="./parktronic" />
        </xsl:call-template>
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Подушки безопасности:'" />
            <xsl:with-param name="element" select="./airbag" />
        </xsl:call-template>
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Охранная система'" />
            <xsl:with-param name="element" select="./alarm_system" />
        </xsl:call-template>
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Центральный замок'" />
            <xsl:with-param name="element" select="./central_lock" />
        </xsl:call-template>
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Навигационная система'" />
            <xsl:with-param name="element" select="./nav_system" />
        </xsl:call-template>
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Легкосплавные диски'" />
            <xsl:with-param name="element" select="./light_alloy_wheels" />
        </xsl:call-template>
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Датчик дождя'" />
            <xsl:with-param name="element" select="./sensors/rain" />
        </xsl:call-template>
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Датчик света'" />
            <xsl:with-param name="element" select="./sensors/light" />
        </xsl:call-template>
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Омыватель фар'" />
            <xsl:with-param name="element" select="./headlights/washer" />
        </xsl:call-template>
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Ксеноновые фары'" />
            <xsl:with-param name="element" select="./headlights/xenon" />
        </xsl:call-template>
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Салон:'" />
            <xsl:with-param name="element" select="./compartment" />
        </xsl:call-template>
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Тонированные стекла'" />
            <xsl:with-param name="element" select="./windows/tinted" />
        </xsl:call-template>
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Люк'" />
            <xsl:with-param name="element" select="./hatch" />
        </xsl:call-template>
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Газобалонное оборудование'" />
            <xsl:with-param name="element" select="./engine/gas_equipment" />
        </xsl:call-template>
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Круиз-контроль'" />
            <xsl:with-param name="element" select="./cruise_control" />
        </xsl:call-template>
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Бортовой компьютер'" />
            <xsl:with-param name="element" select="./trip_computer" />
        </xsl:call-template>
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Усилитель рулевого управления:'" />
            <xsl:with-param name="element" select="./steering_wheel/power" />
        </xsl:call-template>
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Регулировка руля:'" />
            <xsl:with-param name="element" select="./steering_wheel/adjustment" />
        </xsl:call-template>
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Обогрев руля'" />
            <xsl:with-param name="element" select="./steering_wheel/heater" />
        </xsl:call-template>
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Электрозеркала'" />
            <xsl:with-param name="element" select="./mirrors/power" />
        </xsl:call-template>
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Обогрев зеркал'" />
            <xsl:with-param name="element" select="./mirrors/defroster" />
        </xsl:call-template>
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Электростеклоподъемники:'" />
            <xsl:with-param name="element" select="./windows/power" />
        </xsl:call-template>
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Обогрев сидений'" />
            <xsl:with-param name="element" select="./seats/heater" />
        </xsl:call-template>
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Регулировка сиденья водителя:'" />
            <xsl:with-param name="element" select="./seats/driver_adjustment" />
        </xsl:call-template>
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Электропривод сиденья пассажира'" />
            <xsl:with-param name="element" select="./seats/passanger_adjustment" />
        </xsl:call-template>
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Управление климатом:'" />
            <xsl:with-param name="element" select="./climate_control" />
        </xsl:call-template>
        <xsl:call-template name="vehicle-option">
            <xsl:with-param name="label" select="'Стереосистема:'" />
            <xsl:with-param name="element" select="./audio" />
        </xsl:call-template>
    </xsl:template>


    <!-- Список опций авто -->
    <xsl:template name="vehicle-option">
        <xsl:param name="label" />
        <xsl:param name="element" />
        <xsl:if test="$element">
            <li>
                <xsl:value-of select="$label" />
                <xsl:if test="normalize-space($element)">
                    <xsl:text> </xsl:text>
                    <xsl:value-of select="normalize-space($element)" />
                </xsl:if>
            </li>
        </xsl:if>
    </xsl:template>


    <xsl:template match="photos">
        <div id="photos" class="full">
          <div id="original">
            <img>
              <xsl:attribute name="src">
                <xsl:call-template name="url_photo">
                  <xsl:with-param name="all_dealer_ids" select="$dealer_id" />
                  <xsl:with-param name="dealer_id" select="/response/vehicle/@dealer_id" />
                  <xsl:with-param name="vehicle_id" select="$vehicle_id" />
                  <xsl:with-param name="size_id" select="'640x480'" />
                  <xsl:with-param name="file_name" select="./photo[1 = position()]/@file_name" />
                </xsl:call-template>
              </xsl:attribute>
            </img>
          </div>
          <div id="thumbnails">
            <xsl:apply-templates select="./photo"/>
          </div>
        </div>
    </xsl:template>

    <xsl:template match="photo">
        <a>
          <xsl:attribute name="href">
            <xsl:call-template name="url_photo">
              <xsl:with-param name="all_dealer_ids" select="$dealer_id" />
              <xsl:with-param name="dealer_id" select="/response/vehicle/@dealer_id" />
              <xsl:with-param name="vehicle_id" select="$vehicle_id" />
              <xsl:with-param name="size_id" select="'640x480'" />
              <xsl:with-param name="file_name" select="@file_name" />
            </xsl:call-template>
          </xsl:attribute>
          <img>
            <xsl:attribute name="src">
              <xsl:call-template name="url_photo">
                <xsl:with-param name="all_dealer_ids" select="$dealer_id" />
                <xsl:with-param name="dealer_id" select="/response/vehicle/@dealer_id" />
                <xsl:with-param name="vehicle_id" select="$vehicle_id" />
                <xsl:with-param name="size_id" select="'120x90'" />
                <xsl:with-param name="file_name" select="@file_name" />
              </xsl:call-template>
            </xsl:attribute>
            <xsl:if test="(1 = position()) or (0 = (position() mod 5))">
              <xsl:attribute name="class">
                <xsl:if test="1 = position()">
                  <xsl:text>current </xsl:text>
                </xsl:if>
                <xsl:if test="0 = (position() mod 5)">
                  <xsl:text>last</xsl:text>
                </xsl:if>
              </xsl:attribute>
            </xsl:if>
          </img>
        </a>
    </xsl:template>

</xsl:stylesheet>
