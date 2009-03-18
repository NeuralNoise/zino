<?php
    class ElementAdManagerList extends Element {
        public function Render() {
            global $user;
            global $libs;
            
            if ( !$user->Exists() ) {
                return Redirect( '?p=ads' );
            }
            
            $libs->Load( 'admanager' );
            
            $adfinder = New AdFinder();
            $ads = $adfinder->FindByUser( $user );
            if ( empty( $ads ) ) {
                return Redirect( '?p=admanager/create' );
            }
            
            ?><table class="manager">
                <thead>
                    <tr>
                        <th>���������</th>
                        <th>Target group</th>
                        <th>Budget</th>
                        <th>�������� ��� ���������</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr class="create">
                        <td colspan="4" class="last">
                            <div>
                                <a href="">���������� ���� ���������</a>
                            </div>
                        </td>
                    </tr>
                </tfoot>
                <tbody><?php
                foreach ( $ads as $ad ) {
                    ?><tr>
                        <td><?php
                            Element( 'admanager/view', $ad );
                            ?><a class="edit" href="">�����������</a>
                        </td>
                        <td><?php
                            // ������ 13 - 19 ���� ��� �����
                            // ����� �����������
                            // ����������� 16 ���� ��� �����, �����������, ��� �����
                            // �������� ���� ��� 32 ���� ��� �������� ��� �������
                        ?> - <a class="renew" href="">������</a></td>
                        <td>3,520�</td>
                        <td class="last<?php
                        if ( false ) {
                            ?> soon<?php
                        }
                        ?>"><?php
                        // 176,000
                        // ���� ����������
                        // ��������: ���������� �������
                        ?> - <a class="renew" href="">�������� ���������</a></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table><?php
        }
    }
?>
