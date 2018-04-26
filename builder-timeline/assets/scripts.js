(function ($) {
   
    function create_stories(el,type) {
            var items = $( '.module.module-timeline',el);
            if(el && el.hasClass('.module-timeline') && el.hasClass('module')){
                items = items.add(el);
            }
            if(items.length>0){
                    function callback(){
                        var builder_timeline_data = [];
                        items.find( '.timeline-embed').each(function(){
                            builder_timeline_data[$(this).data('id')] =JSON.parse(window.atob($(this).data('data')));
                        });
                        items.find('.layout-graph').each(function(){
                                if( $( this ).find( '.storyjs-embed' ).length === 0 ) {
                                    var id = $( this ).attr( 'id' ).trim(),
                                        source = builder_timeline_data[id],
                                        embed = $( this ).find( '.timeline-embed' ),
                                        config = embed.data( 'config' );
                                        config.source = source;
                                        createStoryJS( config );
                                }
                        });
                }
                if(typeof createStoryJS==='undefined'){
                    Themify.LoadAsync(builder_timeline.url+'knight-lab-timelinejs/js/storyjs-embed.min.js', callback, '2.33.1', null, function(){
                            return ('undefined' !== typeof createStoryJS);
                    });
                }
                else{
                    callback();
                }
            }
	}

	$( window ).on( 'load', function() { create_stories(); } );
	if (Themify.is_builder_active) {
		$( 'body' ).on( 'builder_load_module_partial', function(e,el,type){
			create_stories(el,type);
		});
	}
	
}(jQuery));