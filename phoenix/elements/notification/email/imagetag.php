<?php
    /// Content-type: text/plain ///
    class ElementNotificationEmailImagetag extends Element {
        public function Render( Notification $notification ) {
            global $rabbit_settings;
        
            $image = New Image( $notification->Item->Imageid );
        
            $from = $notification->FromUser;

            w_assert( $from instanceof User );
            w_assert( $from->Exists() );

            ob_start();
            if ( $from->Gender == 'f' ) {
                ?>�<?php
            }
            else {
                ?>�<?php
            }
            ?> <?php
            echo $from->Name;
            ?> �� ���������� �� ��� ������<?php
            if ( !empty( $image->Name ) ) {
                ?>, ��� "<?php
                echo $image->Name;
                ?>"<?php
            }
            $subject = ob_get_clean();
            echo $subject;
            
            ?>.
            
��� �� ���� �� ���� ������ �� ���������� <?php
            if ( $from->Gender == 'f' ) {
                ?>�<?php
            }
            else {
                ?>�<?php
            }
            ?> <?php
            echo $from->Name;
            ?> ���� ���� ���� �������� ��������:
            
<a href="
<?php
            echo $rabbit_settings[ 'webaddress' ];
            ?>/?p=photo&amp;id=<?php
            echo $image->Id;
            ?>"><?php
            echo $rabbit_settings[ 'webaddress' ];
            ?>/?p=photo&id=<?php
            echo $image->Id;
            ?></a><?php
            
            Element( 'email/footer' );
            
            return $subject;
        }
    }
?>