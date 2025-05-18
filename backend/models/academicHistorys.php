<?php
function getAllAcademicHistorys($conn) {
     $sql = "SELECT CONCAT(st.first_name,' ',st.last_name) as student_name,
            a.student_id,
            su.subject_name,
            a.subject_id,
            a.id,
            a.approved
            FROM academic_history as a
            INNER JOIN students as st ON a.student_id = st.id
            INNER JOIN subjects as su ON a.subject_id = su.id
            ORDER BY student_name, subject_name";
    return $conn->query($sql);
}

function getStudentIdByName($conn, $student_name) {
    $stmt = $conn->prepare("SELECT id FROM students WHERE CONCAT(first_name, ' ', last_name) = ?");
    $stmt->bind_param("s", $student_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if (!$student) {
        http_response_code(404);
        echo json_encode(["error" => "Estudiante no encontrado"]);
        return false;
    }

    return $student['id'];
}

function getSubjectIdByName($conn, $subject_name) {
    $stmt = $conn->prepare("SELECT id FROM subjects WHERE subject_name = ?");
    $stmt->bind_param("s", $subject_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $subject = $result->fetch_assoc();

    if (!$subject) {
        http_response_code(404);
        echo json_encode(["error" => "Materia no encontrada"]);
        return false;
    }

    return $subject['id'];
}

function getAcademicHistoryById($conn, $id) {
    $sql = "SELECT * FROM academic_history WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result();
}

function createAcademicHistory($conn, $student_name, $subject_name, $approved) {
    
    $student_id = getStudentIdByName($conn, $student_name);
    $subject_id = getSubjectIdByName($conn, $subject_name);

    if (!$student_id || !$subject_id) {
        return false;
    }

    // Insertar la historia académica
    $stmt = $conn->prepare("INSERT INTO academic_history (student_id, subject_id, approved) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $student_id, $subject_id, $approved);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Información agregada correctamente"]);
        return true;
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error al insertar la historia académica"]);
        return false;
    }
}

function updateAcademicHistory($conn, $id, $student_name, $subject_name, $approved) {
    $student_id = getStudentIdByName($conn, $student_name);
    $subject_id = getSubjectIdByName($conn, $subject_name);

    if (!$student_id || !$subject_id) {
        return false;
    }

    $stmt = $conn->prepare("UPDATE academic_history SET student_id = ?, subject_id = ?, approved = ? WHERE id = ?");
    $stmt->bind_param("iisi", $student_id, $subject_id, $approved, $id);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Actualización exitosa"]);
        return true;
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error al actualizar la historia académica"]);
        return false;
    }
}

function deleteAcademicHistory($conn, $id) {
    $sql = "DELETE FROM academic_history WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
?>