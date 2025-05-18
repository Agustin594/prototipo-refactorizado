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

function createSubject($conn, $subject_name, $teacher) {
    $sql = "INSERT INTO subjects (subject_name, teacher) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $subject_name, $teacher);
    return $stmt->execute();
}

function updateSubject($conn, $id, $subject_name, $teacher) {
    $sql = "UPDATE subjects SET subject_name = ?, teacher = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $subject_name, $teacher, $id);
    return $stmt->execute();
}

function deleteSubject($conn, $id) {
    $sql = "DELETE FROM subjects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
?>