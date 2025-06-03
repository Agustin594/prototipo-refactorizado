<?php
function getAllSubjects($conn) {
    $sql = "SELECT * FROM subjects";
    return $conn->query($sql);
}

function getSubjectById($conn, $id) {
    $sql = "SELECT * FROM subjects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result();
}

function createSubject($conn, $subject_name) {
    $sql = "INSERT INTO subjects (subject_name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $subject_name);
    return $stmt->execute();
}

function updateSubject($conn, $id, $subject_name) {
    $sql = "UPDATE subjects SET subject_name = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $subject_name, $id);
    return $stmt->execute();
}

function deleteSubject($conn, $id) {

    //Chequea que el id no este en academic_history en student_id
    $sql = "SELECT COUNT(*) as total FROM academic_history WHERE subject_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result['total'] > 0) {
        echo json_encode([
            "success" => false,
            "message" => "No se puede eliminar la materia porque tiene materias asociadas."
        ]);
        exit();
    }

    //si es 0 borra.

    $sql = "DELETE FROM subjects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
?>