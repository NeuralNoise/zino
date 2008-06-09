<?php
    function ElementProfileUpdate( $eventlist ) {
        if ( $eventlist[ 0 ]->User->Gender =='f' ) {
            ?>� <?php
        }
        else {
            ?>O <?php
        }
        echo $eventlist[ 0 ]->User->Name;
        $profileinfo = array();
        foreach ( $eventlist as $one ) {
            ob_start();
            switch ( $one->Typeid ) {
                case EVENT_USERPROFILE_EDUCATION_UPDATED:
                    ?>���� <?php
                    ob_start();
                    Element( 'user/trivial/education' , $one->User->Profile->Education );
                    echo strtolower( ob_get_clean() );
                    break;
                case EVENT_USERPROFILE_SEXUALORIENTATION_UPDATED:
                    ?>����� <?php
                    ob_start();
                    Element( 'user/trivial/sex' , $one->User->Profile->Sexualorientation , $one->User->Gender );
                    echo strtolower( ob_get_clean() );
                    break;
                case EVENT_USERPROFILE_RELIGION_UPDATED:
                    ?>����� <?php
                    ob_start();
                    Element( 'user/trivial/religion' , $one->User->Profile->Religion , $one->User->Gender );
                    echo strtolower( ob_get_clean() );
                    break;
                case EVENT_USERPROFILE_POLITICS_UPDATED:
                    ?>����� <?php
                    ob_start();
                    Element( 'user/trivial/politics' , $one->User->Profile->Politics , $one->User->Gender );
                    echo strtolower( ob_get_clean() );
                    break;
                case EVENT_USERPROFILE_SMOKER_UPDATED:
                    switch ( $one->User->Profile->Smoker ) {
                        case 'yes':
                            ?>��������<?php
                            break;
                        case 'no':
                            ?>��� ��������<?php
                            break;
                        case 'socially':
                            ?>�������� �� �����<?php
                            break;
                    }
                    break;
                case EVENT_USERPROFILE_DRINKER_UPDATED:
                    switch ( $one->User->Profile->Drinker ) {
                        case 'yes':
                            ?>�����<?php
                            break;
                        case 'no':
                            ?>��� �����<?php
                            break;
                        case 'socially':
                            ?>����� �� �����<?php
                            break;
                    }
                    break;
                case EVENT_USERPROFILE_ABOUTME_UPDATED:
                    ?>������ ��� ��� ����� <?php
                    echo $self;
                    ?> "<?php
                    echo htmlspecialchars( utf8_substr( $one->User->Profile->Aboutme , 0 , 20 ) );
                    ?>"<?php
                    break;
                case EVENT_USERPROFILE_MOOD_UPDATED:
                case EVENT_USERPROFILE_LOCATION_UPDATED:
                    ?>����� ��� <?php
                    echo htmlspecialchars( $one->User->Profile->Location->Name );
                    break;
                case EVENT_USERPROFILE_HEIGHT_UPDATED:
                    ?>����� <?php
                    ob_start();
                    Element( 'user/trivial/height' , $one->User->Profile->Height );
                    echo strtolower( ob_get_clean() );
                    break;
                case EVENT_USERPROFILE_WEIGHT_UPDATED:
                    ?>����� <?php
                    ob_start();
                    Element( 'user/trivial/weight' , $one->User->Profile->Weight );
                    echo strtolower( ob_get_clean() );
                    break;
                case EVENT_USERPROFILE_HAIRCOLOR_UPDATED:
                    if ( $one->User->Profile->Haircolor == 'highlights' ) {
                        ?>���� ����������<?php
                    }
                    else if ( $one->User->Profile->Haircolor == 'skinhead' ) {
                        ?>����� skinhead<?php
                    }
                    else {
                        ?>���� <?php 
                        ob_start();
                        Element( 'user/trivial/haircolor' , $one->User->Profile->Haircolor );
                        echo strtolower( ob_get_clean() );
                        ?> ������<?php
                    }
                    break;
                case EVENT_USERPROFILE_EYECOLOR_UPDATED:
                    ?>���� <?php
                    ob_start();
                    Element( 'user/trivial/eyecolor' , $one->User->Profile->Eyecolor );
                    echo strtolower( ob_get_clean() );
                    ?> �����<?php
            }
            $profileinfo[] = ob_get_clean();
        }
        if ( count( $profileinfo ) > 1 ) {
            $profileinfo[ count( $profileinfo ) - 2 ] = $profileinfo[ count( $profileinfo ) - 2 ] . " ��� " . $profileinfo[ count( $profileinfo ) - 1 ];
            unset( $profileinfo[ count( $profileinfo ) - 1 ] );
        }
        ?> <?php
        echo implode( ', ', $profileinfo );
    }
?>
