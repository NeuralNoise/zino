<?php
    function ActionAdManagerNew(
        tText $title, tText $body, tFile $uploadimage, tText $url, tInteger $adid
    ) {
        global $libs;
        global $user;
        
        // this user may be logged out; TODO
        if ( !$user->HasPermission( PERMISSION_AD_CREATE ) ) {
            return;
        }
        
        $title = $title->Get();
        $body = $body->Get();
        $url = $url->Get();
        $adid = $adid->Get();
        
        $libs->Load( 'admanager' );
        
        if ( $adid ) {
            $ad = New Ad( $adid );
            if ( !$ad->Exists() ) {
                ?>� ��������� ��� ����������� �� �������������� ��� �������.<?php
                return;
            }
            if ( $ad->Userid != $user->Id ) {
                ?>��� �������� �� �������������� ��� ��������� ��� ��� ��� ������.<?php
                return;
            }
        }
        else {
            $ad = New Ad();
        }
        $ad->Title = $title;
        $ad->Body = $body;
        $ad->Url = $url;
        
        if ( $uploadimage->Exists() ) {
            $image = New Image();
            $image->Name = '';
            $extension = File_GetExtension( $uploadimage->Name );
            switch ( strtolower( $extension ) ) {
                case 'jpg':
                case 'jpeg':
                case 'png':
                case 'gif':
                    break;
                default:
                    ?><script type="text/javascript">
                        alert( 'H ������ ��� ���������� ��� ��� �������������. ����������� �������������� ��� ������ jpg � png.' );
                        window.location.href = <?php
                        echo w_json_encode( $rabbit_settings[ 'webaddress' ] . '/?p=admanager/create' );
                        ?>;
                    </script><?php
                return;
            }
            $setTempFile = $image->LoadFromFile( $uploadimage->Tempname );
            switch ( $setTempFile ) {
                case -1: // Too big
                    ?><script type="text/javascript">
                        alert( 'H ������ ��� ���������� ��� ��� ������ �� ��������� �� 1MB' );
                        window.location.href = <?php
                        echo w_json_encode( $rabbit_settings[ 'webaddress' ] . '/?p=admanager/create' );
                        ?>;
                    </script><?php
                return;
            }
            try {
                $image->Save();
            }
            catch ( ImageException $e ) {
                ?><script type="text/javascript">
                    alert( '������ ��� �������� �� ��� ���������� ��� ������� ���' );
                    window.location.href = <?php
                    echo w_json_encode( $rabbit_settings[ 'webaddress' ] . '/?p=admanager/create' );
                    ?>;
                </script><?php
                return;
            }
            $ad->Imageid = $image->Id;
        }
        if ( $ad->Exists() ) {
            $ad->Save(); // save changes
            $ret = Redirect( '?p=admanager/list' );
        }
        else {
            $ad->Save(); // create ad
            $ret = Redirect( '?p=admanager/demographics&id=' . $ad->Id . '&canskip=true' );
        }
        
        return $ret;
    }
?>
