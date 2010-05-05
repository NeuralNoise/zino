<xsl:template match="/social[@resource='user' and @method='view']">
    <xsl:for-each select="user">
        <a class="xbutton" href="photos">&#171;</a>
        <h2><xsl:value-of select="name[1]" /></h2>
        <xsl:if test="slogan[1]">
            <h3><xsl:value-of select="slogan[1]" /></h3>
        </xsl:if>
        <a class="avatar">
            <xsl:attribute name="href">photo/<xsl:value-of select="avatar[1]/@id" /></xsl:attribute>
            <img class="avatar">
                <xsl:attribute name="src">
                    <xsl:value-of select="avatar[1]/media[1]/@url" />
                </xsl:attribute>
            </img>
        </a>
        <xsl:if test="/social/@for and /social/@for!=name[1]">
            <form action="friendship/create" method="post">
                <input type="hidden" name="friendid">
                    <xsl:attribute name="value"><xsl:value-of select="@id" /></xsl:attribute>
                </input>
                <input type="submit" value="Προσθήκη φίλου" />
            </form>
            <form action="friendship/delete" method="post">
                <input type="hidden" name="friendid">
                    <xsl:attribute name="value"><xsl:value-of select="@id" /></xsl:attribute>
                </input>
                <input type="submit" value="Διαγραφή φίλου" />
            </form>
        </xsl:if>
        <xsl:apply-templates select="discussion" />
    </xsl:for-each>
</xsl:template>