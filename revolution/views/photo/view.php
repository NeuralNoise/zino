<? global $settings ?>
<entry id="<?= $photo[ 'id' ] ?>">
    <title><?= htmlspecialchars( $photo[ 'title' ] ) ?></title>
    <? if ( isset( $user ) ): ?>
    <author>
        <name><?= $user[ 'name' ] ?></name>
        <subdomain><?= $user[ 'subdomain' ] ?></subdomain>
        <gender><?= $user[ 'gender' ] ?></gender>
        <avatar>
            <media url="http://images2.zino.gr/media/<?= $user[ 'id' ] ?>/<?= $user[ 'avatarid' ] ?>/<?= $user[ 'avatarid' ] ?>_100.jpg" />
        </avatar>
    </author>
    <? endif; ?>
    <published><?= $photo[ 'created' ] ?></published>
    <media url="http://images2.zino.gr/media/<?= $photo[ 'userid' ] ?>/<?= $settings[ 'beta' ]? '_': '' ?><?= $photo[ 'id' ] ?>/<?= $photo[ 'id' ] ?>_full.jpg" width="<?= $photo[ 'w' ] ?>" height="<?= $photo[ 'h' ] ?>" /><?
    if ( isset( $comments ) ):
        include 'views/comment/listing.php';
    endif;
    if ( !empty( $favourites ) ): ?>
    <favourites count="<?= count( $favourites ) ?>">
        <? foreach ( $favourites as $favourite ): ?>
        <user><name><?= $favourite[ 'username' ] ?></name></user>
        <? endforeach; ?>
    </favourites>
    <? endif; ?>
</entry>
