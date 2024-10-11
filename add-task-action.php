<?php
// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get project name
    $projectName = $_POST['projectName'];

    // Initialize an empty array to store tasks
    $tasks = [];

    // Print the entire POST data (for debugging purposes)
    // print_r($_POST); 

    // Loop through the posted data to gather all tasks
    foreach ($_POST as $key => $value) {
        // Look for taskName keys to determine tasks
        if (strpos($key, 'taskName-') !== false) {
            // Extract task number from the field name (e.g., taskName-1 -> 1)
            $taskNumber = explode('-', $key)[1];

            // Gather data for this specific task
            $taskName = $_POST["taskName-$taskNumber"];
            $taskTime = $_POST["taskTime-$taskNumber"];
            $dependencies = isset($_POST["taskDependency-$taskNumber"]) ? $_POST["taskDependency-$taskNumber"] : [];

            // Store the task data including task number
            $tasks[] = [
                "taskNumber" => "task-$taskNumber",  // Store task number as task-1, task-2, etc.
                "taskName" => $taskName,
                "taskTime" => $taskTime,
                "dependencies" => $dependencies
            ];
        }
    }

    // print_r($tasks);
    // echo "<br>";

    // Initialize successors
    foreach ($tasks as $key => $task) {
        $tasks[$key]['successors'] = [];
    }

    // Loop through each task and assign successors based on dependencies
    foreach ($tasks as $key => $task) {
        foreach ($task['dependencies'] as $dependency) {
            foreach ($tasks as $innerKey => $innerTask) {
                if ($innerTask['taskNumber'] === $dependency) {
                    $tasks[$innerKey]['successors'][] = $task['taskNumber'];
                }
            }
        }
    }
    // print_r($tasks);
    // echo "<br>";


    // Function to calculate the critical path
    function calculateCriticalPath($tasks)
    {
        // Step 1: Prepare task data
        $taskData = [];
        foreach ($tasks as $task) {
            $taskData[$task['taskNumber']] = [
                'taskName' => $task['taskName'],
                'duration' => (int)$task['taskTime'],
                'dependencies' => $task['dependencies'],
                'successors' => $task['successors'],
                'ES' => 0, // Early Start
                'EF' => 0, // Early Finish
                'LS' => 0, // Late Start
                'LF' => 0, // Late Finish
                'slack' => 0
            ];
        }

        // print_r($taskData);

        // Step 2: Perform Forward Pass (calculate ES and EF)
        foreach ($taskData as $taskNumber => &$task) {
            $maxEF = 0;
            foreach ($task['dependencies'] as $dependency) {
                if ($taskData[$dependency]['EF'] > $maxEF) {
                    $maxEF = $taskData[$dependency]['EF'];
                }
            }
            $task['ES'] = $maxEF; // Earliest Start is the max EF of dependencies
            $task['EF'] = $task['ES'] + $task['duration']; // Earliest Finish = ES + duration
        }
        unset($task); // Unset reference
        // print_r($taskData);

        // Step 3: Perform Backward Pass (calculate LS and LF)
        // Start from the last task (tasks with the highest EF are considered last tasks)
        $maxEF = max(array_column($taskData, 'EF')); // Project duration = max EF

        // Initialize all tasks' LF to the max EF, indicating the project duration
        foreach ($taskData as &$task) {
            $task['LF'] = $maxEF;
        }
        unset($task);
        // print_r($taskData);

        // Go through tasks backwards to calculate LS and LF
        for ($i = count($taskData) - 1; $i >= 0; $i--) {
            $task = &$taskData[array_keys($taskData)[$i]];
            $taskNumber = array_keys($taskData)[$i];

            if (empty($task['successors'])) {
                // For tasks with no successors, LF remains the project end time
                $task['LS'] = $task['LF'] - $task['duration'];
            } else {
                // Find the minimum LS among successors
                $minSuccessorLS = PHP_INT_MAX;
                foreach ($task['successors'] as $successor) {
                    $minSuccessorLS = min($minSuccessorLS, $taskData[$successor]['LS']);
                }
                $task['LF'] = $minSuccessorLS;
                $task['LS'] = $task['LF'] - $task['duration'];
            }

            // Calculate slack
            $task['slack'] = $task['LS'] - $task['ES'];
        }
        unset($task);

        // Step 4: Identify the critical path (tasks with zero slack)
        $criticalPath = [];
        foreach ($taskData as $taskNumber => $task) {
            if ($task['slack'] === 0) {
                $criticalPath[] = $taskNumber;
            }
        }

        return [
            'taskData' => $taskData,
            'criticalPath' => $criticalPath,
            'projectDuration' => $maxEF
        ];
    }

    $criticalPath = calculateCriticalPath($tasks);
    // echo '<pre>';
    // print_r($criticalPath);
    // echo '</pre>';
} else {
    // If no POST data, inform the user
    echo "No data received.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CPM Graph with Metadata Table</title>
    <script
        type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.js"></script>
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis-network.min.css"
        rel="stylesheet"
        type="text/css" />
    <style>
        #network {
            width: 100%;
            height: 100vh;
            border: 1px solid lightgray;
            margin-bottom: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
        }

        h3 {
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>Critical Path Method (CPM) Graph</h2>
    <div id="network"></div>

    <h3>Task Metadata</h3>
    <table id="task-metadata">
        <thead>
            <tr>
                <th>Task</th>
                <th>Title</th>
                <th>Time (days)</th>
                <th>ES</th>
                <th>EF</th>
                <th>LS</th>
                <th>LF</th>
                <th>Slack</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <script>
        // PHP array to JavaScript
        let tasks = <?php echo json_encode($criticalPath['taskData']); ?>;
        let criticalPath = <?php echo json_encode($criticalPath['criticalPath']); ?>;

        // Initialize nodes with "Start" and "Finish" placeholders
        let nodes = [{
                id: "start",
                label: "Start",
                shape: "ellipse"
            },
            {
                id: "finish",
                label: "Finish",
                shape: "ellipse"
            }
        ];

        // Create task nodes using task numbers (e.g., "task-1", "task-2")
        nodes = nodes.concat(Object.keys(tasks).map((taskNumber) => ({
            id: taskNumber,
            label: `${taskNumber}\nDuration: ${tasks[taskNumber].duration} days\nSlack: ${tasks[taskNumber].slack} days`, // Include task number and slack in the node
            shape: "box",
            color: criticalPath.includes(taskNumber) ? "yellow" : undefined // Highlight critical path nodes in red
        })));

        // Create edges (dependencies)
        let edges = [];

        // Connect "Start" node to tasks with no dependencies
        Object.keys(tasks).forEach((taskNumber) => {
            if (tasks[taskNumber].dependencies.length === 0) {
                edges.push({
                    from: "start",
                    to: taskNumber,
                    color: {
                        color: criticalPath.includes(taskNumber) ? "#ff0000" : "#848484"
                    },
                    width: criticalPath.includes(taskNumber) ? 2 : 1
                });
            }
        });

        // Connect tasks based on dependencies
        Object.keys(tasks).forEach((taskNumber) => {
            tasks[taskNumber].dependencies.forEach((dependency) => {
                edges.push({
                    from: dependency,
                    to: taskNumber,
                    color: {
                        color: (criticalPath.includes(taskNumber) && criticalPath.includes(dependency)) ? "#ff0000" : "#848484"
                    },
                    width: (criticalPath.includes(taskNumber) && criticalPath.includes(dependency)) ? 2 : 1
                });
            });
        });

        // Connect tasks with no successors to "Finish"
        Object.keys(tasks).forEach((taskNumber) => {
            if (tasks[taskNumber].successors.length === 0) {
                edges.push({
                    from: taskNumber,
                    to: "finish",
                    color: {
                        color: criticalPath.includes(taskNumber) ? "#ff0000" : "#848484"
                    },
                    width: criticalPath.includes(taskNumber) ? 2 : 1
                });
            }
        });

        // Create a network
        let container = document.getElementById("network");
        let data = {
            nodes: new vis.DataSet(nodes),
            edges: new vis.DataSet(edges),
        };

        let options = {
            layout: {
                hierarchical: {
                    direction: "LR", // Left-Right layout
                    sortMethod: "directed",
                },
            },
            edges: {
                arrows: {
                    to: {
                        enabled: true,
                        scaleFactor: 1,
                        type: "arrow"
                    }
                },
                color: {
                    inherit: false
                },
                // smooth: {
                //     type: "cubicBezier",
                //     forceDirection: "horizontal"
                // }
            },
            physics: false
        };

        let network = new vis.Network(container, data, options);

        // Generate Task Metadata Table
        let tableBody = document.getElementById("task-metadata").getElementsByTagName("tbody")[0];
        Object.keys(tasks).forEach((taskNumber) => {
            let row = tableBody.insertRow();
            let taskCell = row.insertCell(0);
            let titleCell = row.insertCell(1);
            let timeCell = row.insertCell(2);
            let esCell = row.insertCell(3); // Early Start
            let efCell = row.insertCell(4); // Early Finish
            let lsCell = row.insertCell(5); // Late Start
            let lfCell = row.insertCell(6); // Late Finish
            let slackCell = row.insertCell(7); // Slack

            // Fill in the task information
            taskCell.textContent = taskNumber;
            titleCell.textContent = tasks[taskNumber].taskName;
            timeCell.textContent = tasks[taskNumber].duration;
            esCell.textContent = tasks[taskNumber].ES;
            efCell.textContent = tasks[taskNumber].EF;
            lsCell.textContent = tasks[taskNumber].LS;
            lfCell.textContent = tasks[taskNumber].LF;
            slackCell.textContent = tasks[taskNumber].slack;

            // Highlight critical path tasks in bold
            if (criticalPath.includes(taskNumber)) {
                taskCell.style.fontWeight = "bold";
                titleCell.style.fontWeight = "bold";
                timeCell.style.fontWeight = "bold";
                esCell.style.fontWeight = "bold";
                efCell.style.fontWeight = "bold";
                lsCell.style.fontWeight = "bold";
                lfCell.style.fontWeight = "bold";
                slackCell.style.fontWeight = "bold";
            }
        });
    </script>
</body>

</html>