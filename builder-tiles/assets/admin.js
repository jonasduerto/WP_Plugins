jQuery(function($){
        function callback(el){
            el.closest( '.themify_builder_tab' ).find( '.tb-tile-options' ).hide().filter( '.tb-tile-options-' + el.prop( 'id' ) ).not( '._tf-hide' ).show();
        }
        $( 'body',top_iframe ).on('click','#type_front a, #type_back a',function(e){
            callback($(this));
        });
	$( 'body' ).on( 'editing_module_option', function(e,type,settings,$context){
            if(type==='tile'){
                setTimeout(function(){
                    callback($('#type_front',$context).find('.selected'));
                    callback($('#type_back',$context).find('.selected'));
                },1000);
                if(themifybuilderapp.mode==='visual'){
                    var switchSide = function(side){
                        if(typeof Builder_Tiles!=='undefined'){
                            side = side==='#tb_tile_back'?'back':'front';
                            Builder_Tiles.flip_tile(themifybuilderapp.liveStylingInstance.$liveStyledElmt,side);
                            if(side==='front'){
                                themifybuilderapp.liveStylingInstance.$liveStyledElmt.find('.tile-back').removeClass('wow animated');
                            }
                        }
                    };
                    $(this).on('themify_builder_tabsactive.tiles',function(e,id, container){
                        if(id==='#tb_tile_back' || id==='#tb_tile_front'){
                            switchSide(id);
                        }
                    }).
                    one('themify_builder_lightbox_close',function(){
                        $(this).off('themify_builder_tabsactive.tiles builder_load_module_partial.tiles');
                    }).on( 'builder_load_module_partial.tiles', function(e,el,type){
                        switchSide($('#themify_builder_tabs_tile',$context).find('.current a').attr('href'));
                    });
                }
            }
	});
        
        

});