<?php
    function ElementWYSIWYG( $id = 'wysiwyg', $target = 'text', $contents = '' ) {
        ?><div id="<?php
        echo $id;
        ?>" class="wysiwyg"><?php
        echo $contents;
        ?></div>

        <div class="wysiwyg-control">
            <form id="wysiwyg-control-video">
                <br /><br />������������� ��� ��������� ��� video ��� YouTube:
                <br /><br />
                <input type="text" value="" style="width:400px" />
                <br /><br />
                <input type="submit" value="��������" onclick="WYSIWYG.InsertVideo('<?php
                echo $target;
                ?>', $( this.parentNode ).find( 'input' )[ 0 ].value );Modals.Destroy();" />
                <input type="button" value="�������" onclick="Modals.Destroy()" />
            </form>
            <form id="wysiwyg-control-image-start">
                <br /><br />
                <ul>
                    <li><a href="" onclick="Modals.Destroy();Modals.Create($('wysiwyg-control-image-url')[0])return false;">�������� ������� �� ��� ��������� ���</a></li>
                    <li><a href="" onclick="Modals.Destroy();return false;">�������� ������� ��� �� albums ���</a></li>
                    <li><a href="" onclick="Modals.Destroy();return false;">�������� ������� ��� ��� ���������� ���</a></li>
                </ul>
                <input type="button" value="�������" onclick="Modals.Destroy()" />
            </form>
            <form id="wysiwyg-control-image-url">
                <br /><br />������������� ��� ��������� ��� �������:
                <br /><br />
                <input type="text" value="" style="width:400px" />
                <br /><br />
                <input type="submit" value="��������" onclick="WYSIWYG.InsertImage('<?php
                echo $target;
                ?>', $( this.parentNode ).find( 'input' )[ 0 ].value );Modals.Destroy();" />
                <input type="button" value="�������" onclick="Modals.Destroy()" />
            </form>
        </div>
        <?php
    }
?>
