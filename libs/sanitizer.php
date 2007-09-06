<?php

    class XHTMLSanitizer {
        private $mXHTML;
        private $mSource;
        private $mAllowedTags;

        public function SetSource( $source ) {
            if ( !is_scalar( $source ) ) {
                $this->mSource = "";
            }
            else {
                $this->mSource = $source;
            }
        }
        public function AllowTag( $sanetag ) {
            $this->mAllowedTags[] = $sanetag;
        }
        public function Sanitize() {
            $tags           = "";
            $attributes     = "";
            $allowedTags    = $this->mAllowedTags;
            while ( $tag = array_shift( $allowedTags ) ) {
                $tags .= $tag->Name() . "\n";
                $allowedAttributes = $tag->AllowedAttributes();
                while ( $attribute = array_shift( $allowedAttributes ) ) {
                    $attributes .= $tag->Name() . " " . $attribute->Name() . "\n";
                }
            }

            $data = $tags . "\n" . $attributes . "\n" . $this->mSource . "\n";
            echo nl2br( "** $data **" );

            $descriptorspec = array(
                0 => array( "pipe", "r" ),
                1 => array( "pipe", "w" ),
                2 => array( "file", "/tmp/error-output.txt", "a" )
            );

            $cmd = 'php';
            chdir( '/srv/www/vhosts/chit-chat.gr/subdomains/beta/httpsdocs/bin/sanitizer' );
            $proccess = proc_open( $cmd, $descriptorspec, $pipes );
            if ( !is_resource( $proccess ) ) {
                die( "Error opening sanitizer process" );
            }
            
            fwrite( $pipes[ 0 ], $data );
            fclose( $pipes[ 0 ] );

            $this->mXHTML = stream_get_contents( $pipes[ 1 ] );
            fclose( $pipes[ 1 ] );

            proc_close( $proccess );
        }
        public function GetXHTML() {
            return $this->mXHTML;
        }
        public function XHTMLSanitizer() {
            $this->mAllowedTags = array();
        }
    }

    class XHTMLSaneTag {
        private $mName;
        private $mAllowedAttributes;

        public function Name() {
            return mName;
        }
        public function AllowedAttributes() {
            return $this->mAllowedAttributes;
        }
        public function AllowAttribute( $attribute ) {
            $this->mAllowedAttributes[] = $attribute;
        }
        public function XHTMLSaneTag( $name ) {
            $this->mName = $name;
            $this->mAllowedAttributes = array();
        }
    }

    class XHTMLSaneAttribute {
        public function Name() {
            return mName;
        }
        public function XHTMLSaneAttribute( $name ) {
            $this->mName = $name;
        }
    }

?>
