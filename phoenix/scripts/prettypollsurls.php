<?php
    global $water;

    $offset = ( integer )$_GET[ 'offset' ];
    $limit = 100;

    set_include_path( '../:./' );

    require '../libs/rabbit/rabbit.php';

    Rabbit_Construct();

    global $libs;
    global $db;

    $libs->Load( 'url' );

    $query = $db->Prepare(
        'UPDATE
            :polls 
        SET
            `poll_url` = NULL;'
    );
    $query->BindTable( 'polls' );
    $query->Execute();

    $query = $db->Prepare( 'SELECT * FROM :polls' );
    $query->BindTable( 'polls' );
    $res = $query->Execute();

    $polls = array();
    while ( $row = $res->FetchArray() ) {
        $userId = $row[ 'poll_userid' ];
        if ( !isset( $polls[ $userId ] ) ) {
            $polls[ $userId ] = array();
        }
        echo $row[ 'poll_question' ] . "<br />\n";
        $polls[ $userId ][] = array(
            'id' => $row[ 'poll_id' ],
            'question' => array_slice( $row[ 'poll_question' ], 0, 255 )
        );
    }

    $result = array();
    foreach ( $polls as $userId => $hispolls ) {
        $urls = array();
        foreach ( $hispolls as $pollInfo ) {
            $candidate = URL_Format( $pollInfo[ 'question' ] );
            $length = strlen( $candidate );
            while ( isset( $urls[ $candidate ] ) ) {
                if ( $length < 255 ) {
                    $candidate .= '_';
                }
                else {
                    $candidate[ rand( 0, $length - 1 ) ] = '_';
                }
            }
            $urls[ $candidate ] = true;
            $result[ $pollInfo[ 'id' ] ] = $candidate;
        }
    }

    die;
    $i = 0;
    foreach ( $result as $id => $url ) {
        if ( $i >= $offset && $i <= $offset + $limit ) {
            $query = $db->Prepare(
                'UPDATE
                    :polls 
                SET
                    `poll_url` = :poll_url
                WHERE
                    `poll_id` = :poll_id
                LIMIT 1;'
            );
            $query->BindTable( 'polls' );
            $query->Bind( 'poll_url', $url );
            $query->Bind( 'poll_id', $id );
            // $query->Execute();
        }
        ++$i;
    }
    if ( $offset + $limit <= count( $result ) ) {
        $offset += $limit;
        ?><html><head><title>Processing...</title>
        </head><body>
        Processed <?php
        echo $offset;
        ?> out of <?php
        echo count( $result );
        ?>.
        <script type="text/javascript">
        setTimeout( function() {
            window.location.href = "prettypollsurls.php?offset=<?php
            echo $offset;
            ?>";
        }, 1000 );
        </script>
        </body></html><?php
    }

    Rabbit_Destruct();

?>
