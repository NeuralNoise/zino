<?php

    global $libs;
    $libs->Load( 'comment' );

    class TestComment extends Comment {
        protected $mDbTableAlias = 'testcomments';
    }

    class CommentTest extends Testcase {
        protected $mAppliesTo = 'libs/comment';
        private $mTable;

        public function SetUp() {
            global $rabbit_settings;

            $databasealiases = array_keys( $rabbit_settings[ 'databases' ] );
            w_assert( isset( $GLOBALS[ $databasealiases[ 0 ] ] ) );
            $db = $GLOBALS[ $databasealiases[ 0 ] ];

            $table = New DbTable( $db, 'comments', 'comments' );
            $table->Copy( 'testcomments', 'testcomments' );

            $this->mTable = New DbTable( $db, 'testcomments', 'testcomments' );
        }
        public function TestClassesExist() {
        }
        public function TestMethodsExist() {
        }
        public function TestFunctionsExist() {
        }
        public function TestCreateComment() {
            $comment = New TestComment();
            $comment->Typeid = 0;
            $comment->Itemid = 1;
            $comment->Save();    
        }
        public function TearDown() {
            $this->mTable->Delete();
        }
    }

    return New CommentTest();

?>
