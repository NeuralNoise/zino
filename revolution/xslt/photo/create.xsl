<xsl:template match="/social[@resource='photo' and @method='create']">
    <xsl:choose>
        <xsl:when test="error and error/@type = 'wrongextension'">
            ����� � ����� ������� ��� �������������
        <xsl:when>
        
        <xsl:when test="error and error/@type = 'largefile'">
            H ���������� ��� ��� ������ �� ��������� �� 4MB
        <xsl:when>
        
        <xsl:when test="error and error/@type = 'fileupload'">
            ������������� �������� ���� �� �������� ��� �������
        <xsl:when>
        
        <xsl:otherwise>
            <script type="text/javascript">
                window.location.href = 'photos/<xsl:value-of select="//photo/@id" />';
            </script>
        </xsl:otherwise>
    <xsl:choose>
</xsl:template>

