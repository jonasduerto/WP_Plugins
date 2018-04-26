function PTB_MapView(src, callback) {
    var $ = jQuery;
    function Initialize($map, $options, $posts) {
        $($map).css({'height': $options.h,'width': $options.w + ($options.wt == '%' ? '%' : '')});
        var $road = $options.r;
        if ($road == 'ROADMAP') {
            $road = google.maps.MapTypeId.ROADMAP;
        } else if ($road == 'SATELLITE') {
            $road = google.maps.MapTypeId.SATELLITE;
        } else if ($road == 'HYBRID') {
            $road = google.maps.MapTypeId.HYBRID;
        } else if ($road == 'TERRAIN') {
            $road = google.maps.MapTypeId.TERRAIN;
        }
        if ($mobile && $options.dm) {
            $options.d = false;
        }
        var mapOptions = {
            center: new google.maps.LatLng(-34.397, 150.644),
            mapTypeId: $road,
            scrollwheel: $options.s ? true : false,
            draggable: $options.d ? true : false
        };
        var map = new google.maps.Map($map, mapOptions),
            bounds = new google.maps.LatLngBounds(),
            markers = [],
            cached_markers = {},
            is_image = $options.m.indexOf('http')!==-1,
            bubble_opt = {
                    minWidth: 245,
                    maxWidth:300,
                    arrowSize: 15,
                    arrowPosition: 50,
                    arrowStyle: 0,
                    padding:10,
                    disableAutoPan: false,
                    borderWidth:1,
                    borderRadius:8,
                    borderColor:'#4dc7ec',
                    closeSrc:ptb_map.url+'img/close.png'
                };
            
        for (var i in $posts) {
            var $location = $posts[i].l;
             if($location.place){
                    $location.place = JSON.parse($location.place);
                    $location.place.location.lat = $location.place.location[0]?parseFloat($location.place.location[0]):parseFloat($location.place.location.lat);
                    $location.place.location.lng = $location.place.location[1]?parseFloat($location.place.location[1]):parseFloat($location.place.location.lng);
                    var hash = $location.place.location.lat+$location.place.location.lng;
                    if(cached_markers[hash]===undefined){
                        var marker = new MarkerWithLabel({
                                map: map,
                                anchorPoint: new google.maps.Point(0, -29),
                                title:$posts[i].t,
                                icon: is_image?$options.m:($options.m?' ':''),
                                raiseOnDrag: true,
                                labelContent:!is_image?'<i class="ptb_map_icon fa fa-'+$options.m+'"></i>':''
                        });
                        cached_markers[hash] = [];
                        cached_markers[hash].push($posts[i]);
                        marker.setPosition({
                            'lat': $location.place.location.lat,
                            'lng': $location.place.location.lng
                        });
                        map.setCenter({
                            'lat': $location.place.location.lat,
                            'lng': $location.place.location.lng
                        });
                        marker.setVisible(true);
                        bounds.extend(marker.getPosition());
                        markers.push(marker);
                        var infoBubble = new InfoBubble(bubble_opt);
                        google.maps.event.addListener(marker, 'click', (function(marker, j) {
                            return function() {
                                var pos = marker.getPosition(),
                                    k = pos.lat()+pos.lng();
                                infoBubble.setContent(infoWiindow(cached_markers[k]));
                                infoBubble.open(map, marker);
                                $(infoBubble.bubble_).addClass('ptb_map_view_wrapper');
                            };

                        })(marker, i));
                    }
                    else{
                        cached_markers[hash].push($posts[i]);
                    }
                }
                
        }
        if(markers.length>0){
            map.fitBounds(bounds); 
            new MarkerClusterer(map, markers);
        }
    }
    function infoWiindow(data){
        var cl = data.length>1?' ptb_map_multiple':'',
            $html = '<ul class="ptb_map_view_info_window'+cl+'">';
        for(var i in data){
            var img = data[i].i,
                title = data[i].t,
                url = data[i].u,
                info = data[i].l.info;
            $html+='<li>';
            if(img){
                $html+='<a class="ptb_map_view_post_img" href="'+url+'"><img src="'+img+'" alt="'+title+'" title="'+title+'" /></a>';
            }
            $html+='<a class="ptb_map_view_post_title" href="'+url+'">'+title+'</a>';
            if(info){
                info = info.replace(/(?:\r\n|\r|\n)/ig, '<br />');
                $html+='<div class="ptb_map_view_info">'+info+'</div>';
            }
            $html+='</li>';
        }
        $html+='</ul>';
 
        return $html;
    }
    PTB.LoadAsync(ptb_map.url + 'js/markerwithlabel.min.js', function(){
        PTB.LoadAsync(ptb_map.url + 'js/infobubble.min.js', function(){
            PTB.LoadAsync(ptb_map.url + 'js/markerclusterer.min.js', function(){
                
                var $maps = $('.ptb_map_view');
                $maps.each(function () {
                    var $data = JSON.parse(window.atob($(this).data('map'))),
                        $posts = JSON.parse(window.atob($(this).data('posts')));
                    Initialize(this, $data, $posts);
                    $(this).data({'posts':null,'map':null}).removeAttr('data-posts data-map');
                });
                
            }, null, ptb_map.ver, function() {
                return ('undefined' !== typeof MarkerClusterer);
            });

        }, null, ptb_map.ver, function() {
            return ('undefined' !== typeof InfoBubble);
        });
        
    }, null, ptb_map.ver, function() {
        return ('undefined' !== typeof MarkerWithLabel);
    });
  
}
(function ($) {
    'use strict';
	function loadScript(src, callback) {
            var script = document.createElement("script");
            script.type = "text/javascript";
            if (callback) script.onload = callback;
            document.getElementsByTagName("head")[0].appendChild(script);
            script.defer = true;
            script.async = true;
            script.src = src;
        }
    $(document).ready(function () {
        if ($('.ptb_map_view').length > 0) {
            if (typeof google !== 'object' || typeof google.maps !== 'object') {
               loadScript('//maps.googleapis.com/maps/api/js?v=3&signed_in=false&callback=PTB_MapView&language=' + ptb_map.lng+'&key='+ptb_map.map_key);
            } else {
                PTB_MapView();
            }
        }
    });


}(jQuery));