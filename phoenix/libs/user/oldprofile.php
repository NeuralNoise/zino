<?php

    class OldUserProfile extends Satori {
        protected $mDbTableAlias = 'olduserprofiles';
        
        protected function Relations() {
            $this->Location = $this->HasOne( 'Place', 'Placeid' );
            $this->School = $this->HasOne( 'School', 'Schoolid' );
            $this->Mood = $this->HasOne( 'Mood', 'Moodid' );
        }
        protected function LoadDefaults() {
            $this->Education = 0;
            $this->Sexualorientation = '-';
            $this->Religion = '-';
            $this->Politics = '-';    
            $this->Eyecolor = '-';
            $this->Haircolor = '-';
            $this->Smoker = '-';
            $this->Drinker = '-';
            $this->Height = -3;
            $this->Weight = -3;
            $this->Songwidgetid = -1;
        }
    }

?>
