<?php
    class ElementAdManagerDemographics extends Element {
        public function Render( tInteger $adid ) {
            global $libs;
            global $user;
            
            $libs->Load( 'admanager' );
            $libs->Load( 'place' );
            
            $adid = $adid->Get();
            $ad = New Ad( $adid );
            
            if ( !$ad->Exists() ) {
                return;
            }
            if ( !$user->Exists() || $user->Id != $ad->Userid ) {
                return;
            }
            
            ?><div class="buyad">
                <h2 class="ad">��������� ��� Zino</h2>
                <div class="create demographics">
                    <h3>�������� target group</h3>
                    <div class="left" style="width:400px;padding-left:50px">
                        <div class="input" style="float:left">
                            <label>����:</label>
                            <select>
                                <option selected="selected">��������</option>
                                <option>������</option>
                                <option>��������</option>
                            </select>
                        </div>
                        
                        <div class="input" style="margin-left: 230px">
                            <label>�������:</label>
                            <select name="place">
                                <option value="0" selected="selected">��������</option>
                                <?php
                                    $placefinder = New PlaceFinder();
                                    $places = $placefinder->FindAll();
                                    foreach ( $places as $place ) {
                                        ?><option value="<?php
                                        echo $place->Id;
                                        ?>"><?php
                                        echo htmlspecialchars( $place->Name );
                                        ?></option><?php
                                    }
                                ?>
                            </select>
                        </div>
                        
                        <div class="input" style="float:left">
                            <label>��������� ������:</label>
                            <select>
                                <option selected="selected">��������</option>
                                <?php
                                    for ( $i = 13; $i <= 64; ++$i ) {
                                        ?><option value="<?php
                                        echo $i;
                                        ?>"><?php
                                        echo $i;
                                        ?></option><?php
                                    }
                                ?>
                            </select>
                        </div>
                        
                        <div class="input" style="margin-left: 230px">
                            <label>���������� ������:</label>
                            <select>
                                <option selected="selected">��������</option>
                                <?php
                                    for ( $i = 14; $i <= 65; ++$i ) {
                                        ?><option value="<?php
                                        echo $i;
                                        ?>"><?php
                                        echo $i;
                                        ?></option><?php
                                    }
                                ?>
                            </select>
                        </div>
                        <a href="" onclick="return false;" class="start" style="margin-top:50px">����������</a>
                        <a href="" onclick="return false;" style="width: 250px;display:block;padding-top:5px;margin:auto;text-align: center;font-size:90%">� ���������� ���� �� ����</a>
                    </div>
                    <div class="right">
                        <div class="ads"><?php
                            Element( 'admanager/view', $ad );
                        ?></div>
                    </div>
                    <div class="eof"></div>
                </div>
            </div><?php
        }
    }
?>
