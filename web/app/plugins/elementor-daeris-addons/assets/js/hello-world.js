( function( $ ) {
	/**
 	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */ 
	var WidgetHelloWorldHandler = function( $scope, $ ) {
		$('[data-toggle="popover"]').popover({ 
        	placement: 'auto',
    	});;
	};
	
	// Make sure you run this code under Elementor.
	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hotspot.default', WidgetHelloWorldHandler );
	} );
} )( jQuery );
