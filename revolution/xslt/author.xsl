<xsl:template match="author">
    <a class="username">
        <xsl:attribute name="href">user/<xsl:value-of select="subdomain[1]" /></xsl:attribute>
        <xsl:if test="avatar[1]">
            <img class="avatar">
                <xsl:attribute name="src">
                    <xsl:value-of select="avatar[1]/media[1]/@url" />
                </xsl:attribute>
            </img>
        </xsl:if>
        <xsl:value-of select="name[1]" />
    </a>
</xsl:template>
