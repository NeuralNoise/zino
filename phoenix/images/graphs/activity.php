<?php
    set_include_path( '../../:./' );

    global $water;
    global $libs;
    global $db;
    global $user;
    
    require 'libs/rabbit/rabbit.php';

    Rabbit_Construct( 'empty' );

    if ( !$user->CanModifyCategories() ) {
        return false;
    }
    
    $libs->Load( 'graph' );
    
    if ( false ) { // for now
        header( 'HTTP/1.1 304 Not Modified' );
    }
        
    header( 'Cache-Control: Public' );
    header( "Expires: " . gmdate( "D, d M Y H:i:s", time() + 60 * 60 * 24 * 7 ) . " GMT" );
    header( "Last-Modified: " . gmdate( "D, d M Y H:i:s", time() - 60 * 60 * 24 * 7 ) . " GMT" );
    header( "Pragma: " );
    header( "Content-Type: image/png" );
    
    $months = 1;

    $sql = "SELECT 
                COUNT(*) AS pageviews, 
                DATE( `log_date` ) AS day
            FROM 
                `$logs`
            WHERE
                `log_date` >= NOW() - INTERVAL $months MONTH
                AND ( `log_requesturi` LIKE '%/?%'
                OR `log_requesturi` LIKE '%/index.php%' 
                OR `log_requesturi` LIKE '%/' )
            GROUP BY
                day
            ORDER BY
                day ASC
            ";

    $days = array();
    $res = $db->Query( $sql );
    while ( $row = $res->FetchArray() ) {
        $days[] = $row[ 'pageviews' ];
    }
    
    $smooth = isset( $_GET[ 'smoothing' ] ) ? $_GET[ 'smoothing' ] : 0;
    $size = isset( $_GET[ 'size' ] ) ? $_GET[ 'size' ] : 1;
    
    switch ( $size ) {
        case 2:
            $width = 800;
            $height = 400;
            break;
        case 0:
            $width = 400;
            $height = 200;
            break;
        case 1:
        default:
            $width = 600;
            $height = 300;
            break;
    }
    
    $graph = New Graph( "Pageviews" );
    $graph->SetData( $days );
    $graph->SetSize( $width, $height );
    $graph->SetTime( $months * 30 );
    // $graph->SetSmoothing( $smooth );
    $graph->HighlightLast();
    $graph->Render();
    
    Rabbit_Destruct();
?>
