<stream type="journal">
	<? foreach ( $journals as $item ): ?>
    <entry type="journal" id="<?= $item[ 'id' ]; ?>">
        <published><?= $item[ 'created' ] ?></published>
        <author>
            <name><?= $item[ 'username' ]; ?></name>
            <subdomain><?= $item[ 'subdomain' ]; ?></subdomain>
            <gender><?= $item[ 'gender' ]; ?></gender>
            <avatar>
                <media url="http://images2.zino.gr/media/<?= $item[ 'userid' ] ?>/<?= $item[ 'avatarid' ] ?>/<?= $item[ 'avatarid' ] ?>_100.jpg" />
            </avatar>
        </author>
        <title><?= htmlspecialchars( $item[ 'title' ] ) ?></title>
        <discussion count="<?= $item[ 'numcomments' ] ?>" />
    </entry>
    <? endforeach; ?>
</stream>
