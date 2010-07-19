<user id="<?= $user[ 'id' ] ?>">
    <name><?= $user[ 'name' ] ?></name>
    <subdomain><?= $user[ 'subdomain' ] ?></subdomain>
    <gender><?= $user[ 'gender' ] ?></gender>
    <? if ( isset( $user[ 'profile' ][ 'age' ] ) ): ?>
    <age><?= $user[ 'profile' ][ 'age' ] ?></age>
    <? endif; ?>
    <avatar id="<?= $user[ 'avatarid' ] ?>">
        <media url="http://images2.zino.gr/media/<?= $user[ 'id' ] ?>/<?= $user[ 'avatarid' ] ?>/<?= $user[ 'avatarid' ] ?>_100.jpg" />
    </avatar>
    <? if ( isset( $user[ 'location' ] ) ): ?>
    <location><?= $user[ 'location' ] ?></location>
    <? endif;
    if ( isset( $counts ) ): ?>
        <friends count="<?= $counts[ 'friends' ]; ?>" />
        <stream type="photo" count="<?= $counts[ 'images' ]; ?>" />
        <stream type="poll" count="<?= $counts[ 'polls' ]; ?>" />
        <stream type="journal" count="<?= $counts[ 'journals' ]; ?>" />
        <answers count="<?= $counts[ 'answers' ]; ?>" />
        <favourites count="<?= $counts[ 'favourites' ]; ?>" />
        <chat count="<?= $counts[ 'shouts' ]; ?>" />
    <? endif; ?>
    <? if ( $friendofuser ): ?>
        <knownBy><?= $_SESSION[ 'user' ][ 'name' ]; ?></knownBy>
    <? endif; ?>
    <details>
        <? $stats = array(
            'height', 'weight', 'smoker', 'drinker',
            'relationship', 'sexualorientation',
            'politics', 'religion',
            'slogan', 'aboutme',
            'eyecolor', 'haircolor'
           );
           foreach ( $stats as $stat ):
           if ( isset( $user[ 'profile' ][ $stat ] ) ): ?>
           <<?= $stat ?>><?= htmlspecialchars( $user[ 'profile' ][ $stat ] ) ?></<?= $stat ?>>
        <? endif;
           endforeach; ?>
    </details>
    <contact>
        <? $ims = array( 'skype', 'msn', 'gtalk', 'yim' );
           foreach ( $ims as $im ):
           if ( isset( $user[ 'profile' ][ $im ] ) ): ?>
        <im type="<?= $im ?>"><?= $user[ 'profile' ][ $im ] ?></im>
        <? endif;
           endforeach; ?>
    </contact>
    <mood>
        <label>
        <? if ( $user[ 'gender' ] == 'f' ):
               echo $user[ 'mood' ][ 'labelfemale' ];
           else:
               echo $user[ 'mood' ][ 'labelmale' ];
           endif;
        ?>
        </label>
        <media url="http://static.zino.gr/phoenix/moods/<?= $user[ 'mood' ][ 'url' ] ?>" />
    </mood>
    <?
    if ( isset( $comments ) ):
        include 'views/comment/listing.php';
    endif;
    ?>
</user>
