<?php
require_once("./models/academicHistorys.php");

function handleGet($conn) {
    if (isset($_GET['id'])) {
        $result = getAcademicHistoryById($conn, $_GET['id']);
        echo json_encode($result->fetch_assoc());
    } else {
        $result = getAllAcademicHistorys($conn);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    }
}

function handlePost($conn) {
    $input = json_decode(file_get_contents("php://input"), true);
    if (!isset($input['student_name']) || !isset($input['subject_name']) || !isset($input['approved'])) {
        http_response_code(400);
        echo json_encode(["error" => "Faltan parámetros"]);
        return;
    }

    $student_name = $input['student_name'];
    $subject_name = $input['subject_name'];
    $approved = $input['approved'];

    createAcademicHistory($conn, $student_name, $subject_name, $approved);
}

function handlePut($conn) {
    $input = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($input['id']) || !isset($input['student_name']) || !isset($input['subject_name']) || !isset($input['approved'])) {
        http_response_code(400);
        echo json_encode(["error" => "Faltan parámetros"]);
        return;
    }

    $id = $input['id'];
    $student_name = $input['student_name'];
    $subject_name = $input['subject_name'];
    $approved = $input['approved'];

    updateAcademicHistory($conn, $id, $student_name, $subject_name, $approved);
}

function handleDelete($conn) {
    $input = json_decode(file_get_contents("php://input"), true);
    if (deleteAcademicHistory($conn, $input['id'])) {
        echo json_encode(["message" => "Eliminado correctamente"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo eliminar"]);
    }
}
?>