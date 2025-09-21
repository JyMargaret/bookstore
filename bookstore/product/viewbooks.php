<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Books</title>
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
            max-width: 1200px;
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
            animation: fadeInUp 0.6s ease-out;
        }

        h1::after {
            content: '';
            display: block;
            width: 60px;
            height: 3px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            margin: 15px auto 30px;
            border-radius: 2px;
        }

        .header-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            animation: fadeInUp 0.6s ease-out 0.2s both;
        }

        .books-count {
            color: #7f8c8d;
            font-style: italic;
        }

        .add-book-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            position: relative;
            overflow: hidden;
        }

        .add-book-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .add-book-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .add-book-btn:hover::before {
            left: 100%;
        }

        .table-container {
            overflow-x: auto;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.6s ease-out 0.4s both;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        thead th {
            color: white;
            padding: 20px 15px;
            text-align: left;
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            border: none;
            position: relative;
        }

        thead th::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: rgba(255, 255, 255, 0.2);
        }

        tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f1f3f4;
        }

        tbody tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #f5f7ff 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.1);
        }

        tbody tr:nth-child(even) {
            background: #fafbfc;
        }

        tbody tr:nth-child(even):hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #f5f7ff 100%);
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        td {
            padding: 18px 15px;
            border: none;
            color: #2c3e50;
            font-size: 0.95rem;
        }

        td:first-child {
            font-weight: 600;
            color: #667eea;
        }

        td:nth-child(2) {
            font-weight: 500;
            max-width: 200px;
        }

        td:nth-child(4) {
            position: relative;
        }

        td:nth-child(4)::before {
            content: '';
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 8px;
            background: #667eea;
        }

        /* Year styling */
        td:nth-child(5) {
            font-weight: 500;
            color: #7f8c8d;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #7f8c8d;
            animation: fadeInUp 0.6s ease-out 0.4s both;
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-state h3 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-weight: 300;
        }

        .search-container {
            margin-bottom: 25px;
            position: relative;
            animation: fadeInUp 0.6s ease-out 0.3s both;
        }

        .search-input {
            width: 100%;
            padding: 15px 50px 15px 20px;
            border: 2px solid #e9ecef;
            border-radius: 25px;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #7f8c8d;
            font-size: 18px;
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                padding: 25px;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .header-controls {
                flex-direction: column;
                gap: 15px;
                align-items: stretch;
            }
            
            .add-book-btn {
                text-align: center;
                justify-content: center;
            }
            
            .table-container {
                margin: 0 -15px;
            }
            
            table {
                font-size: 0.9rem;
            }
            
            th, td {
                padding: 12px 8px;
            }
            
            td:nth-child(2) {
                max-width: 150px;
                word-wrap: break-word;
            }
        }

        tbody tr {
            opacity: 0;
            animation: fadeInUp 0.6s ease-out forwards;
        }

        tbody tr:nth-child(1) { animation-delay: 0.5s; }
        tbody tr:nth-child(2) { animation-delay: 0.6s; }
        tbody tr:nth-child(3) { animation-delay: 0.7s; }
        tbody tr:nth-child(4) { animation-delay: 0.8s; }
        tbody tr:nth-child(5) { animation-delay: 0.9s; }
        tbody tr:nth-child(n+6) { animation-delay: 1s; }

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

        .loading {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
        }

        .loading::after {
            content: '';
            display: inline-block;
            width: 30px;
            height: 30px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
            animation: fadeInUp 0.6s ease-out 0.1s both;
        }

        .stat-card {
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
            display: block;
        }

        .stat-label {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📚 Library Collection</h1>
        
        <?php 
            include "../classes/database.php";
            include "../classes/book.php";

            $db = new Database();
            $bookObj = new Book($db);
            $books = $bookObj->viewBooks();
            $totalBooks = count($books);
            
            $genres = array_column($books, 'genre');
            $uniqueGenres = array_unique($genres);
            $authors = array_column($books, 'author');
            $uniqueAuthors = array_unique($authors);
        ?>
        
        <div class="stats-container">
            <div class="stat-card">
                <span class="stat-number"><?= $totalBooks ?></span>
                <div class="stat-label">Total Books</div>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?= count($uniqueGenres) ?></span>
                <div class="stat-label">Genres</div>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?= count($uniqueAuthors) ?></span>
                <div class="stat-label">Authors</div>
            </div>
        </div>
        
        <div class="header-controls">
            <div class="books-count">
                Showing <?= $totalBooks ?> book<?= $totalBooks !== 1 ? 's' : '' ?> in your library
            </div>
            <a href="add_book.php" class="add-book-btn">
                ➕ Add New Book
            </a>
        </div>
        
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search books by title, author, or genre..." id="searchInput">
            <span class="search-icon">🔍</span>
        </div>
        
        <?php if($totalBooks > 0): ?>
        <div class="table-container">
            <table id="booksTable">
                <thead>
                    <tr>
                        <th>Book Title</th>
                        <th>Author</th>
                        <th>Genre</th>
                        <th>Publication Year</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach($books as $index => $book){
                            echo "<tr>";
                            echo "<td>" . ($index + 1) . "</td>";
                            echo "<td>" . htmlspecialchars($book["title"]) . "</td>";
                            echo "<td>" . htmlspecialchars($book["author"]) . "</td>";
                            echo "<td>" . htmlspecialchars($book["genre"]) . "</td>";
                            echo "<td>" . htmlspecialchars($book["publication_year"]) . "</td>";
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <div class="empty-state-icon">📖</div>
            <h3>No Books Found</h3>
            <p>Your library is empty. Start building your collection by adding your first book!</p>
        </div>
        <?php endif; ?>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#booksTable tbody tr');
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const cells = document.querySelectorAll('#booksTable tbody td');
                    
                    cells.forEach(cell => {
                        const originalText = cell.getAttribute('data-original') || cell.textContent;
                        if (!cell.getAttribute('data-original')) {
                            cell.setAttribute('data-original', originalText);
                        }
                        
                        if (searchTerm && originalText.toLowerCase().includes(searchTerm)) {
                            const regex = new RegExp(`(${searchTerm})`, 'gi');
                            cell.innerHTML = originalText.replace(regex, '<mark style="background: #fff3cd; padding: 2px 4px; border-radius: 3px;">$1</mark>');
                        } else {
                            cell.textContent = originalText;
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>