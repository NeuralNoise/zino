<?php
    class ElementContactsMailtosend extends Element{
        function Render(){
            ?>���: inviter@zino.gr
����: <?php
            Element( 'contacts/email/subject' );
            ?>

<?php
            Element( 'contacts/email/message' );
            return array( 'tiny' => true );
        }
    }
?>
