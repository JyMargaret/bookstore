<?php
require_once "../classes/book.php";
require_once "../classes/database.php";

$db = new Database();
$bookObj = new Book($db);

$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$book = ["title"=>"", "author"=>"", "genre"=>"", "publication_year"=>""];
$error = ["title"=>"", "author"=>"", "genre"=>"", "publication_year"=>""];
$success_message = "";
$error_message = "";

if ($book_id > 0) {
    $books = $bookObj->viewBooks();
    $bookFound = false;
    
    foreach ($books as $index => $b) {
        $currentId = isset($b['id']) ? intval($b['id']) : ($index + 1);
        if ($currentId == $book_id) {
            $book = [
                "title" => $b["title"],
                "author" => $b["author"],
                "genre" => $b["genre"],
                "publication_year" => $b["publication_year"]
            ];
            $bookFound = true;
            break;
        }
    }
    
    if (!$bookFound) {
        $error_message = "Book not found.";
    }
} else {
    $error_message = "No book selected for editing.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $book_id > 0 && empty($error_message)) {
    $book["title"] = trim($_POST["book_title"] ?? '');
    if (empty($book["title"])) {
        $error["title"] = "Book title is required";
    }
    
    $book["author"] = trim($_POST["author"] ?? '');
    if (empty($book["author"])) {
        $error["author"] = "Author is required";
    }
    
    $book["genre"] = trim($_POST["genre"] ?? '');
    if (empty($book["genre"])) {
        $error["genre"] = "Genre is required";
    }
    
    $book["publication_year"] = trim($_POST["publication_year"] ?? '');
    if (empty($book["publication_year"])) {
        $error["publication_year"] = "Publication year is required";
    } else if (!is_numeric($book["publication_year"]) || $book["publication_year"] < 1) {
        $error["publication_year"] = "Valid year please";
    } else if ($book["publication_year"] > date('Y')) {
        $error["publication_year"] = "Publication year cannot be in the future";
    }

    // If no validation errors, update the book
    if (empty(array_filter($error))) {
        try {
            $conn = $db->connect();
            
            // First, check if the book still exists
            $checkSql = "SELECT * FROM books WHERE id = :id";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bindParam(":id", $book_id, PDO::PARAM_INT);
            $checkStmt->execute();
            
            if ($checkStmt->rowCount() == 0) {
                $error_message = "Book no longer exists in the database.";
            } else {
                // Update the book
                $updateSql = "UPDATE books SET 
                             title = :title, 
                             author = :author, 
                             genre = :genre, 
                             publication_year = :publication_year 
                             WHERE id = :id";
                
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bindParam(":title", $book["title"], PDO::PARAM_STR);
                $updateStmt->bindParam(":author", $book["author"], PDO::PARAM_STR);
                $updateStmt->bindParam(":genre", $book["genre"], PDO::PARAM_STR);
                $updateStmt->bindParam(":publication_year", $book["publication_year"], PDO::PARAM_INT);
                $updateStmt->bindParam(":id", $book_id, PDO::PARAM_INT);
                
                if ($updateStmt->execute()) {
                    $rowsAffected = $updateStmt->rowCount();
                    
                    if ($rowsAffected > 0) {
                        // Redirect with success message instead of using JavaScript
                        header("Location: viewbooks.php?updated=success");
                        exit();
                    } else {
                        $error_message = "No changes were made to the book.";
                    }
                } else {
                    $errorInfo = $updateStmt->errorInfo();
                    $error_message = "Failed to update book: " . htmlspecialchars($errorInfo[2]);
                }
            }
        } catch (PDOException $e) {
            $error_message = "Database error: " . htmlspecialchars($e->getMessage());
        } catch (Exception $e) {
            $error_message = "Error: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 2.5rem;
            font-weight: 300;
        }

        h1::after {
            content: '';
            display: block;
            width: 60px;
            height: 3px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            margin: 15px auto;
            border-radius: 2px;
        }

        .required-note {
            text-align: center;
            color: #7f8c8d;
            margin-bottom: 30px;
            font-style: italic;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.95rem;
        }

        .required {
            color: #e74c3c;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        select:focus {
            outline: none;
            border-color: #667eea;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        select {
            cursor: pointer;
            appearance: none;
            background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 4 5"><path fill="%23666" d="M2 0L0 2h4zm0 5L0 3h4z"/></svg>');
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 12px;
        }

        .error {
            color: #e74c3c;
            font-size: 0.85rem;
            margin-top: 5px;
            font-weight: 500;
            min-height: 20px;
            display: block;
        }

        .submit-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 20px;
            position: relative;
            overflow: hidden;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .submit-btn:active {
            transform: translateY(-1px);
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .success-message {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 500;
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
            animation: slideDown 0.5s ease-out;
        }

        .error-message {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 500;
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
            animation: slideDown 0.5s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .back-btn {
            background: #6c757d;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
            display: inline-block;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .has-error input,
        .has-error select {
            border-color: #e74c3c;
            background-color: #fdf2f2;
        }

        .has-error input:focus,
        .has-error select:focus {
            border-color: #e74c3c;
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .container {
                margin: 10px;
                padding: 25px;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            input[type="text"],
            input[type="number"],
            select {
                font-size: 16px;
            }
        }

        .form-group {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .form-group:nth-child(3) { animation-delay: 0.3s; }
        .form-group:nth-child(4) { animation-delay: 0.4s; }
        .form-group:nth-child(5) { animation-delay: 0.5s; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="viewbooks.php" class="back-btn">← Back to Books</a>
        <h1>Edit Book</h1>
        <div class="required-note">
            Fields marked with <span class="required">*</span> are required
        </div>
        
        <?php if (!empty($success_message)): ?>
            <div class="success-message"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        
        <?php if ($book_id > 0 && !empty($book["title"]) && empty($error_message)): ?>
        <form action="?id=<?= $book_id ?>" method="post">
            <div class="form-group <?= !empty($error["title"]) ? 'has-error' : '' ?>">
                <label for="book_title">Book Title <span class="required">*</span></label>
                <input type="text" name="book_title" id="book_title" value="<?= htmlspecialchars($book["title"]) ?>" placeholder="Enter book title">
                <span class="error"><?= $error["title"] ?></span>
            </div>
            <div class="form-group <?= !empty($error["author"]) ? 'has-error' : '' ?>">
                <label for="author">Author <span class="required">*</span></label>
                <input type="text" name="author" id="author" value="<?= htmlspecialchars($book["author"]) ?>" placeholder="Enter author name">
                <span class="error"><?= $error["author"] ?></span>
            </div>
            <div class="form-group <?= !empty($error["genre"]) ? 'has-error' : '' ?>">
                <label for="genre">Genre <span class="required">*</span></label>
                <select name="genre" id="genre">
                    <option value="">Select a genre</option>
                    <option value="History" <?= $book["genre"] == "History" ? "selected" : "" ?>>History</option>
                    <option value="Science" <?= $book["genre"] == "Science" ? "selected" : "" ?>>Science</option>
                    <option value="Fiction" <?= $book["genre"] == "Fiction" ? "selected" : "" ?>>Fiction</option>
                    <option value="Non-Fiction" <?= $book["genre"] == "Non-Fiction" ? "selected" : "" ?>>Non-Fiction</option>
                    <option value="Biography" <?= $book["genre"] == "Biography" ? "selected" : "" ?>>Biography</option>
                    <option value="Mystery" <?= $book["genre"] == "Mystery" ? "selected" : "" ?>>Mystery</option>
                    <option value="Romance" <?= $book["genre"] == "Romance" ? "selected" : "" ?>>Romance</option>
                    <option value="Fantasy" <?= $book["genre"] == "Fantasy" ? "selected" : "" ?>>Fantasy</option>
                </select>
                <span class="error"><?= $error["genre"] ?></span>
            </div>
            <div class="form-group <?= !empty($error["publication_year"]) ? 'has-error' : '' ?>">
                <label for="publication_year">Publication Year <span class="required">*</span></label>
                <input type="number" name="publication_year" id="publication_year" min="1" max="<?= date('Y') ?>" value="<?= htmlspecialchars($book["publication_year"]) ?>" placeholder="<?= date('Y') ?>">
                <span class="error"><?= $error["publication_year"] ?></span>
            </div>
            <button type="submit" class="submit-btn">
                ✏️ Update Book
            </button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>