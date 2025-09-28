<?php 
include "../classes/database.php";
include "../classes/book.php";

$db = new Database();
$bookObj = new Book($db);
$books = $bookObj->viewBooks();
$totalBooks = count($books);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Books</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
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
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .add-btn {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
        }
        
        .add-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(46, 204, 113, 0.4);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #f1f3f5;
        }
        
        th {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            font-weight: 600;
            color: #495057;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        
        tr:hover {
            background-color: #f8f9fa;
            transform: translateY(-1px);
            transition: all 0.3s ease;
        }
        
        .action-btn {
            padding: 8px 15px;
            margin: 0 3px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 13px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .edit-btn {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
            color: #212529;
        }
        
        .edit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
        }
        
        .delete-btn {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
        }
        
        .delete-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
        }
        
        .stats {
            background: linear-gradient(135deg, #e9ecef, #dee2e6);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 600;
            color: #495057;
            font-size: 1.1rem;
        }
        
        .empty-message {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-message h3 {
            color: #495057;
            margin-bottom: 15px;
            font-size: 1.5rem;
        }

        .alert {
            padding: 15px 20px;
            margin-bottom: 25px;
            border-radius: 8px;
            animation: slideDown 0.5s ease-out;
            font-weight: 500;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-error {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .alert-info {
            background: linear-gradient(135deg, #cce7ff, #b8daff);
            color: #004085;
            border-left: 4px solid #007bff;
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

        .delete-confirmation {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            animation: fadeIn 0.3s ease-out;
        }

        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 40px;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideUp 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-icon {
            font-size: 4rem;
            color: #dc3545;
            margin-bottom: 20px;
        }

        .modal-title {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 1.8rem;
            font-weight: 600;
        }

        .modal-text {
            color: #6c757d;
            margin-bottom: 25px;
            line-height: 1.6;
            font-size: 1.1rem;
        }

        .modal-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .modal-btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .modal-btn-danger {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
        }

        .modal-btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4);
        }

        .modal-btn-secondary {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white;
        }

        .modal-btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
        }

        .book-info {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: left;
            border-left: 4px solid #667eea;
        }

        .book-info h4 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 1.2rem;
        }

        .book-info p {
            margin: 8px 0;
            color: #495057;
            font-weight: 500;
        }

        .book-info strong {
            color: #2c3e50;
        }
        @media (max-width: 768px) {
            .container {
                margin: 10px;
                padding: 20px;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .header {
                flex-direction: column;
                gap: 15px;
                align-items: stretch;
            }
            
            .add-btn {
                text-align: center;
            }
            
            table {
                font-size: 14px;
            }
            
            th, td {
                padding: 10px 8px;
            }
            
            .action-btn {
                padding: 6px 10px;
                font-size: 12px;
            }
            
            .modal-content {
                margin: 20% auto;
                padding: 25px;
            }
            
            .modal-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📚 Library Books</h1>
        
        <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 'success'): ?>
        <div class="alert alert-success">
            ✅ Book has been successfully deleted from your library!
        </div>
        <?php endif; ?>

        <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 'error'): ?>
        <div class="alert alert-error">
            ❌ Error: <?= htmlspecialchars($_GET['message'] ?? 'Failed to delete book') ?>
        </div>
        <?php endif; ?>

        <?php if (isset($_GET['updated']) && $_GET['updated'] == 'success'): ?>
        <div class="alert alert-success">
            ✅ Book has been successfully updated!
        </div>
        <?php endif; ?>

        <?php if (isset($_GET['added']) && $_GET['added'] == 'success'): ?>
        <div class="alert alert-success">
            ✅ New book has been successfully added to your library!
        </div>
        <?php endif; ?>
        
        <div class="stats">
            📊 Total Books: <?= $totalBooks ?>
        </div>
        
        <div class="header">
            <span style="color: #6c757d; font-weight: 500;">
                Showing <?= $totalBooks ?> book<?= $totalBooks !== 1 ? 's' : '' ?>
            </span>
            <a href="addbooks.php" class="add-btn">
                ➕ Add New Book
            </a>
        </div>
        
        <?php if($totalBooks > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>📋 ID</th>
                        <th>📖 Title</th>
                        <th>✍️ Author</th>
                        <th>🎭 Genre</th>
                        <th>📅 Year</th>
                        <th>⚙️ Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($books as $index => $book): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td style="font-weight: 600; color: #2c3e50;">
                                <?= htmlspecialchars($book["title"]) ?>
                            </td>
                            <td><?= htmlspecialchars($book["author"]) ?></td>
                            <td>
                                <span style="background: #e9ecef; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500;">
                                    <?= htmlspecialchars($book["genre"]) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($book["publication_year"]) ?></td>
                            <td>
                                <a href="editbooks.php?id=<?= isset($book['id']) ? urlencode($book['id']) : $index + 1 ?>" 
                                   class="action-btn edit-btn" 
                                   title="Edit this book">
                                   ✏️ Edit
                                </a>
                                <button class="action-btn delete-btn" 
                                        onclick="confirmDelete(<?= isset($book['id']) ? $book['id'] : $index + 1 ?>, '<?= addslashes(htmlspecialchars($book['title'])) ?>', '<?= addslashes(htmlspecialchars($book['author'])) ?>', '<?= addslashes(htmlspecialchars($book['genre'])) ?>', '<?= addslashes(htmlspecialchars($book['publication_year'])) ?>')"
                                        title="Delete this book">
                                    🗑️ Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-message">
                <h3>📚 No books found</h3>
                <p>Your library is empty. Start building your collection by adding your first book!</p>
                <br>
                <a href="addbooks.php" class="add-btn">
                    ➕ Add Your First Book
                </a>
            </div>
        <?php endif; ?>
    </div>

    <div id="deleteModal" class="delete-confirmation">
        <div class="modal-content">
            <div class="modal-icon">🗑️</div>
            <h2 class="modal-title">Confirm Deletion</h2>
            <div class="modal-text">
                Are you sure you want to delete this book? This action cannot be undone.
            </div>
            <div class="book-info" id="bookInfo">
            </div>
            <div class="modal-buttons">
                <button class="modal-btn modal-btn-danger" id="confirmDeleteBtn">
                    🗑️ Yes, Delete Book
                </button>
                <button class="modal-btn modal-btn-secondary" onclick="closeDeleteModal()">
                    ❌ Cancel
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentDeleteId = null;

        function confirmDelete(id, title, author, genre, year) {
            currentDeleteId = id;
            
            document.getElementById('bookInfo').innerHTML = `
                <h4>📖 Book Details:</h4>
                <p><strong>Title:</strong> ${title}</p>
                <p><strong>Author:</strong> ${author}</p>
                <p><strong>Genre:</strong> ${genre}</p>
                <p><strong>Year:</strong> ${year}</p>
            `;
            
            document.getElementById('confirmDeleteBtn').onclick = function() {
                this.innerHTML = '⏳ Deleting...';
                this.disabled = true;
                
                window.location.href = `deletebooks.php?id=${id}`;
            };
            
            document.getElementById('deleteModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            currentDeleteId = null;
            
            const deleteBtn = document.getElementById('confirmDeleteBtn');
            deleteBtn.innerHTML = '🗑️ Yes, Delete Book';
            deleteBtn.disabled = false;
        }

        window.onclick = function(event) {
            const modal = document.getElementById('deleteModal');
            if (event.target == modal) {
                closeDeleteModal();
            }
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDeleteModal();
            }
        });

        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 500);
            });
        }, 5000);

        document.querySelectorAll('.edit-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                this.innerHTML = '⏳ Loading...';
            });
        });
    </script>
</body>
</html>