<discussion>
<?
    $commentids = array( 0 );
    $indentation = 0;
    foreach ( $comments as $comment ) {
        while ( $comment[ 'parentid' ] != $commentids[ 0 ] ) {
            assert( !empty( $commentids ) );
            array_shift( $commentids );
            ?></comment><?php
        }
        array_unshift( $commentids, $comment[ 'id' ] );
        ?><comment><published><?php
        echo $comment[ 'created' ];
        ?></published><author><name><?php
        echo $comment[ 'username' ];
        ?></name><gender><?php
        echo $comment[ 'gender' ];
        ?></gender><avatar><media url="http://images2.zino.gr/media/<?php
        echo $comment[ 'userid' ];
        ?>/<?php
        echo $comment[ 'avatarid' ];
        ?>/<?php
        echo $comment[ 'avatarid' ];
        ?>_100.jpg" /></avatar></author><text><?= $comment[ 'text' ]; ?></text><?php
    }
    while ( count( $commentids ) > 1 ) {
        array_shift( $commentids );
        ?></comment><?php
    }
?>
</discussion>