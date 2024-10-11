<!DOCTYPE html>
<html lang="en">

<head>
  <title>Employee Task Management System</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/bootstrap.theme.min.css">
  <link rel="stylesheet" href="assets/bootstrap-datepicker/css/datepicker.css">
  <link rel="stylesheet" href="assets/bootstrap-datepicker/css/datepicker-custom.css">
  <link rel="stylesheet" href="assets/css/custom.css">
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <script src="assets/js/custom.js"></script>
  <script src="assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
  <script src="assets/bootstrap-datepicker/js/datepicker-custom.js"></script>
  <script type="text/javascript">
    /* delete function confirmation  */
    function check_delete() {
      var check = confirm('Are you sure you want to delete this?');
      if (check) {

        return true;
      } else {
        return false;
      }
    }
  </script>
</head>

<body>

  <nav class="navbar navbar-inverse sidebar navbar-fixed-top" role="navigation">
    <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-sidebar-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="dashboard.php"><span style="color: #d4ab3a; font-weight: bold;">ETMS</span></a>
      </div>

      <?php
      $user_role = $_SESSION['user_role'];
      if ($user_role == 1) {
      ?>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-nav-custom">
            <li <?php if ($page_name == "Dashboard") {
                  echo "class=\"active\"";
                } ?>><a href="dashboard.php"><span style="font-size:16px; color:#d4ab3a;" class=" hidden-xs showopacity glyphicon glyphicon-home"></span>&nbsp; Dashboard</a></li>
            <li <?php if ($page_name == "Task_Info") {
                  echo "class=\"active\"";
                } ?>><a href="task-info.php"><span style="font-size:16px; color:#d4ab3a;" class="hidden-xs showopacity glyphicon glyphicon-tasks"></span> Task Mangement</a></li>
            <li <?php if ($page_name == "Add_Task") {
                  echo "class=\"active\"";
                } ?>><a href="add-task.php"><span style="font-size:16px; color:#d4ab3a;" class="hidden-xs showopacity glyphicon glyphicon-tasks"></span> Add Project</a></li>
            <li <?php if ($page_name == "Attendance") {
                  echo "class=\"active\"";
                } ?>><a href="attendance-info.php"><span style="font-size:16px; color:#d4ab3a;" class="hidden-xs showopacity glyphicon glyphicon-calendar"></span> Attendance </a></li>
            <li <?php if ($page_name == "Admin") {
                  echo "class=\"active\"";
                } ?>><a href="manage-admin.php"><span style="font-size:16px; color:#d4ab3a;" class="hidden-xs showopacity glyphicon glyphicon-user"></span> Administration</a></li>
            <li><a href="?logout=logout"><span style="font-size:16px; color:#d4ab3a;" class="hidden-xs showopacity glyphicon glyphicon-log-out"></span> Logout</a></li>
          </ul>
        </div>
      <?php
      } else if ($user_role == 2) {

      ?>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-nav-custom">
            <li <?php if ($page_name == "Task_Info") {
                  echo "class=\"active\"";
                } ?>><a href="task-info.php">Task Mangement<span style="font-size:16px; color:#d4ab3a;" class="pull-right hidden-xs showopacity glyphicon glyphicon-tasks"></span></a></li>
            <li <?php if ($page_name == "Attendance") {
                  echo "class=\"active\"";
                } ?>><a href="attendance-info.php">Attendance <span style="font-size:16px; color:#d4ab3a;" class="pull-right hidden-xs showopacity glyphicon glyphicon-calendar"></span></a></li>
            <li><a href="?logout=logout">Logout<span style="font-size:16px; color:#d4ab3a;" class="pull-right hidden-xs showopacity glyphicon glyphicon-log-out"></span></a></li>
          </ul>
        </div>

      <?php

      } else {
        header('Location: index.php');
      }

      ?>



    </div>
  </nav>



  <div class="main">