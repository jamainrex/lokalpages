<script type="text/javascript">

var map, mapObject, marker, mapDivDir;

jQuery(document).ready(function($) { 
$.fn.scrollView = function () {
  return this.each(function () {
    $('html, body').animate({
      scrollTop: $(this).offset().top
    }, 1000);
  });
}
    
    
    $('#content').scrollView();
    mapDivDir = $('#item-direction-map');
    directionPanel = $('#directionsPanel');
    // map
    var latTextfield = $('#item-direction-gpsLatitude');
    var lonTextfield = $('#item-direction-gpsLongitude');
    var item_icon = $('#item-direction-marker-icon').val();
    
    var latTextfield_user = $('#user-direction-gpsLatitude');
    var lonTextfield_user = $('#user-direction-gpsLongitude');

    // Set geo location to Cebu
    var initLat = (latTextfield.val()) ? latTextfield.val() : 10.315510389976486;
    var initLon = (lonTextfield.val()) ? lonTextfield.val() : 123.88569831848145;
    
    var initLat_user = (latTextfield_user.val()) ? latTextfield_user.val() : 10.315510389976486;
    var initLon_user = (lonTextfield_user.val()) ? lonTextfield_user.val() : 123.88569831848145;
    
    
    var rendererOptions = {
        draggable: false
        };
     var directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);  
     var directionsService = new google.maps.DirectionsService();    
    
    mapDivDir.width('75%').height(500).gmap3({
        map: {
            events: {
                click:function(mapLocal, event){
                    map.gmap3({
                        get: {
                            name: "marker",
                            callback: function(marker){
                                marker.setPosition(event.latLng);
                                var pos = marker.getPosition();
                                latTextfield_user.val(pos.lat());
                                lonTextfield_user.val(pos.lng());
                            }
                        }
                    });
                }
            },
            options: {
                center: [initLat,initLon],
                zoom: 13
            }
        },
        marker: {
            values:[
                  {
                      latLng:[initLat_user, initLon_user],
                      tag: 'user'
                  },
                  {
                      latLng:[initLat, initLon],
                      options: {
                                icon: "{!$term->marker}"
                            },
                      tag: 'item'
                  }
            ],
            options: {
                draggable: true
            },
            events: {
                dragend: function(marker, event, context){
                    var tag = context.tag; 
                    //console.log(tag);
                    var pos = marker.getPosition();
                    if( tag == 'user' ){
                        latTextfield_user.val(pos.lat());
                        lonTextfield_user.val(pos.lng());
                    }else if( tag == 'item' ){
                        latTextfield.val(pos.lat());
                        lonTextfield.val(pos.lng());
                    }
                    calcRoute();
                }
            }
        },getgeoloc:{
                callback : function(latLng){        
                    console.log(latLng);
                    var lat = latLng.lat();
                    var lng = latLng.lng();
                    latTextfield_user.val(lat);
                    lonTextfield_user.val(lng);
                    
                    setUserMarkerPos(latLng);
                  
                    calcRoute();

                }
            }
    }, "autofit");
    


  
   mapObject = mapDivDir.gmap3({
        get: {
            name: "map"
        }
    }); 
    
    marker = mapDivDir.gmap3({
        get: {
            name: "marker", tag: "user"
        }
    });
    
itemMarker = mapDivDir.gmap3({
        get: {
            name: "marker", tag: "item"
        }
    });
    
itemMarker.setDraggable(false);
   
directionsDisplay.setMap(mapObject);
directionsDisplay.setPanel(document.getElementById('directionsPanel'));
    
function calcRoute() {
    var start = new google.maps.LatLng( latTextfield_user.val(), lonTextfield_user.val() );
    var end = new google.maps.LatLng( latTextfield.val(), lonTextfield.val() );
    var request = { origin:start, destination:end, travelMode: google.maps.TravelMode.DRIVING }; 
    directionsService.route(request, function(result, status) { if (status == google.maps.DirectionsStatus.OK) { directionsDisplay.setDirections(result); } });
    }
    
function computeTotalDistance(result) {
      var total = 0;
      var myroute = result.routes[0];
      for (var i = 0; i < myroute.legs.length; i++) {
        total += myroute.legs[i].distance.value;
      }
      total = total / 1000.0;
      document.getElementById('total').innerHTML = total + ' km';
}

google.maps.event.addListener(directionsDisplay, 'directions_changed', function() { computeTotalDistance(directionsDisplay.getDirections()); });
    
function setUserMarkerPos(latLng){ marker.setPosition(latLng); }

    latTextfield_user.keyup(function (event) {
        var value = $(this).val();
        var center = mapObject.getCenter();
        var newCenter = new google.maps.LatLng(parseFloat(value),center.lng());
        mapObject.setCenter(newCenter);
        marker.setPosition(newCenter);
    });

    lonTextfield_user.keyup(function (event) {
        var value = $(this).val();
        var center = mapObject.getCenter();
        var newCenter = new google.maps.LatLng(center.lat(), parseFloat(value));
        mapObject.setCenter(newCenter);
        marker.setPosition(newCenter);
    });

}); 

</script>