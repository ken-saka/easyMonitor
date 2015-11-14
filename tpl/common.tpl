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
  <div class="tab-pane active" id="tab1">
  <!-- alert page ----------------------------------------------------------->
    <?php include('./tpl/alert.tpl'); ?>
  <!-- alert page ----------------------------------------------------------->
  </div>

  <div class="tab-pane" id="tab2">
  <!-- host page --------------------------------------------------------- -->
    <?php include('./tpl/host.tpl'); ?>
  <!-- host page --------------------------------------------------------- -->
  </div>

  <div class="tab-pane" id="tab3">
  <!-- monitor page ------------------------------------------------------ -->
    <?php include('./tpl/monitor.tpl'); ?>
  <!-- monitor page ------------------------------------------------------ -->
  </div>

  <div class="tab-pane" id="tab4">
  <!-- monitor page ------------------------------------------------------ -->
    <?php include('./tpl/monitor.tpl'); ?>
  <!-- monitor page ------------------------------------------------------ -->
  </div>
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
</script>
<!-- script -->
</body>
</html>
