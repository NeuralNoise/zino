<?php
    class ElementUserSettingsPersonalSchool extends Element {
        public function Render( $placeid, $typeid ) {
            global $user;
            global $libs;
             
            if ( ( $placeid > 0 ) && ( $typeid >= 1 && $typeid <= 6 ) ) {
                $libs->Load( 'school/school' );
                $finder = New SchoolFinder();
                $schools = $finder->Find( $placeid, $typeid );
                if ( count( $schools ) > 0 ) {    
                    ?><select>
                        <option value="0"<?php
                        if ( $user->Profile->Schoolid == 0 ) {
                            ?> selected="selected"<?php
                        }
                        ?>>-</option><?php
                        foreach( $schools as $school ) {
                            ?><option value="<?php
                            echo $school->Id;
                            ?>"<?php
                            if ( $user->Profile->Schoolid == $school->Id ) {
                                ?> selected="selected"<?php
                            }
                            ?>><?php
                            Element( 'user/trivial/school', $school );
                            ?></option><?php
                        }
                    ?></select><?php
                }
                else {
                    ?><span>Δεν υπάρχουν εκπαιδευτικά ιδρύματα στην περιοχή</span><?php
                }
            }
        }
    }

?>
