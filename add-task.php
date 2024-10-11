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


// if (isset($_POST['add_task_post'])) {
//   $obj_admin->add_new_task($_POST);
// }

$page_name = "Add_Task";
include("include/sidebar.php");
// include('ems_header.php');


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Project Task Management</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    .container {
      max-width: 600px;
      margin: auto;
    }

    .task {
      border: 1px solid #ccc;
      padding: 10px;
      margin-bottom: 10px;
      border-radius: 5px;
    }

    .task h3 {
      margin-top: 0;
    }

    label {
      display: block;
      margin-top: 10px;
    }

    input[type="text"],
    input[type="number"],
    select {
      width: 100%;
      padding: 8px;
      margin: 5px 0;
      box-sizing: border-box;
    }

    select[multiple] {
      height: auto;
    }

    .remove-task {
      margin-top: 10px;
      color: red;
      cursor: pointer;
    }

    #addTask {
      margin-top: 20px;
      padding: 10px;
      background-color: #4caf50;
      color: white;
      border: none;
      cursor: pointer;
    }

    #addTask:hover {
      background-color: #45a049;
    }

    .submit-btn {
      margin-top: 20px;
      padding: 10px;
      background-color: #008cba;
      color: white;
      border: none;
      cursor: pointer;
    }

    .submit-btn:hover {
      background-color: #007bb5;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>Project and Tasks Form</h2>
    <form id="projectForm" action="add-task-action.php" method="POST" target="_blank">
      <label for="projectName">Project Name:</label>
      <input type="text" id="projectName" name="projectName" required />

      <div id="taskContainer">
        <!-- Tasks will be added here dynamically -->
      </div>

      <button type="button" id="addTask">Add Task</button>

      <input type="submit" value="Submit" class="submit-btn" />
    </form>
  </div>

  <script>
    let taskCount = 0;

    // Function to add a new task
    function addTask() {
      taskCount++;

      const taskDiv = document.createElement("div");
      taskDiv.classList.add("task");
      taskDiv.id = `task-${taskCount}`;

      taskDiv.innerHTML = `
                <h3>Task ${taskCount}</h3>
                <label for="taskName-${taskCount}">Task Name:</label>
                <input type="text" id="taskName-${taskCount}" name="taskName-${taskCount}" required>

                <label for="taskTime-${taskCount}">Estimated Time (in days):</label>
                <input type="number" id="taskTime-${taskCount}" name="taskTime-${taskCount}" min="1" required>

                <label for="taskDependency-${taskCount}">Required Tasks Before This Task:</label>
                <select id="taskDependency-${taskCount}" name="taskDependency-${taskCount}[]" multiple>
                    ${generateTaskOptions()}
                </select>

                <div class="remove-task" onclick="removeTask(${taskCount})">Remove Task</div>
            `;

      document.getElementById("taskContainer").appendChild(taskDiv);
    }

    // Function to generate the list of tasks for dependencies
    function generateTaskOptions() {
      let options =
        '<option value="" disabled>Select Required Tasks</option>';
      for (let i = 1; i <= taskCount; i++) {
        if (document.getElementById(`task-${i}`)) {
          const taskName =
            document.getElementById(`taskName-${i}`).value || `Task ${i}`;
          options += `<option value="task-${i}">${taskName}</option>`;
        }
      }
      return options;
    }

    // Function to remove a task
    function removeTask(id) {
      document.getElementById(`task-${id}`).remove();
    }

    // Event listener to add a new task
    document.getElementById("addTask").addEventListener("click", addTask);
  </script>
</body>

</html>