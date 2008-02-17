<?php

    /*
    class UserSearch extends Search {
        protected $mModel = 'User';

        public function GetLatest( $n = 5 ) {
            $this->Limit = $n;
            $this->OrderBy = 'Created';

            return $this->Get();
        }
    }
    */

    class Search extends Overloadable {
        protected $mDb;
        protected $mDbTable;
        protected $mDbFields;
        protected $mLimit;
        protected $mOffset;
        protected $mSortBy;
        protected $mOrder;

        protected function SetLimit( $limit ) {
            $this->mLimit = $limit;
        }
        protected function SetOffset( $offset ) {
            $this->mOffset = $offset;
        }
        protected function SetSortBy( $attribute ) {
            $field = $this->GetFieldFromAttribute( $attribute );
            $table = $this->mDbTable->Alias;

            $this->mSortBy = "`$table`.`$field`";
            $this->mOrder = 'DESC';
        }
        protected function SetOrder( $order ) {
            $this->mOrder = strtoupper( $order );
        }
        private function GetFieldFromAttribute( $attribute ) {
            return $this->mDbFields[ $attribute ];
        }
        private function CreateQuery() {
            $query = 'SELECT * FROM ' . $this->mDbTable . ' ';
            
            if ( $this->mSortBy != NULL ) {
                $query .= ' ORDER BY ' . $this->mSortBy . ' ' . $this->mOrder . ' ';
            }
            $query .= ' LIMIT ' . $this->mOffset . ', ' . $this->mLimit . ';';

            return $this->mDb->Prepare( $query );
        }
        public function Get() {
            $res = $this->CreateQuery()->Execute();
            $ret = array();
            while ( $row = $res->FetchArray() ) {
                $ret[] = New TestModel( array( "test_id" => 1, "test_text" => "foobar",  "test_num" => 10 ) );
                print_r( $ret[ count( $ret ) - 1 ] );
                die();
            }

            return $ret;
        }
        public function __construct( $database = false ) {
            global $db;

            if ( $database !== false ) {
                $this->mDb = $database;
            }
            else {
                $this->mDb = $db;
            }

            $this->mLimit = 20;
            $this->mOffset = 0;

            $prototype = New $this->mModel(); // MAGIC!
            $this->mDbTable = $prototype->DbTable;
            $this->mDbFields = array();

            $fields = $prototype->DbFields;
            foreach ( $fields as $field => $attribute ) {
                $this->mDbFields[ $attribute ] = $field;    
            }
        }
    }

?>
