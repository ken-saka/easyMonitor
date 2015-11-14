<script>

// get Monitor Json

function getMonitorJson(offset, count){
offset = 0;
count = 100;

  $.getJSON("./action.php"
  ,{ "act":"monitorListGetJson", "offset":offset, "count":count }
  ,function(data, status){
    if(status==0){
      alert("Cannot get json for host list.");
    }
    else {
      for ( key in data ){
        if( ! monitor_json[key] ){
          monitor_json[key] = data[key];
          appendMonitorList(key);
        }
      }
    }
  });
}

// display monitorList 
function appendMonitorList(data) {

  // append monitor list to table
  $("#monitorTable").append(
    '<tr id="' + monitor_json[data]['monitor_id'] + '">'
     +"<td>"
       + '<input type="checkbox" id="monitordeletecheck" value="'
         + monitor_json[data]['monitor_id'] + '"/>'
         +"&nbsp"
             + monitor_json[data]['monitorname'] + "</td>"
     +"<td>" + monitor_json[data]['monitortype'] + "</td>"
     +"<td>" + monitor_json[data]['argument']    + "</td>"
     +"<td>" + monitor_json[data]['timeout']     + "</td>"
     +"<td>" + monitor_json[data]['retry']       + "</td>"
   +"</tr>"
  );
}

// create monitor
function monitorCreate(){
  var monitorname     = $("#createMonitorName").val();
  var monitortype     = $("#createMonitorType").val();
  var monitorargument = $("#createMonitorArgument").val();
  var monitortimeout  = $("#createMonitorTimeout").val();
  var monitorretry    = $("#createMonitorRetry").val();

  $.getJSON("./action.php"
  ,{ "act":"monitorCreate", "monitorname":monitorname, "monitorargument":monitorargument, "monitortimeout":monitortimeout, "monitortype":monitortype, "monitorretry":monitorretry }
  ,function(data, status){
    tg = $("#monitorCreateMessage");
    if(data == 0){
      tg.animate({height:'show',opacity:'show'}, {duration: 1000});
      tg.html("Already exist!");
      tg.css("color","red");
      tg.animate({height:'hide',opacity:'hide'}, {duration: 2000});
    }
    else {
      monitor_json[data] = {'monitor_id':data, 'monitorname':monitorname, 'monitortype':monitortype, 'argument':monitorargument, 'timeout':monitortimeout, "retry":monitorretry };
      appendMonitorList(data);
      tg.animate({height:'show',opacity:'show'}, {duration: 1000});
      tg.html("Created!");
      tg.css("color","blue");
      tg.animate({height:'hide',opacity:'hide'}, {duration: 2000});
    }
  });
}

// delete monitor checked
function monitorDeleteCheck(){
  tg = $("#monitorDeleteCheckedDisp");
  tg.text("");
  $("#monitordeletecheck:checked").each(function(){
    monitor_id = $(this).val();
    tg.append('<li id="'+monitor_id+'">'+monitor_json[monitor_id]['monitorname']+'</li>');
  });
}

// delete monitor
function monitorDelete(){
  $("ul#monitorDeleteCheckedDisp li").each(function(i, li){
    monitor_id = li.id;
    $.getJSON("./action.php"
      ,{ "act":"monitorDelete", "monitor_id":monitor_id }
      ,function(data, status){
        delete monitor_json[data];
        removeMonitorTable(data);
      }
    );
  });
}

// remove monitor from table
function removeMonitorTable(monitor_id){
  $("table#monitorTable > tbody > tr[id=" + monitor_id + "]").remove();
}
</script>


  <!-- modal create ------------------------- -->
  <!-- modal create button -->
  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#monitorCreateModal">Create</button>
  <!-- modal create button -->

  <!-- modal create window-->
  <div class="modal fade" id="monitorCreateModal">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Create Monitor    <span id="monitorCreateMessage"></span></h4>
        </div>

        <div class="modal-body">
         <span class="form-inline">
          <div class="form-group">
            <label for="createMonitortName" class="sr-only">MONITORNAME</label>
            <input type="text" class="form-control" id="createMonitorName" placeholder="MONITORNAME"/>
          </div>
          <div class="form-group">
            <label for="createMonitorTimeout" class="sr-only">TIMEOUT</label>
            <input type="text" class="form-control" id="createMonitorTimeout" placeholder="TIMEOUT"/>
          </div>
          <div class="form-group">
            <label for="createMonitorRetry" class="sr-only">TIMEOUT</label>
            <input type="text" class="form-control" id="createMonitorRetry" placeholder="RETRY"/>
          </div>
         </span>

         <span class="form-inline">
          <div class="form-group">
            <select id="createMonitorType" class="form-control">
              <option>PING</option>
              <option>HTTP</option>
              <option>HTTPS</option>
            </select>
          </div>
          <div class="form-group">
            <textarea id="createMonitorArgument" class="form-control" placeholder='GET / HTTP/1.0'></textarea>
          </div>
         </span>

         <span class="form-inline">
          <div class="form-group">
            <button type="button" class="btn btn-primary" onclick="monitorCreate()">CREATE</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>
          </div>
         </span>
        </div>

      </div> <!-- modal-content -->
    </div> <!-- moda-daialog -->
  </div> <!-- modal fade -->
  <!-- modal create window-->
  <!-- modal create ------------------------- -->

  <!-- modal delete ------------------------- -->
  <!-- modal delete button -->
  <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#monitorDeleteModal" onclick="monitorDeleteCheck()">Delete</button><br>

  <!-- modal delete window -->
  <div class="modal fade" id="monitorDeleteModal">
    <div class="modal-dialog">

      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Delete monitor?</h4>
        </div>
        <div class="modal-body">
          <ul id="monitorDeleteCheckedDisp"></ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" onclick="monitorDelete()" data-dismiss="modal">DELETE</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>
        </div>
      </div>

    </div>
  </div>
  <!-- modal delete window -->
  <!-- modal delete ------------------------- -->

  <!-- monitorList -------------------------- -->
  <div class="table-responsive">
    <table id="monitorTable" class="table table-striped table-condensed">
      <tr>
        <th>monitorName</th>
        <th>type</th>
        <th>argument</th>
        <th colspan="2" class="text-center">timeout/retry</th>
      </tr>
    </table>
  </div> 
  <!-- monitorList -------------------------- -->

