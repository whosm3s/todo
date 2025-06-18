<?php
session_start();
if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}
if (!isset($_SESSION['done'])) {
    $_SESSION['done'] = [];
}

$tasks = $_SESSION['tasks'];
$done = $_SESSION['done'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['task'])) {
        // Add a new task
        $taskText = trim($_POST['task']);
        if (!empty($taskText)) {
            $tasks[] = ['text' => $taskText];
            $_SESSION['tasks'] = $tasks; 
        }
    } elseif (isset($_POST['done'])) {
        $doneIndex = intval($_POST['done']);
        if (isset($tasks[$doneIndex])) {
            $done[] = $tasks[$doneIndex];
            unset($tasks[$doneIndex]);
            $tasks = array_values($tasks); 
            $_SESSION['tasks'] = $tasks;
            $_SESSION['done'] = $done; 
        }
    } elseif (isset($_POST['reset_done'])) {
        $_SESSION['done'] = [];
        $done = [];
    }


    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>To-Do App</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>My To-Do List</h1>
    <form method="POST">
        <input type="text" name="task" placeholder="Enter a task" required>
        <button type="submit">Add Task</button>
    </form>

    <h2>Active Tasks</h2>
    <ul>
        <?php foreach ($tasks as $index => $task): ?>
            <li>
                <?php echo htmlspecialchars($task['text']); ?>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="done" value="<?php echo $index; ?>">
                    <button type="submit">Done</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Done Tasks</h2>
    <form method="POST">
        <button type="submit" name="reset_done" value="1">Reset Done Tasks</button>
    </form>
    <ul>
        <?php foreach ($done as $task): ?>
            <li><?php echo htmlspecialchars($task['text']); ?> (Done)</li>
        <?php endforeach; ?>
    </ul>
</body>
</html>