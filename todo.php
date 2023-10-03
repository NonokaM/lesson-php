<?php
$db = new SQLite3('todo.db');

function getAllTasks()
{
    global $db;
    $tasks = [];
    $results = $db->query('SELECT id, task FROM tasks');
    while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
        $tasks[] = $row;
    }
    return $tasks;
}

function addTask($task)
{
    global $db;
    $stmt = $db->prepare('INSERT INTO tasks (task) VALUES (:task)');
    $stmt->bindValue(':task', $task, SQLITE3_TEXT);
    $stmt->execute();
}

function removeTask($id)
{
    global $db;
    $stmt = $db->prepare('DELETE FROM tasks WHERE id = :id');
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add']) && isset($_POST['task'])) {
        addTask($_POST['task']);
    } elseif (isset($_GET['remove'])) {
        removeTask($_GET['remove']);
    }
    exit();
}

header('Content-Type: application/json');
echo json_encode(getAllTasks());
