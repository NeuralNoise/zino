<?php

	function Type_Get() {
		return array(
			1 => array( 'TYPE_POLL', 'Poll' ),
			2 => array( 'TYPE_IMAGE', 'Image' ),
			3 => array( 'TYPE_USERPROFILE', 'UserProfile' ),
			4 => array( 'TYPE_JOURNAL', 'Journal' )
		);
	}

	function Type_Prepare() {
		$types = Type_Get();
		foreach ( $types as $key => $value ) {
			define( $value[ 0 ], $key );
		}
	}

	function Type_FromObject( $object ) {
		$types = Type_Get();
		$class = get_class( $object );
		foreach ( $types as $key => $value ) {
			if ( $value[ 1 ] == $class ) {
				return $key;
			}
		}
		throw New Exception( "Invalid object class on Type_FromObject" );
	}

	function Type_GetClass( $typeid ) {
		global $water;
		$types = Type_Get();
		$water->Trace( "GetClass: $typeid " . $types[ $typeid ][ 1 ] );
		if ( !isset( $types[ $typeid ] ) ) {
			throw New Exception( "Invalid typeid $typeid no Type_GetClas" );
		}
		return $types[ $typeid ][ 1 ];
	}

	Type_Prepare();

?>
