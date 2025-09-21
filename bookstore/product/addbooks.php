<?php 

require_once "../classes/book.php";
$bookObj = new Book();

$book = ["title"=>"", "author"=>"", "genre"=>"", "publication_year"=>""];
$error = ["title"=>"", "author"=>"", "genre"=>"", "publication_year"=>""];

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $book["title"] = trim(htmlspecialchars($_POST["book_title"]));
    if(empty($book["title"])){
        $error["title"] = "Book title is required";
    }
    
    $book["author"] = trim(htmlspecialchars($_POST["author"]));
    if(empty($book["author"])){
        $error["author"] = "Author is required";
    }
    
    $book["genre"] = trim(htmlspecialchars($_POST["genre"]));
    if(empty($book["genre"])){
        $error["genre"] = "Genre is required";
    }
    
    $book["publication_year"] = trim(htmlspecialchars($_POST["publication_year"]));
    if(empty($book["publication_year"])){
        $error["publication_year"] = "Publication year is required";
    }else if(!is_numeric($book["publication_year"]) || $book["publication_year"] < 1){
        $error["publication_year"] = "Valid year please";
    }else if($book["publication_year"] > date('Y')){
        $error["publication_year"] = "Publication year cannot be in the future";
    }

    if(empty($error["title"]) && empty($error["author"]) && empty($error["genre"]) && empty($error["publication_year"])){
        include_once "../classes/database.php";

        $db = new Database();
        $bookObj = new Book($db);
        $bookObj->title = $book["title"];
        $bookObj->author = $book["author"];
        $bookObj->genre = $book["genre"];
        $bookObj->publication_year = $book["publication_year"];

        if($bookObj->addBook()){
            echo '<div class="success-message">Book added successfully!</div>';
            $book = ["title"=>"", "author"=>"", "genre"=>"", "publication_year"=>""];
        }else{
            echo '<div class="error-message">Failed to add book. Please try again.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
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

        .input-icon {
            position: relative;
        }

        .input-icon::before {
            content: '';
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            opacity: 0.5;
            z-index: 1;
        }

        .book-icon::before {
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z" /></svg>') no-repeat;
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
                font-size: 16px; /* Prevents zoom on iOS */
            }
        }

        /* Animation for form elements */
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
    </style>
</head>

<body>
    <div class="container">
        <h1>Add New Book</h1>
        <div class="required-note">
            Fields marked with <span class="required">*</span> are required
        </div>
        
        <form action="" method="post">
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
                📚 Add Book to Library
            </button>
        </form>
    </div>
</body>
</html>