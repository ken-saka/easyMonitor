<script>
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
     + ' value="' + host_json[data]['host_id'] + '"/>'
     + "&nbsp"
     + host_json[data]['hostname']  + "</td>"
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

// remove host from table
function removeHostTable(host_id){
  $("table#hostTable > tbody > tr[id=" + host_id + "]").remove();
}
</script>

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
        <th>hostname</th>
        <th>ipaddress</th>
        <th>monitor</th>
      </tr>
    </table>
  </div> 
  <!-- hostList -----------------------------     -->
