<operation resource="imagetag" method="delete">
    <imagetag id="<?= $tag[ 'id' ] ?>">
        <image id="<?= $tag[ 'image' ][ 'id' ] ?>" />
        <person id="<?= $tag[ 'personid' ] ?>" />
        <owner id="<?= $tag[ 'ownerid' ] ?>" />
        <geometry>
            <left><?= $tag[ 'left' ] ?></left>
            <top><?= $tag[ 'top' ] ?></top>
            <width><?= $tag[ 'width' ] ?></width>
            <height><?= $tag[ 'height' ] ?></height>
        </geometry>
    </imagetag>
</operation>
