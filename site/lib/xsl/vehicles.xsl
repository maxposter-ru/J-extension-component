<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="utf-8" />

    <xsl:include href="inc_url.xsl" />
    <xsl:include href="inc_form.xsl" />
    <xsl:include href="inc_vehicles.xsl" />

    <xsl:template match="/">
        <div class="maxposter maxposter-wrapper">
            <xsl:apply-templates />
        </div>
    </xsl:template>

</xsl:stylesheet>
