<?php
require_once("./models/students.php");

function handleGet($conn) {
    if (isset($_GET['id'])) {
        $result = getStudentById($conn, $_GET['id']);
        echo json_encode($result->fetch_assoc());
    } else {
        $result = getAllStudents($conn);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    }
}

function handlePost($conn) {
    $input = json_decode(file_get_contents("php://input"), true);
    if (createStudent($conn, $input['firstName'], $input['lastName'], $input['email'], $input['age'])) {
        echo json_encode(["message" => "Estudiante agregado correctamente"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo agregar"]);
    }
}

function handlePut($conn) {
    $input = json_decode(file_get_contents("php://input"), true);
    if (updateStudent($conn, $input['id'], $input['firstName'], $input['lastName'], $input['email'], $input['age'])) {
        echo json_encode(["message" => "Actualizado correctamente"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo actualizar"]);
    }
}

function handleDelete($conn) {
     try{
        $input = json_decode(file_get_contents("php://input"), true);
        $response = deleteStudent($conn, $input['id']);
        if ($response==true){
            echo json_encode(["message" => "Eliminado correctamente"]);
        }
    }catch(Exception $e){
        http_response_code($e->getCode() ?: 500); // Si no hay código, usamos 500
        //Devolvemos el error en formato JSON
        echo json_encode(["error" => $e->getMessage()]);
        return;
    }
}
?>