<?php
    class ElementSearchView extends Element {
        public function Render( $minage, $maxage, $placeid, $gender, $orientation, $name, $limit, $pageno ) {
            global $xc_settings;
            global $rabbit_settings;
            global $libs;

            $libs->Load( 'user/search' );
            $libs->Load( 'place' );
            
            if ( $pageno <= 0 ) {
                $pageno = 1;
            }
            $offset = ( $pageno - 1 ) * $limit;
            ?><div id="search"><?php
            $location = New Place( $placeid );
            Element( 'search/options',
                $minage, $maxage, $location, $gender, $orientation, $name,
                $offset, $limit
            );
            ?></div><?php
            $searching = $minage > 0 || $maxage > 0 || $gender == 'm' || $gender == 'f' || $orientation !== '' || $location->Exists();
            if ( $searching ) {
                // Get $users by a finder using $users_per_page, $pageno in the LIMIT statement.
                $finder = New UserSearch();
                $users = $finder->FindByDetails( $minage, $maxage, $location, $gender, $orientation, '', $offset, $limit );
                if ( !count( $users ) ) {
                    ?><div style="text-align:center;margin-bottom:20px">
                        <strong>Δεν βρέθηκαν άτομα με τα κριτήρια αναζήτησής σου.</strong><br />
                        Δοκίμασε να αφαιρέσεις κάποιο κριτήριο ή να ψάξεις με πιο γενικούς όρους.
                    </div><?php
                }
                else {
                    Element( 'user/list', $users );
                }
                // Change the link
                $args = compact( 'minage', 'maxage', 'placeid', 'gender', 'orientation' );
                $searchargs = array();
                foreach ( $args as $key => $arg ) {
                    $searchargs[] = $key . '=' . urlencode( $arg );
                }
                $link = $rabbit_settings[ 'webaddress' ] . "/find?" . implode( '&', $searchargs ) . "&pageno=";
                $pages = $finder->FoundRows() / $limit;
                Element( 'pagify', $pageno, $link, $pages );
            }
        }
    }
?>
