<?php

    class ElementDeveloperResearchGetjournals extends Element {
        public function Render( tText $username ) {
            global $user;
            global $libs;

            $libs->Load( 'research/spot' );

            $username = $username->Get();
            if ( !empty( $username ) ) {
                $userfinder = New UserFinder();
                $theuser = $userfinder->FindByName( $username );
            }
            else {
                $theuser = $user;
            }

            ?>Recommended journals<br />
            <small>Powered by Spot</small><br /><?php

            $journals = Spot::GetJournals( $theuser );
            ?><div style="margin-left: 10px;"><?php
                foreach ( $journals as $journal ) {
                    echo $journal->User->Name;
                    ?><br /><?php
                    ?><a href=""><?php
                    echo htmlspecialchars( $journal->Title );
                    ?></a>
                    <br /><br /><?php
                }
                ?></div><?php
        }
    }

?>
