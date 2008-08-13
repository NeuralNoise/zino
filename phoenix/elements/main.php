<?php
    class ElementMain extends Element {
        public function Render() {
            global $user;
            global $water;
            global $page;
            global $libs;
            global $rabbit_settings;
            global $xc_settings;
            
            //attaching ALL css files
            if ( $rabbit_settings[ 'production' ] ) {
                $page->AttachStylesheet( $xc_settings[ 'staticcssurl' ] . 'global.css?' . $xc_settings[ 'cssversion' ] );
            }
            else {
                $page->AttachStylesheet( $xc_settings[ 'staticcssurl' ] . 'global-beta.css?' . $xc_settings[ 'cssversion' ] );
            }
            if ( UserBrowser() == "MSIE" ) {
                $page->AttachStylesheet( 'css/ie.css' );
            }
            //start javascript attaching
            $page->AttachScript( 'http://www.google-analytics.com/urchin.js' );
            if ( $rabbit_settings[ 'production' ] ) {
                $page->AttachScript( $xc_settings[ 'staticjsurl' ] . 'global.js?' . $xc_settings[ 'jsversion' ] );
            }
            else {
                $page->AttachScript( $xc_settings[ 'staticjsurl' ] . 'global-beta.js?' . $xc_settings[ 'jsversion' ] );
            }
            
            $page->AddMeta( 'author', 'Kamibu Development Team' );
            $page->AddMeta( 'keywords', 'greek friends chat community greece meet people' );
            $page->AddMeta( 'description', 'Το ' . $rabbit_settings[ 'applicationname' ] . ' είναι μία ελληνική κοινότητα φίλων - είσαι μέσα;' );
            
            ob_start();
            $res = Element::MasterElement();
            $master = ob_get_clean();
            
            if ( $res === false ) { //If the page requested is not in the pages available
                Element( 'banner' );
                ?><div class="content" id="content"><?php
                Element( '404' );
                ?></div><?php
                Element( 'footer' );
            }
            else {
                if ( !is_array( $res ) || !isset( $res[ 'tiny' ] ) ) {
                    Element( 'banner' );
                }
                ?><div class="content" id="content"><?php    
                echo $master;
                ?></div><?php
                if ( !is_array( $res ) || !isset( $res[ 'tiny' ] ) ) {
                    Element( 'footer' );
                }
            }
            if ( $page->Title() != '' ) { // If the title's page is not blank
                $page->SetTitle( $page->Title() . ' | ' . $rabbit_settings[ 'applicationname' ] );
            }
            else {
                $water->Notice( 'Title not defined for page' ); // Produce a notice at the php debugger
                $page->SetTitle( $rabbit_settings[ 'applicationname' ] );
            }
            
            // pass
            return $res;
        }
    }
?>
