<?php
function getAllStudents($conn) {
    $sql = "SELECT * FROM students";
    return $conn->query($sql);
}

function getStudentById($conn, $id) {
    $sql = "SELECT * FROM students WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result();
}

function createStudent($conn, $first_name, $last_name, $email, $age) {
    $sql = "INSERT INTO students (first_name, last_name, email, age) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $first_name, $last_name, $email, $age);
    return $stmt->execute();
}

function updateStudent($conn, $id, $first_name, $last_name, $email, $age) {
    $sql = "UPDATE students SET first_name = ?, last_name = ?, email = ?, age = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $first_name, $last_name, $email, $age, $id);
    return $stmt->execute();
}

function deleteStudent($conn, $id) {

    //Chequea que el id no este en academic_history en student_id
    $sql = "SELECT COUNT(*) as total FROM academic_history WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result['total'] > 0) {
        echo json_encode([
            "success" => false,
            "message" => "No se puede eliminar el estudiante porque tiene materias asociadas."
        ]);
        exit();
    }

    //Si total es igual a 0 elimina.

    $sql = "DELETE FROM students WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
?>