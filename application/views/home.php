    <div>
        <button style="margin-bottom:20px;" class="button button-3d button-primary button-rounded" id="toogle_menu_button">Map Menu</button>
    </div>
    <div style="margin-bottom: 20px;" id="tabs" style="display:none;" >
          <ul>
            <li><a href="#tabs-1" class="">Search Item</a></li>
            <li><a href="#tabs-2">Map Settings</a></li>
          </ul>
          <div style="height:150px" id="tabs-1" >
            <form style="display:block;margin-bottom: 10px;" id="form_search_item">
                <input placeholder="Search Site ID..." style="padding: 3px;width:250px;" type="text" id="search_item" />
                <input type="submit" style="padding: 3px;width:70px;margin-bottom: 4px;" id="search_submit" /><br />
                <input type="radio" name="search_type" class="search_feature" value="site_id" checked>Site ID</input><br />
                <input type="radio" name="search_type" class="search_feature" value="odp_name">ODP Name</input><br />
                <input type="radio" name="search_type" class="search_feature" value="lat_long">Lat, Long</input><br />
            </form>
          </div>
          <div style="height:150px" id="tabs-2">
            <button style="padding: 3px;width:200px;display: block;" id="clear_items">Clear All Items</button>
            <table style="margin-top:10px;display:block">
                <tr>
                    <td style="padding-right: 7px;padding-left: 7px;">Radius Site</td>
                    <td style="padding-right: 7px;padding-left: 7px;"><input style="padding-left: 5px;" type="text" id="radius_site" value="5000"/>
                    </td>
                    <td>Meter</td>
                </tr>
                <tr>
                    <td style="padding-right: 7px;padding-left: 7px;">Radius ODP</td>
                    <td style="padding-right: 7px;padding-left: 7px;"><input style="padding-left: 5px;" type="text" id="radius_odp" value="200" /></td>
                    <td>Meter</td>
                </tr>
            </table>
          </div>
    </div>

<div class="card">
    <div style='width:100%' id='map'></div>
</div>
<!-- FOR JQUERY -->

<div id="loading" style="display: none;">
    <img src="<?php echo site_url(); ?>images/loading_2.gif" />
</div>

<div class="modal"><!-- Place at bottom of page --></div>

<style>
.modal {
    display:    none;
    position:   fixed;
    z-index:    1000;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 0, 0, 0, .8 ) 
                url('<?php echo site_url(); ?>images/loading_2.gif') 
                50% 50% 
                no-repeat;
}

/* When the body has the loading class, we turn
   the scrollbar off with overflow:hidden */
body.loading .modal {
    overflow: hidden;   
}

/* Anytime the body has the loading class, our
   modal element will be visible */
body.loading .modal {
    display: block;
}

#map.fullscreen{
    z-index: 9999; 
    width: 100%; 
    height: 100%; 
    position: fixed; 
    top: 0; 
    left: 0; 
 }

.legend {
    height: 90px;
    line-height: 18px;
    color: #555;
    background-color: #ffffff;
    opacity: 0.7;
}

.legend_distance{
    width: 126px;
    height: 126px;
    line-height: 18px;
    color: #555;
    background-color: #ffffff;
    opacity: 0.7;
}
.legend i {
    width: 18px;
    height: 18px;
    float: left;
    margin-right: 8px;
    opacity: 1;
}
</style>

<script>
    

    $('#try').click(function(e){
        $('#map').toggleClass('fullscreen'); 
    });

    $('#tabs').css('display', 'none');
    $( function() {
        $( "#tabs" ).tabs();
    });

    $('#toogle_menu_button').click(function(){
        if($("#tabs").css('display') == 'none'){
            // $('#tabs').attr("hidden", false);
            // console.log('hiddeeen');
            $("#tabs").slideDown();
        }
        else{
            // console.log('sss');
            // $('#tabs').attr("hidden", true);
            $("#tabs").slideUp();
        }
        // alert('www');
    });


    $body = $("body");

    $(document).on({
        ajaxStart: function() { $body.addClass("loading");    },
        ajaxStop: function() { $body.removeClass("loading"); }    
    });


    var osmLink = '<a href="http://openstreetmap.org">OpenStreetMap</a>',
        thunLink = '<a href="http://thunderforest.com/">Thunderforest</a>';
    
    var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        osmAttrib = '&copy; ' + osmLink + ' Contributors',
        landUrl = 'http://{s}.tile.thunderforest.com/landscape/{z}/{x}/{y}.png',
        thunAttrib = '&copy; '+osmLink+' Contributors & '+thunLink;

    var osmMap = L.tileLayer(osmUrl, {attribution: osmAttrib}),
        landMap = L.tileLayer(landUrl, {attribution: thunAttrib});

    var googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',{
        maxZoom: 20,
        subdomains:['mt0','mt1','mt2','mt3']
    });

    var googleStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',{
        maxZoom: 20,
        subdomains:['mt0','mt1','mt2','mt3']
    });

    var googleHybrid = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}',{
        maxZoom: 20,
        subdomains:['mt0','mt1','mt2','mt3']
    });

    var googleTerrain = L.tileLayer('http://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}',{
        maxZoom: 20,
        subdomains:['mt0','mt1','mt2','mt3']
    });


    var mbAttr = 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
        '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
        'Imagery © <a href="http://mapbox.com">Mapbox</a>',
    mbUrl = 'https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw';

    var id_a            = '';
    var latitude_a      = '';
    var longitude_a     = '';
    var id_b            = '';
    var latitude_b      = '';
    var longitude_b     = '';
    var latlongs_line   = [];
    var line_of_sight   = '';
    var lat_onclick     = '';
    var long_onclick    = '';
    var witel           = '';
    //var radius_site     = $('#radius_site').val();
    //var radius_odp      = $('#radius_odp').val();
    var clickCircleSite;
    var clickCircleODP;
    var popup   = new L.popup();

    var grayscale           = L.tileLayer(mbUrl, {id: 'mapbox.light', attribution: mbAttr}),
        streets             = L.tileLayer(mbUrl, {id: 'mapbox.streets',   attribution: mbAttr});
    var sites               = new L.layerGroup(),
        links               = new L.layerGroup(),
        odp                 = new L.layerGroup(),
        circleSite          = new L.layerGroup(),
        circleODP           = new L.layerGroup(),
        distance_2_points   = new L.layerGroup();

    var map = L.map('map', 
    {
        center: [-7.2099, 116.6748046875],
        zoom: 7,
        layers: [googleStreets, sites],
        contextmenu: true,
        contextmenuWidth: 180,
        contextmenuItems: [
            {
              text: 'Show coordinates',
              callback: showCoordinates
            }, {
              text: 'Center map here',
              callback: centerMap
            }, '-', {
              text: 'Zoom in',
              icon: '<?php echo site_url(); ?>images/zoom-in.png',
              callback: zoomIn
            }, {
              text: 'Zoom out',
              icon: '<?php echo site_url(); ?>images/zoom-out.png',
              callback: zoomOut
            }, {
              text: 'Show Radius-Site',
              callback: showRadiusSite
            }, {
              text: 'Show Radius-ODP',
              callback: showRadiusODP
            }
            ]
        },

    );

    var legend_distance = L.control({position: 'bottomright'});

    legend_distance.onAdd = function (map) {

        var div = L.DomUtil.create('div', 'info legend_distance');
        div.innerHTML = `<table style="width:126px;height:126px;" id="bootstrap-data-table" class="table">
                            <tr>
                                <th>Site</th>
                                <td><img style="height:20px" src="<?php echo site_url(); ?>images/marker-icon.png" /></td>
                            </tr>
                            <tr>
                                <th>ODP</th>
                                <td><img style="height:20px" src="<?php echo site_url(); ?>images/marker/odp_pink.png" /></td>
                            </tr>
                            <tr>
                                <th>Link Site</th>
                                <td><img style="height:20px" src="<?php echo site_url(); ?>images/blue_lines.png" /></td>
                            </tr>
                    </table>`;
        return div;
    };

    var legend = L.control({position: 'bottomleft'});

    legend.onAdd = function (map) {

        var div = L.DomUtil.create('div', 'info legend');
        div.innerHTML = `<table background="" id="bootstrap-data-table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                            <th>Point A</th>
                            <th>Point B</th>
                            <th>Distance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <td style="height:46px;"><div id='id_a'></div></td>
                            <td><div id='id_b'></div></td>
                            <td><div id='distance'></div></td>
                            </tr>
                        </tbody>
                    </table>`;
        return div;
    };

    legend_distance.addTo(map);
    legend.addTo(map);

    var odpIcon = new L.Icon({
        iconUrl: '<?php echo site_url(); ?>images/marker/odp_pink.png',
        // iconUrl: '<?php echo site_url(); ?>images/marker/marker-icon-2x-red.png',
        // shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [20, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    var redIcon = new L.Icon({
        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        // iconUrl: '<?php echo site_url(); ?>images/marker/marker-icon-2x-red.png',
        // shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    var blackIcon = new L.Icon({
        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-black.png',
        // iconUrl: '<?php echo site_url(); ?>images/marker/marker-icon-2x-red.png',
        // shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });


    var blueIcon = new L.Icon({
        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
        // iconUrl: '<?php echo site_url(); ?>images/marker/marker-icon-2x-red.png',
        // shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    var greenIcon = new L.Icon({
        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
        // iconUrl: '<?php echo site_url(); ?>images/marker/marker-icon-2x-red.png',
        // shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    var yellowIcon = new L.Icon({
        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-yellow.png',
        // iconUrl: '<?php echo site_url(); ?>images/marker/marker-icon-2x-red.png',
        // shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    var baseLayers = {
        "osmMap" : osmMap,
        "landMap" : landMap,
        "grayscale": grayscale,
        "streets": streets, 
        "googleSat" : googleSat,
        "googleTerrain" : googleTerrain,
        "googleHybrid" : googleHybrid,
        "googleStreets" : googleStreets
    };

    var overlays = {
        "sites"             : sites,
        "links"             : links,
        "odp"               : odp,
        "distance_2_points" : distance_2_points,
        "circleSite"        : circleSite,
        "circleODP"         : circleODP
    };
    map.addLayer(links);
    map.addLayer(odp);
    map.addLayer(circleSite);
    map.addLayer(circleODP)
    map.addLayer(distance_2_points);

    L.control.layers(baseLayers,overlays).addTo(map);

    function showCoordinates (e) {
        alert(e.latlng);
    }

    function centerMap (e) {
        map.panTo(e.latlng);
    }

    function zoomIn (e) {
        map.zoomIn();
    }

    function zoomOut (e) {
        map.zoomOut();
    }

    $('#clear_items').click(function(){
        clearODP();
        clearLinks();
        clearSites();
        clearDistance();
    });

    L.DomUtil.addClass(map._container, 'crosshair-cursor-enabled');

    function showRadiusSite(e) {
        lat_onclick     = e.latlng.wrap().lat.toString();
        long_onclick    = e.latlng.wrap().lng.toString();
        // console.log("lat onclick : " + lat_onclick);
        // console.log("long onclick : " + long_onclick);
        // console.log("radius Site : " + $('#radius_site').val());

        if (clickCircleSite != undefined) {
            map.removeLayer(clickCircleSite);
        };

        var html =  '<table style=\'width:400px;\' class=\'table_popup\' border=\'1\'>' +
                    '<tr><td>Center Latitude</td><td><div class=\'latitude_radius_site\'>'+ lat_onclick +'</td></tr>' + 
                    '<tr><td>Center Longitude</td><td><div class=\'longitde_radius_site\'>'+ long_onclick +'</td></tr>' + 
                    '</table>' +
                    '<div style="margin-top:5px;"><button style=\'width:150px;text-align=left\' class=\'button button-primary button-rounded button-small show_site\'>Show Site</button></div>' +
                    '<div style="margin-top:5px;"><button style=\'width:150px;text-align=left\' class=\'button button-primary button-rounded button-small show_link\'>Show Links</button></div>' +
                    '<div style="margin-top:5px;"><button style=\'width:150px;text-align=left\' class=\'button button-primary button-rounded button-small show_all_link_site\'>Show Site & Links</button></div>';
                    


        clickCircleSite = L.circle([lat_onclick, long_onclick], parseInt($('#radius_site').val()) , {
            color: 'blue',
            fillColor: 'blue',
            fillOpacity: 0.5
        }).addTo(circleSite).bindPopup(html, {maxWidth:"auto"});

        popup
            .setLatLng(e.latlng)
            .setContent(html)
            .openOn(circleSite);
        map.fitBounds(clickCircleSite.getBounds());
    }

    function showRadiusODP(e) {
        lat_onclick     = e.latlng.wrap().lat.toString();
        long_onclick    = e.latlng.wrap().lng.toString();

        if (clickCircleODP != undefined) {
            map.removeLayer(clickCircleODP);
        };

        var html = '<table style=\'width:400px;\' class=\'table_popup\' border=\'1\'>' +
                    '<tr><td>Center Latitude</td><td><div class=\'latitude_radius_site\'>'+ lat_onclick +'</td></tr>' + 
                    '<tr><td>Center Longitude</td><td><div class=\'longitde_radius_site\'>'+ long_onclick +'</td></tr>' + 
                    '<tr><td><select style=\'width:100%;height:100%;\' class=\'witel\'>';
                                html += '<?php 
                        foreach($list_witel as $row){
                            echo "<option value=\'".$row['witel']."\'>". $row['witel'] ."</option>";
                        }
                    ?>';
            html += '</td>' + 
                    '<td><button style=\'width:100%;height:100%;display:inline-block;\' class=\'button button-primary button-rounded button-small show_odp\'>Show ODP</button></div></td></tr>' +
                    '</table>' +
                    '</select></div>';

        clickCircleODP = L.circle([lat_onclick, long_onclick], parseInt($('#radius_odp').val()), {
            color: 'red',
            fillColor: 'red',
            fillOpacity: 0.5
        }).addTo(circleODP).bindPopup(html, {maxWidth:"auto"});

        popup
            .setLatLng(e.latlng)
            .setContent(html)
            .openOn(circleODP);
        map.fitBounds(clickCircleODP.getBounds());
    }

    $('#form_search_item').submit(function(event) {
    // $('#search_submit').click(function(){
        event.preventDefault();
        var type = $('input[name=search_type]:checked').val();

        if(type == 'site_id'){
            var data = {
                site_id : $("#search_item").val().toUpperCase()
            };
            
            $.ajax({
              type: "POST",
              url: "<?php echo site_url(); ?>home/get_a_site",
              data: data,
              success: function(arr) {
                var icon = '';
                var arr = jQuery.parseJSON(arr);
                console.log(arr);
                if(arr == 0){
                    alert('site ID : ' + $("#search_item").val().toUpperCase() + ' not found!');
                    return;
                }
                var html =  '<table class=\'table_popup\' style=\'width:300px\' border=\'1\'>' + 
                            '<tr>' +
                            '<td>Site ID</td><td><div class=\'id\'>' + arr['site_id'] + '</div></td></tr>'+
                            '<td>Site Name</td><td><div class=\'site_name\'>' + arr['site_name'] + '</div></td></tr>'+
                            '<tr><td>Latitude</td><td><div class=\'lat\'>'+arr['lat']+'</div></td></tr>' +
                            '<tr><td>Longitude</td><td><div class=\'long\'>'+arr['long']+'</div></td></tr>' +
                            '<tr><td>Transport 3G</td><td><div class=\'transport_3g\'>'+arr['transport_3g']+'</div></td></tr>' +
                            '<tr><td>OLT</td><td><div class=\'olt\'>'+arr['olt']+'</div></td></tr>' +
                            '<tr><td>OLT Port</td><td><div class=\'olt_port\'>'+arr['olt_port']+'</div></td></tr>' +
                            '<tr><td>Metro</td><td><div class=\'metro\'>'+arr['metro']+'</div></td></tr>' +
                            '<tr><td>Metro Port</td><td><div class=\'metro_port\'>'+arr['metro_port']+'</div></td></tr>' +
                            '<tr><td>RAN</td><td><div class=\'ran\'>'+arr['ran']+'</div></td></tr>' +
                            '<tr><td>IP ONT</td><td><div class=\'ip_ont\'>'+arr['ip_ont']+'</div></td></tr>' +
                            '<tr><td>OLT Port</td><td><div class=\'tipe_transmisi\'>'+arr['tipe_transmisi']+'</div></td></tr>' +
                            '<tr><td>Warna</td><td><div class=\'warna\'>'+arr['warna']+'</div></td></tr>' +
                            '<tr>' +
                            '<td style\'margin-bottom:4px;\'><button style="width:100%;" class=\'button button-primary button-rounded button-small point_a_set\'>Set As Point A</button></td>' +
                            '<td style=\'margin-top:4px\'><button style="width:100%;" class=\'button button-primary button-rounded button-small point_b_set\'>Set As Point B</button></td>' +
                            '</tr></table>';
                if(arr['warna'] == "Hitam"){
                    icon_ = blackIcon;
                }
                else if(arr['warna'] == "Kuning"){
                    icon_ = yellowIcon;
                }
                else if(arr['warna'] == "Merah"){
                    icon_ = redIcon;
                }
                else if(arr['warna'] == "Biru"){
                    icon_ = blueIcon;
                }                          
                L.marker([arr['lat'],arr['long']],{icon: icon_}).bindPopup(html).addTo(sites);
                map.setView([arr['lat'],arr['long']],10);
              }
            });
        }
        else if(type == 'odp_name'){
            var data = {
                odp_name : $("#search_item").val().toUpperCase()
            };
            
            $.ajax({
              type: "POST",
              url: "<?php echo site_url(); ?>home/get_a_odp",
              data: data,
              success: function(arr) {
                var arr = jQuery.parseJSON (arr);
                if(arr == 0){
                    alert('ODP : ' + $("#search_item").val().toUpperCase() + ' not found!');return;
                }
                console.log(arr);
                var html =  '<table class=\'table_popup\' style=\'width:300px\' border=\'1\'>' + 
                            '<tr>' +
                            '<td>Site ID</td><td><div class=\'id\'>' + arr['odp_name'] + '</div></td></tr>'+
                            '<td>Witel</td><td><div class=\'witel\'>' + arr['witel'] + '</div></td></tr>'+

                            '<tr><td>Latitude</td><td><div class=\'lat\'>'+arr['lat']+'</div></td></tr>' +
                            '<tr><td>Longitude</td><td><div class=\'long\'>'+arr['long']+'</div></td></tr>' +
                            '</table>' + 
                            '<div style=\'margin-top:7px;\'>' +
                            '<div><button class=\'button button-primary button-rounded button-small point_a_set\'>Set As Point A</button></div>' +
                            '<div><button class=\'button button-primary button-rounded button-small point_b_set\'>Set As Point B</button></div>' +
                            '</div>';
                L.marker([arr['lat'],arr['long']], {icon: odpIcon}).bindPopup(html).addTo(odp);
                map.setView([arr['lat'],arr['long']],10);
              }
            });
        }
        else{
            var arr = $("#search_item").val().toUpperCase().split(',');
            map.setView([arr[0],arr[1]],10);
        }

        
    });
    
    $('body').on('click', '.show_site', function() {
        // alert('berhasil');
        var data = {
            lat     : lat_onclick,
            long    : long_onclick,
            radius  : $('#radius_site').val()
        };
        console.log(data);
        $.ajax({
          type: "POST",
          url: "<?php echo site_url(); ?>home/get_data_site",
          data: data,
          success: function(arr) {
            var icon_ = '';
            var arr = jQuery.parseJSON (arr);
            if(arr.length == 0){
                alert('No Sites Found!');
                return;
            }
            console.log(arr);
            for(var i=0;i<arr.length;i++){
                var html =  '<table class=\'table_popup\' style=\'width:300px\' border=\'1\'>' + 
                            '<tr>' +
                            '<td>Site ID</td><td><div class=\'id\'>' + arr[i]['site_id'] + '</div></td></tr>'+
                            '<td>Site Name</td><td><div class=\'site_name\'>' + arr[i]['site_name'] + '</div></td></tr>'+
                            '<tr><td>Latitude</td><td><div class=\'lat\'>'+arr[i]['lat']+'</div></td></tr>' +
                            '<tr><td>Longitude</td><td><div class=\'long\'>'+arr[i]['long']+'</div></td></tr>' +
                            '<tr><td>Transport 3G</td><td><div class=\'transport_3g\'>'+arr[i]['transport_3g']+'</div></td></tr>' +
                            '<tr><td>OLT</td><td><div class=\'olt\'>'+arr[i]['olt']+'</div></td></tr>' +
                            '<tr><td>OLT Port</td><td><div class=\'olt_port\'>'+arr[i]['olt_port']+'</div></td></tr>' +
                            '<tr><td>Metro</td><td><div class=\'metro\'>'+arr[i]['metro']+'</div></td></tr>' +
                            '<tr><td>Metro Port</td><td><div class=\'metro_port\'>'+arr[i]['metro_port']+'</div></td></tr>' +
                            '<tr><td>RAN</td><td><div class=\'ran\'>'+arr[i]['ran']+'</div></td></tr>' +
                            '<tr><td>IP ONT</td><td><div class=\'ip_ont\'>'+arr[i]['ip_ont']+'</div></td></tr>' +
                            '<tr><td>OLT Port</td><td><div class=\'tipe_transmisi\'>'+arr[i]['tipe_transmisi']+'</div></td></tr>' +
                            '<tr><td>Warna</td><td><div class=\'warna\'>'+arr[i]['warna']+'</div></td></tr>' +
                            '<tr>' +
                            '<td style\'margin-bottom:4px;\'><button style="width:100%;" class=\'button button-primary button-rounded button-small point_a_set\'>Set As Point A</button></td>' +
                            '<td style=\'margin-top:4px\'><button style="width:100%;" class=\'button button-primary button-rounded button-small point_b_set\'>Set As Point B</button></td>' +
                            '</tr></table>';
                if(arr[i]['warna'] == "Hitam"){
                    icon_ = blackIcon;
                }
                else if(arr[i]['warna'] == "Kuning"){
                    icon_ = yellowIcon;
                }
                else if(arr[i]['warna'] == "Merah"){
                    icon_ = redIcon;
                }
                else if(arr[i]['warna'] == "Biru"){
                    icon_ = blueIcon;
                }               

                L.marker([arr[i]['lat'],arr[i]['long']],{icon: icon_}).bindPopup(html,{maxWidth:"auto"}).openPopup().addTo(sites);   
            }
          }
        });
    });

    $('body').on('click', '.show_odp', function() {
        // alert('berhasil');
        var data = {
            lat     : lat_onclick,
            long    : long_onclick,
            radius  : parseInt($('#radius_odp').val()),
            witel   : $('.witel').val()
        };
        //alert('witel : ' + data['witel']);
        // console.log(data);
        $.ajax({
          type: "POST",
          url: "<?php echo site_url(); ?>home/get_odp",
          data: data,
          success: function(arr) {
            var arr = jQuery.parseJSON (arr);
            // console.log(arr);
            if(arr == 0 || arr.length === 0){
                alert("No ODP Found!");
                return;
            }
            
            for(var i=0;i<arr.length;i++){
                var html =  '<table class=\'table_popup\' style=\'width:300px\' border=\'1\'>' + 
                            '<tr>' +
                            '<td>ODP Name</td><td><div class=\'id\'>' + arr[i]['odp_name'] + '</div></td></tr>'+
                            '<tr><td>Witel</td><td><div class=\'witel\'>'+arr[i]['witel']+'</div></td></tr>' +
                            '<tr><td>Latitude</td><td><div class=\'lat\'>'+arr[i]['lat']+'</div></td></tr>' +
                            '<tr><td>Longitude</td><td><div class=\'long\'>'+arr[i]['long']+'</div></td></tr>' +
                            '<tr><td><button style=\'width:100%;\' class=\'button button-primary button-rounded button-small point_a_set\'>Set As Point A</button></td>' +
                            '<td><button style=\'width:100%;\' class=\'button button-primary button-rounded button-small point_b_set\'>Set As Point B</button></td></tr>' +
                            '</table>';
                L.marker([arr[i]['lat'],arr[i]['long']], {icon: odpIcon}).bindPopup(html,{maxWidth: "auto"}).addTo(odp);
            }
          }
        });
    });


    $('body').on('click', '.show_link', function() {
        var data = {
            lat     : lat_onclick,
            long    : long_onclick,
            radius  : parseInt($('#radius_site').val())
        };

        // console.log(data);
        $.ajax({
          type: "POST",
          url: "<?php echo site_url(); ?>home/get_link",
          data: data,
          success: function(arr) {
            arr = arr.replace('</div>','')
            // console.log(arr)
            var arr = jQuery.parseJSON(arr);
            if(arr.length == 0){
                alert('No Sites Found!');
                return;
            }
            // console.log(arr);

            for(var i=0;i<arr.length;i++){
                var latlongs_line_link = [[arr[i]['site_a_lat'],arr[i]['site_a_long']],[arr[i]['site_b_lat'],arr[i]['site_b_long']]];
                var line_of_sight_link = L.polyline(latlongs_line_link, {color: 'blue'}).bindPopup('id link : ' +  arr[i]['link_id_a_to_b']).addTo(map);
            }
          }
        });
        map.fitBounds(clickCircleSite.getBounds());
    });

    $('body').on('click', '.show_all_link_site', function() {
        // alert('berhasil');
        var data = {
            lat     : lat_onclick,
            long    : long_onclick,
            radius  : parseInt($('#radius_site').val())
        };
        // console.log("apakah ada div? " + arr);

        // console.log(data);
        $.ajax({
          type: "POST",
          url: "<?php echo site_url(); ?>home/get_all_link_site",
          data: data,
          success: function(arr) {
            if(arr == '0'){
                alert('no sites and links found!');
                return;
            }
            // console.log(arr)
            var arr = jQuery.parseJSON(arr);
            // console.log(arr);
          
            for(var i=0;i<arr.length;i++){
                if(arr[i]['type'] == 'link'){
                    var latlongs_line_link = [[arr[i]['site_a_lat'],arr[i]['site_a_long']],[arr[i]['site_b_lat'],arr[i]['site_b_long']]];
                    var line_of_sight_link = L.polyline(latlongs_line_link, {color: 'blue'}).bindPopup('id link : ' +  arr[i]['link_id_a_to_b']).addTo(links);
                }
                else if(arr[i]['type'] == 'site'){
                    var icon_ = null;
                    var html =  '<table class=\'table_popup\' style=\'width:300px\' border=\'1\'>' + 
                            '<tr>' +
                            '<td>Site ID</td><td><div class=\'id\'>' + arr[i]['site_id'] + '</div></td></tr>'+
                            '<td>Site Name</td><td><div class=\'site_name\'>' + arr[i]['site_name'] + '</div></td></tr>'+
                            '<tr><td>Latitude</td><td><div class=\'lat\'>'+arr[i]['lat']+'</div></td></tr>' +
                            '<tr><td>Longitude</td><td><div class=\'long\'>'+arr[i]['long']+'</div></td></tr>' +
                            '<tr><td>Transport 3G</td><td><div class=\'transport_3g\'>'+arr[i]['transport_3g']+'</div></td></tr>' +
                            '<tr><td>OLT</td><td><div class=\'olt\'>'+arr[i]['olt']+'</div></td></tr>' +
                            '<tr><td>OLT Port</td><td><div class=\'olt_port\'>'+arr[i]['olt_port']+'</div></td></tr>' +
                            '<tr><td>Metro</td><td><div class=\'metro\'>'+arr[i]['metro']+'</div></td></tr>' +
                            '<tr><td>Metro Port</td><td><div class=\'metro_port\'>'+arr[i]['metro_port']+'</div></td></tr>' +
                            '<tr><td>RAN</td><td><div class=\'ran\'>'+arr[i]['ran']+'</div></td></tr>' +
                            '<tr><td>IP ONT</td><td><div class=\'ip_ont\'>'+arr[i]['ip_ont']+'</div></td></tr>' +
                            '<tr><td>OLT Port</td><td><div class=\'tipe_transmisi\'>'+arr[i]['tipe_transmisi']+'</div></td></tr>' +
                            '<tr><td>Warna</td><td><div class=\'warna\'>'+arr[i]['warna']+'</div></td></tr>' +
                            '<tr>' +
                            '<td style\'margin-bottom:4px;\'><button style="width:100%;" class=\'button button-primary button-rounded button-small point_a_set\'>Set As Point A</button></td>' +
                            '<td style=\'margin-top:4px\'><button style="width:100%;" class=\'button button-primary button-rounded button-small point_b_set\'>Set As Point B</button></td>' +
                            '</tr></table>';
                if(arr[i]['warna'] == "Hitam"){
                    icon_ = blackIcon;
                }
                else if(arr[i]['warna'] == "Kuning"){
                    icon_ = yellowIcon;
                }
                else if(arr[i]['warna'] == "Merah"){
                    icon_ = redIcon;
                }
                else if(arr[i]['warna'] == "Biru"){
                    icon_ = blueIcon;
                }               

                L.marker([arr[i]['lat'],arr[i]['long']],{icon: icon_}).bindPopup(html,{maxWidth:"auto"}).openPopup().addTo(sites);
                }
            }
            map.fitBounds(clickCircleSite.getBounds());
          }
        });
    });




    function distance(lat1, lon1, lat2, lon2, unit) {
        var radlat1 = Math.PI * lat1/180
        var radlat2 = Math.PI * lat2/180
        var theta = lon1-lon2
        var radtheta = Math.PI * theta/180
        var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
        dist = Math.acos(dist)
        dist = dist * 180/Math.PI
        dist = dist * 60 * 1.1515
        if (unit=="Kilometer") { dist = dist * 1.609344 }
        if (unit=="Meter") { dist = dist * 1609.344 }
        if (unit=="N") { dist = dist * 0.8684 }
        return dist.toFixed(2)
    }


    function clearODP(){
        odp.clearLayers();
    }

    function clearLinks(){
        links.clearLayers();
    }

    function clearSites(){
        sites.clearLayers();
    }


    function clearDistance() {
        
        // clearLayers();
        // map.removeLayer(distance_2_points);
        // map.addLayer(distance_2_points);
        // console.log('---------------------');
        //console.log(distance_2_points._layers);
        for(i in map._layers) {
            // console.log(i);

            if(map._layers[i]._path != undefined) {
                try {
                    map.removeLayer(map._layers[i]);
                }
                catch(e) {
                    console.log("problem with " + e + map._layers[i]);
                }
            }
        }
        // console.log('---------------------');
    }

    $( "body" ).on("click", ".point_a_set", function(){
        latitude_a      = $(this).parents('table').find('div.lat').text();
        longitude_a     = $(this).parents('table').find('div.long').text();
        id_a            = $(this).parents('table').find('div.id').text();
        // console.log(id_a + '  ' + latitude_a + ',' + longitude_a);return;
        $( "#id_a" ).text(id_a);

        if(id_b !== ''){
            distance_2_points.clearLayers();
            var dist = distance(latitude_a,longitude_a,latitude_b,longitude_b, 'Kilometer' );
            $( "#distance" ).text(dist + ' Km');
            latlongs_line = [[latitude_a,longitude_a],[latitude_b,longitude_b]];
            line_of_sight = L.polyline(latlongs_line, {color: 'green'}).bindPopup('Distance : ' + dist + ' Km').addTo(distance_2_points);
            map.fitBounds(line_of_sight.getBounds());
        }
        
    });
    $( "body" ).on("click", ".point_b_set", function(){
        latitude_b      = $(this).parents('table').find('div.lat').text();
        longitude_b     = $(this).parents('table').find('div.long').text();
        id_b            = $(this).parents('table').find('div.id').text();
        $( "#id_b" ).text(id_b);
        if(id_a !== ''){
            distance_2_points.clearLayers();
            var dist = distance(latitude_a,longitude_a,latitude_b,longitude_b, 'Kilometer' );
            $( "#distance" ).text(dist + ' Km');    
            latlongs_line = [[latitude_a,longitude_a],[latitude_b,longitude_b]];
            line_of_sight = L.polyline(latlongs_line, {color: 'green'}).bindPopup('Distance : ' + dist + ' Km').addTo(distance_2_points);
            map.fitBounds(line_of_sight.getBounds());
        }
    });

</script>


        <!-- REAL DEAL -->
        </div> <!-- .content -->
    </div><!-- /#right-panel -->

    <!-- Right Panel -->




