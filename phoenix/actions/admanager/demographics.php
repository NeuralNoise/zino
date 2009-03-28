<?php
    function ActionAdManagerDemographics(
        tInteger $adid, tInteger $minage, tInteger $maxage, tInteger $sex, tIntegerArray $places
    ) {
        global $libs;
        global $user;
        
        $libs->Load( 'admanager' );
        
        $adid = $adid->Get();
        $minage = $minage->Get();
        $maxage = $maxage->Get();
        $sex = $sex->Get();
        
        // this user may be logged out; TODO
        if ( !$user->HasPermission( PERMISSION_AD_EDIT ) ) {
            return;
        }
        $ad = New Ad( $adid );
        if ( !$ad->Exists() ) {
            ?>� ��������� ��� ����������� �� �������������� ��� �������.<?php
            return;
        }
        if ( $ad->Userid != $user->Id ) {
            ?>��� �������� �� �������������� ��� ��������� ��� ��� ��� ������.<?php
            return;
        }
        
        $ad->Minage = $minage;
        $ad->Maxage = $maxage;
        $ad->Sex = $sex;
        
        $ad->Save();
        
        return Redirect( '?p=admanager/list' );
    }
?>
