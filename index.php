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


var alertCheckedFlag=0;
var alertCheckedHideCount=0;
var alertChecked=['hide', 'show'];

// display alertList 
function alertList(){
  var alertCount=0;
  var offset = 0;
  var count = 100;

  // clear table
  $("#alertTable").find("tr:gt(0)").remove();

  // Get alert list json from db via action.php
  $.getJSON("./action.php"
  ,{ "act":"alertListGetJson", "offset":offset, "count":count }
  ,function(alert_json, status){
    if(status==0){ alert("Cannot get json for alert list."); }

    // fetch alert list from alert_json
    for(var data in alert_json){

      // hide checked
      alertCheckedFlag = $("#alertCheckBox:checked").val();
      if( alert_json[data]['checked'] != 0 
      && alertCheckedFlag != 'on'){ continue; }
      // hide checked

      // NaviBar badge counter
      if(alert_json[data]['checked'] == 0){
        alertCount++;
      }
      // NaviBar badge counter

      // append alert list to table
      $("#alertTable").append(
        "<tr>"
       +"<td>" + alert_json[data]['occurDate']  + "</td>"
       +"<td>" + alert_json[data]['hostname']   + "</td>"
       +"<td >" 
        + '<span class="label label-' + alert_json[data]['alertLevel'] + ' label-block">'
          + alert_json[data]['alertLevel']
        + '</span>'
       + "</td>"
       +"<td>" + alert_json[data]['alertContent']+"</td>"
       +"<td>"
        +  '<button id="alertChecked" type="button" class="btn btn-default btn-xs" onclick="alertCheckedAction(' + alert_json[data]['alert_id'] + ',' + alert_json[data]['checked'] + ')">' + alertChecked[alert_json[data]['checked']] + '</button>'
       +"</td>"
       +"</tr>"
      );
      // append alert list to table
    }
    $("#alertCount").text(alertCount);
  });
}
// display alertList 

// alert checked button action
function alertCheckedAction(alert_id, checked) {
  check = Math.abs( checked - 1 );
  $.get("./action.php"
  ,{ "act":"alertChecked", "alert_id":alert_id, "checked":check }
  ,function(data, status){
    if(!status){ alert("Cannot change checked."); }
    alertList();
  });
}
// alert checked button action


// display hostList 
function hostList(){
  var offset = 0;
  var count = 100;

  // clear table
  $("#hostTable").find("tr:gt(0)").remove();

  // Get host list json from db via action.php
  $.getJSON("./action.php"
  ,{ "act":"hostListGetJson", "offset":offset, "count":count }
  ,function(host_json, status){
    if(status==0){ alert("Cannot get json for host list."); }

    // fetch alert list from alert_json
    for(var data in host_json){

      // append host list to table
      $("#hostTable").append(
        "<tr>"
       +"<td>"
         + '<input type="checkbox" id="hostdeletecheck" value="'
           + host_json[data]['host_id']
         + '"/></td>'
       +"<td>" + host_json[data]['hostname']  + "</td>"
       +"<td>" + host_json[data]['ipaddress']   + "</td>"
       +"<td>" + 'monitor' + "</td>"
       +"</tr>"
      );
      // append host list to table
    }
  });
}
// display hostList 

// create host
function hostCreate(){
  var hostname  = $("#createHostname").val();
  var ipaddress = $("#createIpaddress").val();
  $.getJSON("./action.php"
  ,{ "act":"hostCreate", "hostname":hostname, "ipaddress":ipaddress }
  ,function(res, status){
    if(status==0){ alert("Cannot create a host."); }
    hostList();
  });
}
// create host

// display monitorList 
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
    for(var data in monitor_json){

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
      // append host list to table
    }
  });
// display monitorList 

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
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">easy Monitor</a>
    </div>

    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li class="active">
          <a href="#tab1" data-toggle="tab">
            <span class="glyphicon glyphicon-alert" aria-hidden="true"></span>
             Alert
            <span id="alertCount" class="badge"></span>
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
  </div> <!-- /container --> 
</nav>
<!-- Fixed navbar -->


<!-- content -->
<div class="tab-content">

  <!-- alert page -->
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
            <input type="checkbox" id="alertCheckBox" onchange="alertList()"/>
          </th>
        </tr>
      </table>
    </div> 
    <script> alertList(); </script>
    <!-- alertList -->

  </div>
  <!-- alert page -->

  <!-- host page -->
  <div class="tab-pane" id="tab2">

    <!-- modal create button -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#hostCreateModal">Create</button>

    <div class="modal fade" id="hostCreateModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Create Host</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="hostname">hostname</label>
              <input type="text" class="form-control" id="createHostname"/>
            </div>
            <div class="ipaddress">
              <label for="ipaddress">ipaddress</label>
              <input type="text" class="form-control" id="createIpaddress"/>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="hostCreate()">CREATE</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
          </div>
        </div>
      </div>
    </div>
    <!-- modal create button -->

    <!-- modal delete button -->
    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#hostDeleteModal">Delete</button><br>
    <div class="modal fade" id="hostDeleteModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Delete host?</h4>
          </div>
          <div class="modal-body">
            <script></script>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" onclick="hostDelete()">DELETE</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
          </div>
        </div>
      </div>
    </div>
    <!-- modal delete button -->

    <!-- hostList -->
    <div class="table-responsive">
      <table id="hostTable" class="table table-striped table-condensed">
        <tr>
          <th>check</th>
          <th>hostname</th>
          <th>ipaddress</th>
          <th>monitor</th>
          </th>
        </tr>
      </table>
    </div> 
    <script> hostList(); </script>
    <!-- hostList -->

  </div>
  <!-- host page -->

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

/* cel change test 
$(function(){
  $('[id=alertTable] > tbody > tr > td').on('click',function(){
    y = this.parentNode.rowIndex; 
    x = this.cellIndex; 
    selector = "table tr:eq(" + y + ") td:eq(" + x + ")"
    $(selector).text("change");
  });
});
*/

</script>
<!-- script -->

</body>
</html>
