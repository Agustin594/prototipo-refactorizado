<?php
require_once("./models/subjects.php");

function handleGet($conn) {
    if (isset($_GET['id'])) {
        $result = getSubjectById($conn, $_GET['id']);
        echo json_encode($result->fetch_assoc());
    } else {
        $result = getAllSubjects($conn);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    }
}

function handlePost($conn) {
    $input = json_decode(file_get_contents("php://input"), true);
    if (createSubject($conn, $input['name'])) {
        echo json_encode(["message" => "Materia agregada correctamente"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo agregar"]);
    }
}

function handlePut($conn) {
    $input = json_decode(file_get_contents("php://input"), true);
    if (updateSubject($conn, $input['id'], $input['name'])) {
        echo json_encode(["message" => "Actualizado correctamente"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo actualizar"]);
    }
}

function handleDelete($conn) {
    try{
        $input = json_decode(file_get_contents("php://input"), true);
        $response = deleteSubject($conn, $input['id']);
        if ($response == true) {
            echo json_encode(["message" => "Materia eliminada correctamente"]);
        }
    } catch(Exception $e) {
        //Tomamos el error para poder mostrarlo
        http_response_code($e->getCode() ?: 500); // Si no hay código, usamos 500
        //Devolvemos el error en formato JSON
        echo json_encode(["error" => $e->getMessage()]);
        return;
    }
}
?>