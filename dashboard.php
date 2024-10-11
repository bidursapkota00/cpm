<?php

require 'authentication.php'; // admin authentication check 

// auth check
$user_id = $_SESSION['admin_id'];
$user_name = $_SESSION['name'];
$security_key = $_SESSION['security_key'];
if ($user_id == NULL || $security_key == NULL) {
    header('Location: index.php');
}

// check admin
$user_role = $_SESSION['user_role'];

if(isset($_GET['delete_task'])){
  $action_id = $_GET['task_id'];
  
  $sql = "DELETE FROM task_info WHERE task_id = :id";
  $sent_po = "task-info.php";
  $obj_admin->delete_data_by_this_method($sql, $action_id, $sent_po);
}

if(isset($_POST['add_task_post'])){
    $obj_admin->add_new_task($_POST);
}

$page_name = "Dashboard";
include("include/sidebar.php");

// Fetch the total number of tasks
$total_tasks = 0;
try {
    $sql = "SELECT COUNT(*) as total_tasks FROM task_info";
    $stmt = $obj_admin->db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $total_tasks = $result['total_tasks'];
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<div class="row">
  <div class="col-md-12">
    <div class="well well-custom">
      <h3 class="mt-4 pt-4 text-info">Admin | Dashboard</h3>
      <hr>
      <div class="row">
        <div class="col-sm-4 col-md-4 g-2 mb-3">
          <div class="card" style="width: 18rem;background-color:lavender;padding:15px;margin-bottom:10px;">
            <div class="card-body">
              <h4 class="card-title">Task Management</h4>
              <p class="card-text">
                <?php
                echo "<h1 style='color:#00208a;font-weight:bold;font-size:40px;text-align:center;'>" . $total_tasks . "</h1>";
                ?>
              </p>
              <a href="task-info.php" class="btn" style="font-size: 15px;background-color:green;color: white;border-radius: 5px; padding:5px 30px;">view</a>
            </div>
          </div>
        </div>


        <div class="col-sm-4 col-md-4 g-2 mb-3">
          <div class="card" style="width: 18rem;background-color:lavender;padding:15px;margin-bottom:10px;">
            <div class="card-body">
              <h4 class="card-title">Attendance</h4>
              <p class="card-text">
                <h1 style="color:#00208a;font-weight:bold;font-size:40px;text-align:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
  <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
</svg>   
              </h1>
               
                </p>
              <a href="attendance-info.php" class="btn" style="font-size: 15px;background-color:green;color: white;border-radius: 5px; padding:5px 30px;">view</a>
            </div>
          </div>
        </div>

        
        <div class="col-sm-4 col-md-4 g-2 mb-3">
          <div class="card" style="width: 18rem;background-color:lavender;padding:15px;">
            <div class="card-body">
              <h4 class="card-title">Administration</h4>
              <p class="card-text">           
<h1 style="color:#00208a;font-weight:bold;font-size:40px;text-align:center;">
<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-person-add" viewBox="0 0 16 16">
  <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4"/>
  <path d="M8.256 14a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z"/>
</svg> 
              </h1>
               
              </p>
              <a href="manage-admin.php" class="btn" style="font-size: 15px;background-color:green;color: white;border-radius: 5px; padding:5px 30px;">view</a>
            </div>
          </div>
        </div>


      </div>

    </div>
  </div>
</div>

<?php include("include/footer.php"); ?>
