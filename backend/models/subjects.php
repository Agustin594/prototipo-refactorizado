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
    $sql = "DELETE FROM subjects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    try{
        if($stmt->execute())
            return true;
        else
            throw new Exception("Error al eliminar la materia: " . $stmt->error, $stmt->errno);
    }catch (Exception $e) {
        if ($e->getCode() == 1451) { // Error de clave foránea
            throw new Exception ("No se puede eliminar la materia porque tiene estudiantes asignados",409);
        }else{
            throw $e;
        }
    }
}
?>