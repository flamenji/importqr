<link href="http://pagination.js.org/dist/2.1.2/pagination.css" rel="stylesheet" type="text/css">

<style type="text/css">
ul, li {
    list-style: none;
}
#wrapper {
    width: 900px;
    margin-bottom: 20px;
}
.data-container {
    margin-top: 20px;
}
.data-container ul {
    padding: 0;
    margin: 0;
}
.data-container li {
    margin-bottom: 5px;
    padding: 5px 10px;
    background: #eee;
    color: #666;
}

.pagination {
    display: inline-block;
}

.pagination a {
    color: black;
    float: left;
    padding: 8px 16px;
    text-decoration: none;
    border: 1px solid #ddd;
}

.pagination a.active {
    background-color: #4CAF50;
    color: white;
    border: 1px solid #4CAF50;
}

.pagination a:hover:not(.active) {background-color: #ddd;}

.pagination a:first-child {
    border-top-left-radius: 5px;
    border-bottom-left-radius: 5px;
}

.pagination a:last-child {
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
}

.pagination li {
  padding : 5px;
}
.icon_action{
  width : 30px;
  height: 30px;
}

.modal {
    display:    none;
    position:   fixed;
    z-index:    1000 !important;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 0, 0, 0, .8 ) 
                url('<?php echo site_url(); ?>images/loading_2.gif') 
                50% 50% 
                no-repeat;
}
body.loading .modal {
    overflow: hidden;   
}
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

.popup {
  width:100%;
  height:100%;
  display:none;
  position:fixed;
  top:0px;
  left:0px;
  background:rgba(0,0,0,0.75);
}
/* Inner */
.popup-inner {
  max-width:700px;
  width:90%;
  padding:40px;
  position:absolute;
  top:50%;
  left:50%;
  -webkit-transform:translate(-50%, -50%);
  transform:translate(-50%, -50%);
  box-shadow:0px 2px 6px rgba(0,0,0,1);
  border-radius:3px;
  background:#fff;
}
/* Close Button */
.popup-close {
  width:30px;
  height:30px;
  padding-top:4px;
  display:inline-block;
  position:absolute;
  top:0px;
  right:0px;
  transition:ease 0.25s all;
  -webkit-transform:translate(50%, -50%);
  transform:translate(50%, -50%);
  border-radius:1000px;
  background:rgba(0,0,0,0.8);
  font-family:Arial, Sans-Serif;
  font-size:20px;
  text-align:center;
  line-height:100%;
  color:#fff;
}
.popup-close:hover {
  -webkit-transform:translate(50%, -50%) rotate(180deg);
  transform:translate(50%, -50%) rotate(180deg);
  background:rgba(0,0,0,1);
  text-decoration:none;
}

.form_edit_div{
  margin-bottom: 4px;
}
</style>
<div>
        <button style="margin-bottom:20px;" class="button button-3d button-primary button-rounded" id="toogle_menu_button">ODP Menu</button>
    </div>
    <div style="margin-bottom: 20px;" id="tabs" style="display:none;" >
          <ul>
            <li><a href="#tabs-1" class="">Search Item</a></li>
            <li><a href="#tabs-2">Input & Delete</a></li>
          </ul>
          <div style="height:150px" id="tabs-1" >
            <form style="display:block;margin-bottom: 10px;" method="get" action="<?php echo site_url(); ?>home/data_odp" id="form_search_item">
            <table class="table table-striped" style="width:40%">
              <tr>
                <td>
                  <input placeholder="Search ODP..." style="padding: 3px;width:70%;" value="<?php echo ($this->input->get('search_item'))?$this->input->get('search_item'):$this->session->userdata('search_item'); ?>" type="text" id="search_item" name="search_item" />
                      <input class='button button-primary button-rounded button-small' type="submit" style="height:100%;padding: 3px;width:20%;margin-bottom: 4px;" value="Search" id="search_submit" /><br />
                </td>
              </tr>
            </table>
            </form>
          </div>
          <div style="height:150px" id="tabs-2">
            <button class='button button-glow button-rounded button-raised button-primary' style="height:100%;text-align:left;width:200px;display: block;float:left;margin-right: 20px;" id="input_bulk_odp"><i style='padding-left: 4px;' class="fa fa-mail-reply">&nbsp&nbsp Input Bulk ODP</i></button>
            <button class='button button-glow button-rounded button-caution' style="height:100%;text-align:left;width:200px;display: block;" id="delete_all_odp"><i style='padding-left: 4px;float:left;' class="fa fa-trash-o">&nbsp&nbsp Delete All ODP</i></button>
          </div>
    </div>

<div class="modal"><!-- Place at bottom of page --></div>

<div id="wrapper">
  <a href="<?php echo site_url(); ?>home/export_data_odp" >
    <div>
      <img src="<?php echo site_url(); ?>images/excel.png" style="width: 30px"/> Export Data ODP To Excel
    </div>
  </a>
    <section>
        <div class="data-container"></div>
        <div id="pagination-demo2"></div>
    </section>
</div>

<div class="popup" data-popup="popup-1">
  <div class="popup-inner card">
  <div class='card-header'>
    <h2>Edit Data ODP</h2><br />
  </div>
  <div class='edit_odp_form card-body card-block'>
    <div class="form_edit_div form-group">
      <div style="font-weight: bold" id='last_witel'></div>
      <div><input class="witel_edit form-control" name="witel_edit" id="witel_edit"/></div>
    </div>
    <div class="form_edit_div">
      <div style="font-weight: bold" id="last_sto"></div>
      <div><input class="sto_edit form-control" name="sto_edit" id="sto_edit" /></div>
    </div>
    <div class="form_edit_div">
      <div style="font-weight: bold" id="last_odp_name"></div>
      <div><input class="odp_name_edit form-control" name="odp_name_edit" id="odp_name_edit" /></div>
    </div>
    <div class="form_edit_div">
      <div style="font-weight: bold" id="last_latitude"></div>
      <div><input class="latitude_edit form-control" name="latitude_edit" id="latitude_edit" /></div>
    </div>
    <div class="form_edit_div">
      <div style="font-weight: bold" id="last_longitude"></div>
      <div><input class="longitude_edit form-control" name="longitude_edit" id="longitude_edit" /></div>
    </div>
    <div class="form_edit_div">
      <button id="update_odp_button" class='button button-primary button-rounded button-small'>Update</button>
      <a data-popup-close="popup-1" href="#"><button class='button button-primary button-rounded button-small'>Cancel</button></a>
    </div>  
  </div>
  <a class="popup-close" data-popup-close="popup-1" href="#">x</a>
  </div>
</div>

<div class="popup" data-popup="popup-2">
  <div class="popup-inner">
    <h2>Are you sure want to delete this ODP?</h2>
    <p id="odp_to_delete"></p>
    <button class='button button-primary button-rounded button-small' id="delete_odp_button">Delete</button>
    <a data-popup-close="popup-2" href="#"><button class='button button-primary button-rounded button-small' id="no_to_delete">Cancel</button></a>
    <a class="popup-close" data-popup-close="popup-2" href="#">x</a>
  </div>
</div>

<style>
  #contextMenu {
  position: absolute;
  display: none;
}

.wrap{
  width:90%;
  display:block;
  margin:0 auto;
}

table{border:1px solid rgba(221, 221, 221, 1);}

tr:nth-child(even) {background: #F5F5F5}

tr{
  position:relative;
}

tr:hover{
  background:#c9e8f7;
  position:relative;
}

.context-menu-item.icon-delete {
    background-image: url("<?php echo site_url(); ?>images/delete.png");
}
.context-menu-item.icon-edit {
    background-image: url("<?php echo site_url(); ?>images/edit.png");
}

</style>

<div id="contextMenu" class="dropdown clearfix">
    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu" style="display:block;position:static;margin-bottom:5px;">
      <li><a tabindex="-1" href="#">Edit</a>
      </li>
      <li><a tabindex="-1" href="#">Delete</a>
      </li>
    </ul>
</div>

<script src="<?php echo site_url(); ?>assets/js/pagination.js"></script>
<script src="<?php echo site_url(); ?>assets/js/jquery.contextMenu.js" type="text/javascript"></script>
<link href="<?php echo site_url(); ?>assets/css/jquery.contextMenu.css" rel="stylesheet" type="text/css" />

<script>
  var tr_class = '';
  var old_odp_name = '';
  var targeted_popup_class;
  $(document).contextMenu({
  delegate : 'table tr.menu',
  selector : 'table tr.menu',
  callback: function(key, options) {
                var m = "clicked: " + key + "\nselected item : " + this.attr('class');
                if(key=='edit'){
                  targeted_popup_class = 'popup-1';
                  tr_class = this.attr('class').toString().split(' ')[0];
                  // console.log(tr_class);
                  old_odp_name = $("."+tr_class).find('.odp_name').text();
                  // console.log(old_odp_name);

                  //console.log($(this).parents('tr').children('.witel').text());
                  $('#last_witel').text('Witel [' + $("."+tr_class).  children('.witel').text() + ']');
                  $('#last_sto').text('STO [' + $("."+tr_class).children('.sto').text() + ']');
                  $('#last_odp_name').text('ODP Name [' + $("."+tr_class).children('.odp_name').text() + ']');
                  $('#last_latitude').text('Latitude [' + $("."+tr_class).children('.latitude').text() + ']');
                  $('#last_longitude').text('Longitude [' + $("."+tr_class).children('.longitude').text() + ']');

                  $('.witel_edit').val($("."+tr_class).children('.witel').text());
                  $('.sto_edit').val($("."+tr_class).children('.sto').text());
                  $('.odp_name_edit').val($("."+tr_class).children('.odp_name').text());
                  $('.latitude_edit').val($("."+tr_class).children('.latitude').text());
                  $('.longitude_edit').val($("."+tr_class).children('.longitude').text());
                  $('[data-popup="' + targeted_popup_class + '"]').fadeIn(350);
                }
              else if(key=='delete'){
                  targeted_popup_class = 'popup-2';
                  tr_class = this.attr('class').toString().split(' ')[0];
                  old_odp_name = $("."+tr_class).find('.odp_name').text();
                  $('#odp_to_delete').text($("."+tr_class).children('.odp_name').text());
                  $('[data-popup="' + targeted_popup_class + '"]').fadeIn(350);
              }
                // console.log(m);
                //window.console && console.log(m) || alert(m); 
            },
            items: {
                "edit": {name: "Edit"},
                "delete": {name: "Delete"},
                "sep1": "---------",
                "quit": {name: "Cancel"}
            }
  });

  $('#tabs').css('display', 'none');
    $( function() {
        $( "#tabs" ).tabs();
    });

    $('#toogle_menu_button').click(function(){
        if($("#tabs").css('display') == 'none'){
            $("#tabs").slideDown();
        }
        else{
            $("#tabs").slideUp();
        }
    });

    $body = $("body");

    $(document).on({
        ajaxStart: function() { $body.addClass("loading");    },
        ajaxStop: function() { $body.removeClass("loading"); }    
    });

  $('#update_odp_button').on('click', function(event){
      var data = {
                  old_odp_name  : old_odp_name,
                  witel         : $('#witel_edit').val(),
                  sto           : $('#sto_edit').val(),
                  odp_name      : $('#odp_name_edit').val(),
                  latitude      : $('#latitude_edit').val(),
                  longitude     : $('#longitude_edit').val()
        };
      // console.log(data);
      $.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>home/update_odp_ajax",
            dataType : 'json',
            data: data,
            success: function(arr) {
                // console.log('response : ' + arr);
                if(arr['code'] == 0){
                  alert("Update is successfull!");
                  $('.list_odp').find('tr.'+tr_class).find('td.witel').text(data['witel']);
                  $('.list_odp').find('tr.'+tr_class).find('td.sto').text(data['sto']);
                  $('.list_odp').find('tr.'+tr_class).find('td.odp_name').text(data['odp_name']);
                  $('.list_odp').find('tr.'+tr_class).find('td.latitude').text(data['latitude']);
                  $('.list_odp').find('tr.'+tr_class).find('td.longitude').text(data['longitude']);
                  $('[data-popup="' + targeted_popup_class + '"]').fadeOut(350);
                } 
                else if(arr['code'] == 1062){
                  alert("Data duplikasi untuk \"ODP Name\" : " + data['odp_name']);
                  return;
                }
                else {
                  alert("Terjadi Kesalahan Sistem");
                  return;
                }
          } 
        })
  });

  $('#delete_odp_button').on('click', function(event){
      var data = {
            odp_name      : $('#odp_to_delete').text(),

        };
      // console.log(data);
      $.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>home/delete_odp_ajax",
            dataType : "json",
            data: data,
            success: function(arr) {
                console.log(arr);
                if(arr['code'] == 0){
                  alert("Delete is successfull!");
                  $('.list_odp').find('tr.'+tr_class).find('td.action').text('DELETED');
                  $('.list_odp').find('tr.'+tr_class).css('background-color', 'red').css('color', 'white');
                  $('[data-popup="' + targeted_popup_class + '"]').fadeOut(350);
                }
                else{
                  alert("Delete is Error\nCode error : " + arr['code']);
                }
          } 
        })
  });

  $('#form_search_item').submit(function(event){
      event.preventDefault();
      var totalNumber;
      var string = $('#search_item').val();
      var field = 'odp_name';
      string = string.trim().toUpperCase();
      $('#search_item').val(string);
      
      var data = {
        'type'  : 'odp_name',
        'like'  : string,
        'ajax'  : true
      }
      $.ajax({
        type: "POST", 
        url: '<?php echo site_url(); ?>home/count_item',
        data : data,
        dataType: "text", 
        async: false,
        success: function(data){
            console.log('response : ' + data);
            totalNumber = data; //or something similar
            console.log('total number : ' + totalNumber);
        }
      });
      set_pagination(field,string, totalNumber, 10);
  });


function set_pagination(field, like,totalNumber, pageSize){
  if(!field){
    field = '';
  }
  if(!like){
    like = '';
  }
  if(!totalNumber){
    totalNumber = <?php echo $total_rows; ?>;
  }
  if(!pageSize){
    pageSize  = <?php echo $per_page; ?>;
  }
  var container = $('#pagination-demo2');
    container.addHook('beforeInit', function () {
        window.console && console.log('beforeInit...');
    });
    container.pagination({
      dataSource: '<?php echo site_url(); ?>home/get_ajax_odp_pagination?like=' + like + '&field='+field,
      locator: 'items',
      totalNumber: totalNumber,
      pageSize: pageSize,
      className: 'paginationjs-theme-blue paginationjs-big',
      ajax: {
        beforeSend: function() {
          container.prev().html('.........<h2>Loading data from Data ODP.</h2>........');
        }
      },
      callback: function(response, pagination) {
        window.console && console.log(22, response, pagination);
        var pageNumber = pagination.pageNumber;
        var pageSize = pagination.pageSize;
        
        var dataHtml = '<table class=\'list_odp table table-striped\'>';
        dataHtml += '<th>No</th><th>WITEL</th><th>STO</th><th>ODP Name</th><th>Latitude</th><th>Longitude</th><th>Action</th>';
        $.each(response, function (index, item) {
          dataHtml += '<tr class=\'tr_'+ index +' menu\' >';
          dataHtml += '<td>' + ((index + 1) + ((pageNumber - 1) * pageSize ))+ '</td>';
          dataHtml += '<td class=\'witel\'>' + item.witel + '</td>';
          dataHtml += '<td class=\'sto\'>' + item.sto + '</td>';
          dataHtml += '<td class=\'odp_name\'>' + item.odp_name + '</td>';
          dataHtml += '<td class=\'latitude\'>' + item.latitude + '</td>';
          dataHtml += '<td class=\'longitude\'>' + item.longitude + '</td>';
          dataHtml += '<td class=\'action\'><a data-popup-open=\'popup-1\' class=\'edit\' href=\'#\'><img alt=\'edit\' class=\'icon_action\' src=\'<?php echo site_url(); ?>images/edit.png\' /></a><a data-popup-open=\'popup-2\' class=\'delete\' href=\'#\'><img alt=\'delete\' class=\'icon_action\' src=\'<?php echo site_url(); ?>images/delete.png\' /></a></td>';
          dataHtml += '</tr>';
        });
        dataHtml += '</table>';
        container.prev().html(dataHtml);
        //container.prev().html(dataHtml);
      }
    })
}


$(function() {
    set_pagination();
})


$(function() {
      $(document).on('click','[data-popup-open]', function(e)  {
        targeted_popup_class = jQuery(this).attr('data-popup-open');
        //$('.popup-inner').css('display', 'block');
        if(targeted_popup_class == 'popup-1'){
            tr_class = $(this).parents('tr').attr('class');
            old_odp_name = $(this).parents('tr').find('.odp_name').text();
            // console.log(old_odp_name);

          //console.log($(this).parents('tr').children('.witel').text());
          $('#last_witel').text('Witel [' + $(this).parents('tr').children('.witel').text() + ']');
          $('#last_sto').text('STO [' + $(this).parents('tr').children('.sto').text() + ']');
          $('#last_odp_name').text('ODP Name [' + $(this).parents('tr').children('.odp_name').text() + ']');
          $('#last_latitude').text('Latitude [' + $(this).parents('tr').children('.latitude').text() + ']');
          $('#last_longitude').text('Longitude [' + $(this).parents('tr').children('.longitude').text() + ']');

          $('.witel_edit').val($(this).parents('tr').children('.witel').text());
          $('.sto_edit').val($(this).parents('tr').children('.sto').text());
          $('.odp_name_edit').val($(this).parents('tr').children('.odp_name').text());
          $('.latitude_edit').val($(this).parents('tr').children('.latitude').text());
          $('.longitude_edit').val($(this).parents('tr').children('.longitude').text());
        }
        else if(targeted_popup_class == 'popup-2'){
          tr_class = $(this).parents('tr').attr('class');
          old_odp_name = $(this).parents('tr').find('.odp_name').text();
          $('#odp_to_delete').text($(this).parents('tr').children('.odp_name').text());
        }

        $('[data-popup="' + targeted_popup_class + '"]').fadeIn(350);       
        e.preventDefault();
      });

    //----- CLOSE
      $(document).on('click','[data-popup-close]', function(e)  {
        $('.edit_class').css('display', 'none');
        var targeted_popup_class = jQuery(this).attr('data-popup-close');
      $('[data-popup="' + targeted_popup_class + '"]').fadeOut(350);
        e.preventDefault();
      });
  });
</script>