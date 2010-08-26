<?php
    class TestAlbum extends ModelTestCase {
        protected $mAppliesTo = 'models/album';
        protected $mCovers = 'Album';

        public function SetUp() {
            clude( 'models/album.php' );
            clude( 'models/types.php' );
            clude( 'models/user.php' );

            $this->GenerateTestUsers( 3 );
        }
        public function TearDown() {
            $this->DeleteTestUsers();
        }
        public function PreConditions() {
            $this->AssertClassExists( 'Album' );
            $this->AssertMethodExists( 'Album', 'Item' );
            $this->AssertMethodExists( 'Album', 'Create' );
            $this->AssertMethodExists( 'Album', 'Delete' );
        }
        /**
         * @dataProvider ValidIds
         */
        public function TestItem( $id ) {
            $album = Album::Item( $id );
            $this->AssertArrayHasKeys( $album, array( 'id', 'ownerid', 'mainimageid' ) );
            $this->AssertArrayValues( $album, array( 'id' => ( string )$id ) );
        }
        /**
         * @dataProvider ExampleData
         */
        public function TestCreate( $userid, $name, $description ) {
            $album = Album::Create( $userid, $name, $description );
            $this->AssertArrayHasKeys( $album, array( 'id', 'url', 'created' ) );
            $this->AssertArrayValues( $album, array(
                'ownerid' => $userid,
                'name' => $name,
                'description' => $description,
                'mainimageid' => 0,
                'delid' => 0,
                'numcomments' => 0,
                'numphotos' => 0
            ) );

            $id = $album[ 'id' ];
            $album = Album::Item( $id );
            $this->Called( "Album::Item" );
            $this->AssertArrayValues( $album, array(
                'id' => ( string )$id,
                'ownerid' => ( string )$userid,
                'name' => $name,
                'description' => $description
            ) );

            return array( $album[ 'ownerid' ], "neo", "gamato asfasf", $album[ 'id' ] );
        }


        public function ValidIds( $num = 3 ) {
            $res = db( 'SELECT `album_id` FROM `albums` ORDER BY RAND() LIMIT ' . ( string )$num );
            $ret = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                $ret[] = (int)$row[ 0 ];
            }
            return $ret;
        }
        public function ExampleData() {
            $users = $this->GetTestUsers();
            $userid = (int)( $users[ 0 ][ 'id' ] );
            $userid1 = (int)( $users[ 1 ][ 'id' ] );
            $userid2 = (int)( $users[ 2 ][ 'id' ] );
/*
			$egoalbum1 = User::GetEgoAlbumId( $userid1 );
			return array( 
				array( $userid1, 'barcelona', 'I love this place', $egoalbum1  )
			);
*/
            return array(
                array( $userid, 'kamibu summer meeting', 'photos from our meeting at ioannina' ),
                array( $userid, 'barcelona', 'I love this place' ),
                array( $userid1, 'rome', '' ),
                array( $userid1, 'test', 'haha' ),
                array( $userid2, 'foobar', '' ),
                array( $userid2, 'red green', 'blue' ),
                array( $userid1, 'hello', 'world' )
            );
        }
    }

    return New TestAlbum();

?>
