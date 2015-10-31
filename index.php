<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge"">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>easyMonitor</title>
  <link href="./css/bootstrap.min.css" rel="stylesheet">

  <!-- script -->
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
  <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
  <script src="./js/bootstrap.min.js"></script>
  <!-- script -->

  <!-- script -->
  <script>
  
  // alert ------------------------------------------------------------------- 

  // get Alert Json
  function getAlertJson(offset, count){
    $.getJSON("./action.php"
    ,{ "act":"alertListGetJson", "offset":offset, "count":count }
    ,function(data, status){
      if(status==0){
        alert("Cannot get json for alert list.");
      }
      else {
        for ( key in data ){
          if( ! alert_json[key] ){
            alert_json[key] = data[key];
            appendAlertList(key);
          }
        }
      }
    });
  }
  
  // append Alert list
  function appendAlertList(data){
  
    // append alert list to table
    $("table#alertTable").append(
        '<tr id="' + alert_json[data]['alert_id'] + '">'
       +"<td>" + alert_json[data]['occurDate']  + "</td>"
       +"<td>" + alert_json[data]['hostname']   + "</td>"
       +"<td >" 
        + '<span class="label label-' + alert_json[data]['alertLevel'] + ' label-block">'
          + alert_json[data]['alertLevel']
        + '</span>'
       + "</td>"
       +"<td>" + alert_json[data]['alertContent']+"</td>"
       +"<td>"
        +  '<button id="alertChecked_' + alert_json[data]['alert_id'] + '" type="button" class="btn btn-default btn-xs" onclick="alertCheckBtnAction(' + alert_json[data]['alert_id'] +  ')">' + alertChecked[alert_json[data]['checked']] + '</button>'
       +"</td>"
       +"</tr>"
    );

    // hide check
    alertCheckedFlag = $("#alertCheckBox:checked").val();
    if( alert_json[data]['checked'] != 0 && alertCheckedFlag != 'on'){
      $("table#alertTable > tbody > tr#" + alert_json[data]['alert_id']).hide();
    }
  }

  // hide Alert list
  function hideAlertList(data){
    $("table#alertTable > tbody > tr#" + data).animate({height:'hide',opacity:'hide'}, 'slow');
  }

  // alert hide/show button action
  function alertCheckBtnAction(al_id) {

    check = Math.abs(alert_json[al_id]['checked'] - 1);
    alert_json[al_id]['checked'] = check;
    $('#alertChecked_' + al_id).text(alertChecked[check]);
    
    $.get("./action.php"
    ,{ "act":"alertChecked", "alert_id":al_id, "checked":check }
    ,function(data, status){
      if(!status){ alert("Cannot change checked."); }
      if(check === 1 && alertCheckedFlag != 'on' ){
        hideAlertList(al_id);
      }
    });
  }

  // check Alert checkbox
  function checkedAlerCeckbox(){
    alertCheckedFlag = $("#alertCheckBox:checked").val();
    if( alertCheckedFlag == 'on' ){
      $("table#alertTable").find("tr:gt(0)").show();
    }
    else {
      for ( key in alert_json ){
        if(alert_json[key]['checked'] == 1){
          hideAlertList(key);
        }
      }
    }
  }
  // alert ------------------------------------------------------------------- 
  
  
  // host ------------------------------------------------------------------- 

  // get Host Json
  function getHostJson(offset, count){
    $.getJSON("./action.php"
    ,{ "act":"hostListGetJson", "offset":offset, "count":count }
    ,function(data, status){
      if(status==0){
        alert("Cannot get json for host list.");
      }
      else {
        for ( key in data ){
          if( ! host_json[key] ){
            host_json[key] = data[key];
            appendHostList(key);
          }
        }
      }
    });
  }

  // append hostList to hostTable
  function appendHostList(data){
  
    // append host list to table
    $("table#hostTable").append(
      '<tr id="' + host_json[data]['host_id'] + '">'
     +"<td>"
       + '<input type="checkbox" id="hostdeletecheck"'
       + ' value="' + host_json[data]['host_id'] + '"/></td>'
     +"<td>" + host_json[data]['hostname']  + "</td>"
     +"<td>" + host_json[data]['ipaddress'] + "</td>"
     +"<td>" + 'monitor' + "</td>"
     +"</tr>"
    );
  }
  
  // create host
  function hostCreate(){
    var hostname  = $("#createHostname").val();
    var ipaddress = $("#createIpaddress").val();

    $.getJSON("./action.php"
    ,{ "act":"hostCreate", "hostname":hostname, "ipaddress":ipaddress }
    ,function(data, status){
      tg = $("#hostCreateMessage");
      if(data == 0){
        tg.animate({height:'show',opacity:'show'}, {duration: 1000});
        tg.html("Already exist!");
        tg.css("color","red");
        tg.animate({height:'hide',opacity:'hide'}, {duration: 2000});
      }
      else {
        host_json[data] = {'host_id':data, 'hostname':hostname, 'ipaddress':ipaddress};
        appendHostList(data);
        tg.animate({height:'show',opacity:'show'}, {duration: 1000});
        tg.html("Created!");
        tg.css("color","blue");
        tg.animate({height:'hide',opacity:'hide'}, {duration: 2000});
      }
    });
  }
  // create host
  
  // delete host checked 
  function hostDeleteCheck(){
    tg = $("#hostDeleteCheckedDisp");
    tg.text("");
    $("#hostdeletecheck:checked").each(function(){
      host_id = $(this).val();
      tg.append('<li id="'+host_id+'">'+host_json[host_id]['hostname']+'</li>');
    });
  }
  // delete host checked 
  
  // delete host
  function hostDelete(){
    $("ul#hostDeleteCheckedDisp li").each(function(i, li){
      host_id = li.id;
      $.getJSON("./action.php"
        ,{ "act":"hostDelete", "host_id":host_id }
        ,function(data, status){
          delete host_json[data];
          removeHostTable(data);
        }
      );
    });
  }

  function removeHostTable(host_id){
    $("table#hostTable > tbody > tr[id=" + host_id + "]").remove();
  }
  // host ------------------------------------------------------------------- 
  
  // monitor ---------------------------------------------------------------- 

  // display monitorList 
  function monitorList() {
    var offset = 0;
    var count = 100;
  
    // clear table
    $("#monitorTable").find("tr:gt(0)").remove();
  
    // Get host list json from db via action.php
    $.getJSON("./action.php"
    ,{ "act":"monitorListGetJson", "offset":offset, "count":count }
    ,function(monitor_json, status){
      if(status==0){ alert("Cannot get json for monitor list."); }
  
      // fetch alert list from alert_json
      for( data in monitor_json){
  
        // append host list to table
        $("#monitorTable").append(
          "<tr>"
         +"<td>"
           + '<input type="checkbox" id="monitordeletecheck" value="'
             + host_json[data]['monitor_id']
           + '"/></td>'
         +"<td>" + host_json[data]['monitorName']  + "</td>"
         +"<td>" + host_json[data]['MonitorType']   + "</td>"
         +"<td>" + host_json[data]['hostname']   + "</td>"
         +"<td>" + host_json[data]['argument']   + "</td>"
         +"<td>" + host_json[data]['timeout']   + "</td>"
         +"<td>" + 'monitor' + "</td>"
         +"</tr>"
        );
      }
    });
  }

  // monitor ---------------------------------------------------------------- 
  
  // sleep function
  function Sleep( T ){ 
    var d1 = new Date().getTime(); 
    var d2 = new Date().getTime(); 
    while( d2 < d1+1000*T ){
      d2=new Date().getTime(); 
    } 
    return; 
  } 
  </script>
  <!-- script -->

  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body style="padding-top: 70px;padding-left: 20px;padding-right: 20px;">

<!-- Fixed navbar -->
<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">easyMonitor</a>
    </div>

    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li class="active">
          <a href="#tab1" data-toggle="tab">
            <span class="glyphicon glyphicon-alert" aria-hidden="true"></span>
             Alert
          </a>
        </li>
        <li>
          <a href="#tab2" data-toggle="tab">
            <span class="glyphicon glyphicon-hdd" aria-hidden="true"></span>
             Host
          </a>
        </li>
        <li>
          <a href="#tab3" data-toggle="tab">
            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
             Monitor
          </a>
        </li>
        <li>
          <a href="#tab4" data-toggle="tab">
            <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
             User
          </a>
        </li>
      </ul>
    </div> <!--/.nav-collapse -->
  </div> <!-- /container-fluid --> 
</nav>
<!-- Fixed navbar -->


<!-- content -->
<div class="tab-content">

  <!-- alert page ----------------------------------------------------------->
  <div class="tab-pane active" id="tab1">

    <!-- alertList -->
    <div class="table-responsive">
      <table id="alertTable" class="table table-striped table-condensed">
        <tr>
          <th>OccurDate</th>
          <th>Hostname</th>
          <th>Level</th>
          <th>Error</th>
          <th>Checked
            <input type="checkbox" id="alertCheckBox" onchange="checkedAlerCeckbox()"/>
          </th>
        </tr>
      </table>
    </div> 
    <!-- alertList -->

  </div>
  <!-- alert page ----------------------------------------------------------->

  <!-- host page --------------------------------------------------------- -->
  <div class="tab-pane" id="tab2">

    <!-- modal create ------------------------- -->
      <!-- modal create button -->
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#hostCreateModal">Create</button>
      <!-- modal create button -->

      <!-- modal create window-->
      <div class="modal fade" id="hostCreateModal">
        <div class="modal-dialog">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Create Host    <span id="hostCreateMessage"></span></h4>
            </div>

            <div class="modal-body">
             <form class="form-inline">
              <div class="form-group">
                <label for="createHostname" class="sr-only">HOSTNAME</label>
                <input type="text" class="form-control" id="createHostname" placeholder="HOSTNAME"/>
              </div>
              <div class="form-group">
                <label for="createIpadress" class="sr-only">IPADDRESS</label>
                <input type="text" class="form-control" id="createIpaddress" placeholder="IPADDRESS"/>
              </div>

              <button type="button" class="btn btn-primary" onclick="hostCreate()">CREATE</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>
             </form>
            </div>

          </div> <!-- modal-content -->
        </div> <!-- moda-daialog -->
      </div> <!-- modal fade -->
      <!-- modal create window-->
    <!-- modal create -------------------------     -->

    <!-- modal delete -------------------------     -->
      <!-- modal delete button -->
      <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#hostDeleteModal" onclick="hostDeleteCheck()">Delete</button><br>

      <!-- modal delete window -->
      <div class="modal fade" id="hostDeleteModal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Delete host?</h4>
            </div>
            <div class="modal-body">
              <ul id="hostDeleteCheckedDisp"></ul>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" onclick="hostDelete()" data-dismiss="modal">DELETE</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>
            </div>
          </div>
        </div>
      </div>
      <!-- modal delete window -->
    <!-- modal delete -------------------------     -->

    <!-- hostList -----------------------------     -->
    <div class="table-responsive">
      <table id="hostTable" class="table table-striped table-condensed">
        <tr>
          <th>check</th>
          <th>hostname</th>
          <th>ipaddress</th>
          <th>monitor</th>
        </tr>
      </table>
    </div> 
    <!-- hostList -----------------------------     -->

  </div>
  <!-- host page --------------------------------------------------------- -->

  <!-- monitor page -->
  <div class="tab-pane" id="tab3">
    Monitor<br>
    Monitor<br>
  </div>
  <!-- monitor page -->

  <!-- user page -->
  <div class="tab-pane" id="tab4">
    User<br>
    User<br>
  </div>
  <!-- user page -->
</div>
<!-- content -->

<!-- script -->
<script>

  var alertCheckedFlag=0;
  var alertCheckedHideCount=0;
  var alertChecked=['hide', 'show'];
  var alert_json = {};
  var host_json = {};
  getAlertJson(0, 100);
  getHostJson(0, 100);

  /* cel change test 
  $('table#alertTable > tbody > tr > td').on('click',function(){
    y = this.parentNode.rowIndex; 
    x = this.cellIndex; 
    selector = "table tr:eq(" + y + ") td:eq(" + x + ")"
    $(selector).text("change");
  });
  */

</script>
<!-- script -->

</body>
</html>
