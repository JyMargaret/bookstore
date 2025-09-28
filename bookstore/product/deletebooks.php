<?php
require_once "../classes/book.php";
require_once "../classes/database.php";

$db = new Database();
$bookObj = new Book($db);

$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($book_id > 0) {
    try {
        $conn = $db->connect();
        
        $checkSql = "SELECT * FROM books WHERE id = :id";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bindParam(":id", $book_id, PDO::PARAM_INT);
        $checkStmt->execute();
        
        if ($checkStmt->rowCount() > 0) {
            $deleteSql = "DELETE FROM books WHERE id = :id";
            $deleteStmt = $conn->prepare($deleteSql);
            $deleteStmt->bindParam(":id", $book_id, PDO::PARAM_INT);
            
            if ($deleteStmt->execute()) {
                $rowsAffected = $deleteStmt->rowCount();
                
                if ($rowsAffected > 0) {
                    header("Location: viewbooks.php?deleted=success");
                    exit();
                } else {
                    header("Location: viewbooks.php?deleted=error&message=" . urlencode("No book was deleted"));
                    exit();
                }
            } else {
                $errorInfo = $deleteStmt->errorInfo();
                header("Location: viewbooks.php?deleted=error&message=" . urlencode("Database error: " . $errorInfo[2]));
                exit();
            }
        } else {
            header("Location: viewbooks.php?deleted=error&message=" . urlencode("Book not found"));
            exit();
        }
    } catch (PDOException $e) {
        header("Location: viewbooks.php?deleted=error&message=" . urlencode("Database error: " . $e->getMessage()));
        exit();
    } catch (Exception $e) {
        header("Location: viewbooks.php?deleted=error&message=" . urlencode("Error: " . $e->getMessage()));
        exit();
    }
} else {
    header("Location: viewbooks.php?deleted=error&message=" . urlencode("Invalid book ID"));
    exit();
}
?>