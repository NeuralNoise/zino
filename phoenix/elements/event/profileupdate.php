<?php
    function ElementEventProfileUpdate( $eventlist ) {
        global $water;

        if ( $eventlist[ 0 ]->User->Gender =='f' ) {
            ?>Η <?php
            $self = 'της';
        }
        else {
            ?>O <?php
            $self = 'του';
        }
        echo $eventlist[ 0 ]->User->Name;
        $profileinfo = array();
        foreach ( $eventlist as $one ) {
            ob_start();
            switch ( $one->Typeid ) {
                case EVENT_USERPROFILE_EDUCATION_UPDATED:
                    ?>πάει <?php
                    ob_start();
                    Element( 'user/trivial/education' , $one->User->Profile->Education );
                    $education = ob_get_clean();
                    if ( $education != utf8_strtoupper( $education ) ) {
                        // if it's not all-upper case (abbreviation), lower-case it
                        $education = utf8_strtolower( $education );
                    }
                    echo $education;
                    break;
                case EVENT_USERPROFILE_SEXUALORIENTATION_UPDATED:
                    ?>είναι <?php
                    ob_start();
                    Element( 'user/trivial/sex' , $one->User->Profile->Sexualorientation , $one->User->Gender );
                    echo utf8_strtolower( ob_get_clean() );
                    break;
                case EVENT_USERPROFILE_RELIGION_UPDATED:
                    ob_start();
                    switch ( $one->User->Profile->Religion ) {
                        case 'nothing':
                            ?>δεν έχει θρησκευτικές πεποιθήσεις<?php
                            break;
                        default:
                            ?>είναι <?php
                            Element( 'user/trivial/religion' , $one->User->Profile->Religion , $one->User->Gender );
                    }
                    echo utf8_strtolower( ob_get_clean() );
                    break;
                case EVENT_USERPROFILE_POLITICS_UPDATED:
                    ob_start();
                    switch ( $one->User->Profile->Politics ) {
                        case 'nothing':
                            ?>δεν έχει πολιτικές πεποιθήσεις<?php
                            break;
                        default:
                            ?>είναι <?php
                            Element( 'user/trivial/politics' , $one->User->Profile->Politics , $one->User->Gender );
                    }
                    echo utf8_strtolower( ob_get_clean() );
                    break;
                case EVENT_USERPROFILE_SMOKER_UPDATED:
                    switch ( $one->User->Profile->Smoker ) {
                        case 'yes':
                            ?>καπνίζει<?php
                            break;
                        case 'no':
                            ?>δεν καπνίζει<?php
                            break;
                        case 'socially':
                            ?>καπνίζει με παρέα<?php
                            break;
                    }
                    break;
                case EVENT_USERPROFILE_DRINKER_UPDATED:
                    switch ( $one->User->Profile->Drinker ) {
                        case 'yes':
                            ?>πίνει<?php
                            break;
                        case 'no':
                            ?>δεν πίνει<?php
                            break;
                        case 'socially':
                            ?>πίνει με παρέα<?php
                            break;
                    }
                    break;
                case EVENT_USERPROFILE_ABOUTME_UPDATED:
                    ?>έγραψε για τον εαυτό <?php
                    echo $self;
                    ?> "<?php
					$aboutme = utf8_substr( $one->User->Profile->Aboutme , 0 , 20 );
                    echo htmlspecialchars( $aboutme );
					if ( utf8_strlen( $one->User->Profile->Aboutme ) > utf8_strlen( $aboutme ) ) {
						?>...<?php
					}
                    ?>"<?php
                    break;
                case EVENT_USERPROFILE_MOOD_UPDATED:
                    ?>είναι <?php
                    if ( $one->User->Gender == 'm' ) {
                        echo htmlspecialchars( utf8_strtolower( $one->User->Profile->Mood->Labelmale ) );
                    }
                    else {
                        echo htmlspecialchars( utf8_strtolower( $one->User->Profile->Mood->Labelfemale ) );
                    }
                    break;
                case EVENT_USERPROFILE_LOCATION_UPDATED:
                    ?>μένει <?php
                    echo htmlspecialchars( $one->User->Profile->Location->Nameaccusative );
                    break;
                case EVENT_USERPROFILE_HEIGHT_UPDATED:
                    ?>είναι <?php
                    ob_start();
                    Element( 'user/trivial/height' , $one->User->Profile->Height );
                    echo utf8_strtolower( ob_get_clean() );
                    break;
                case EVENT_USERPROFILE_WEIGHT_UPDATED:
                    ?>είναι <?php
                    ob_start();
                    Element( 'user/trivial/weight' , $one->User->Profile->Weight );
                    echo utf8_strtolower( ob_get_clean() );
                    break;
                case EVENT_USERPROFILE_HAIRCOLOR_UPDATED:
                    if ( $one->User->Profile->Haircolor == 'highlights' ) {
                        ?>έχει ανταύγειες<?php
                    }
                    else if ( $one->User->Profile->Haircolor == 'skinhead' ) {
                        ?>είναι skinhead<?php
                    }
                    else {
                        ?>έχει <?php 
                        ob_start();
                        Element( 'user/trivial/haircolor' , $one->User->Profile->Haircolor );
                        echo utf8_strtolower( ob_get_clean() );
                        ?> μαλλί<?php
                    }
                    break;
                case EVENT_USERPROFILE_EYECOLOR_UPDATED:
                    ?>έχει <?php
                    ob_start();
                    Element( 'user/trivial/eyecolor' , $one->User->Profile->Eyecolor );
                    echo utf8_strtolower( ob_get_clean() );
                    ?> χρώμα ματιών<?php
                    break;
            }
            $profileinfo[] = ob_get_clean();
        }
        if ( count( $profileinfo ) > 1 ) {
            $profileinfo[ count( $profileinfo ) - 2 ] = $profileinfo[ count( $profileinfo ) - 2 ] . " και " . $profileinfo[ count( $profileinfo ) - 1 ];
            unset( $profileinfo[ count( $profileinfo ) - 1 ] );
        }
        ?> <?php
        echo implode( ', ', $profileinfo );
    }
?>
