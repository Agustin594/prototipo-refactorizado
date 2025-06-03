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
    $sql = "DELETE FROM students WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    try{
        if ($stmt->execute())
            return true;
        else
            throw new Exception("Error al eliminar al estudiante" . $stmt->error, $stmt->errno);
    }catch (Exception $e) {
        if ($e->getCode() == 1451) { //Codigo de eliminar llave foranea
            throw new Exception ("No se puede eliminar al estudiante porque tiene cursos asociados",409);
        }else{
            throw $e;
        }
    }
}
?>