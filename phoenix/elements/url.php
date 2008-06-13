<?php
    /// Content-type: text/plain ///
    function ElementURL( $target ) {
        global $rabbit_settings;

        w_assert( is_object( $target ) );

        switch ( get_class( $target ) ) {
            case 'User':
                return Element( 'user/url', $target );
            case 'Image':
                echo $rabbit_settings[ 'webaddress' ];
                ?>/?p=photo&id=<?php // do not escape this & -- we're in plaintext mode; use output buffering in your caller if you want to htmlspecialchars()
                echo $target->Id;
                return;
            case 'Album':
                echo $rabbit_settings[ 'webaddress' ];
                ?>/?p=album&id=<?php
                echo $target->Id;
                return;
            case 'Journal':
                echo $rabbit_settings[ 'webaddress' ];
                ?>/?p=journal&id=<?php
                echo $target->Id;
                return;
            case 'Poll':
                echo $rabbit_settings[ 'webaddress' ];
                ?>/?p=poll&id=<?php
                echo $target->Id;
                return;
            case 'Comment':
                ob_start();
                Element( 'url', $target->Item );
                $url = ob_get_clean();
                echo $url;
                if ( strpos( $url, '&' ) !== false ) {
                    ?>&<?php
                }
                else {
                    ?>?<?php
                }
                ?>commentid=<?php
                echo $target->Id;
                ?>#comment_<?php
                echo $target->Id;
                return;
            default:
                throw New Exception( 'Unknown comment target item "' . get_class( $target ) . '"' );
        }
    }
?>
