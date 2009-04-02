<?php
    class ElementUserTrivialRelationship extends Element {
        protected $mPersistent = array( 'status', 'gender' );

        public function Render( $status , $gender ) {
            if ( $gender == 'm' || $gender == '-' ) {
                $statuses = array( 
                    '-' => '-',
                    'single' => '���������',
                    'relationship' => '�� �����',
                    'casual' => '�������� �����',
                    'engaged' => '�����������',
                    'married' => '�����������'
                );
            }
            else {
                $statuses = array( 
                    '-' => '-',
                    'single' => '��������',
                    'relationship' => '�� �����',
                    'casual' => '�������� �����',
                    'engaged' => '����������',
                    'married' => '����������'
                );
            }
            echo htmlspecialchars( $statuses[ $status ] );
        }
    }
?>
