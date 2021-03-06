<?php

    class ElementStatisticsBounces extends Element {
        public function Render() {
            global $page;
            global $libs;

            $page->SetTitle( 'Statistics' );
            $libs->Load( 'pageview' );

            $finder = New PageviewFinder();
            $bounces = $finder->FindBounceRates( 20 );
            $bounceRates = $bounces[ 0 ];
            $bouncesByElement = $bounces[ 1 ];
            $landingsByElement = $bounces[ 2 ];
            ?><table>
            <thead><tr>
            <th>Element</th>
            <th>Bounce Rate</th>
            <th>Bounces</th>
            <th>Landings</th>
            </tr></thead><tbody><?php
            foreach ( $bounceRates as $element => $bounceRate ) {
                ?><tr><td><?php
                echo $element;
                ?></td><td><?php
                echo $bounceRate * 100; // multiply by 100 to get percentage
                ?>%</td><td><?php
                echo $bouncesByElement[ $element ] * 100; // we log 1% of anonymous
                ?></td><td><?php
                echo $landingsByElement[ $element ] * 100;
                ?></td></tr><?php
            }
            ?></tbody></table><?php
        }
    }

?>
