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
    if (!isset($input['student_id']) || !isset($input['subject_id']) || !isset($input['approved'])) {
        http_response_code(400);
        echo json_encode(["error" => "Faltan parámetros"]);
        return;
    }

    createAcademicHistory($conn, $input['student_id'], $input['subject_id'], $input['approved']);
}

function handlePut($conn) {
    $input = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($input['id']) || !isset($input['student_id']) || !isset($input['subject_id']) || !isset($input['approved'])) {
        http_response_code(400);
        echo json_encode(["error" => "Faltan parámetros"]);
        return;
    }

    $id = $input['id'];
    $student_id = $input['student_id'];
    $subject_id = $input['subject_id'];
    $approved = $input['approved'];

    updateAcademicHistory($conn, $id, $student_id, $subject_id, $approved);
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