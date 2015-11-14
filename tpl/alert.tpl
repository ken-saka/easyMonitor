<script>
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
     +"<td>"
       +  '<button id="alertChecked_' + alert_json[data]['alert_id'] + '" type="button" class="btn btn-default btn-xs" onclick="alertCheckBtnAction(' + alert_json[data]['alert_id'] +  ')">' + alertChecked[alert_json[data]['checked']] + '</button>'
       + "</td><td>"
       + alert_json[data]['occurDate'].replace(" ", "<br>")
       + "</td>"
     +"<td>" + alert_json[data]['hostname']   + "</td>"
     +"<td >" 
      + '<span class="label label-' + alert_json[data]['alertLevel'] + ' label-block">'
        + alert_json[data]['alertLevel']
      + '</span>'
     +"</td >" 
     +"<td >" 
      + alert_json[data]['alertContent']+"</td>"
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
</script>

<!-- alertList -->
<div class="table-responsive">
  <table id="alertTable" class="table table-striped table-condensed">
    <tr>
      <th colspan="2" class="text-center">Date
        <input type="checkbox" id="alertCheckBox" onchange="checkedAlerCeckbox()"/>
      </th>
      <th>Host</th>
      <th colspan="2">Message</th>
    </tr>
  </table>
</div> 
<!-- alertList -->
