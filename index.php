<?php
require_once __DIR__ . '/config/session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookHub - Rent & Sell Books Online</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<header>
    <nav class="navbar">
        <div class="logo">📚 BookHub</div>

        <ul class="nav-links">
            <li><a href="#home">Home</a></li>
            <li><a href="#popular">Popular Books</a></li>
            <li><a href="#rent">Rent</a></li>
            <li><a href="#sell">Sell</a></li>
            <li><a href="#reviews">Reviews</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>

        <?php if(isset($_SESSION['user'])): ?>
            <div style="display: flex; gap: 12px; align-items: center;">
                <a href="profile.php"><button class="btn">Profile</button></a>
                <a href="logout.php"><button class="btn btn-secondary">Logout</button></a>
            </div>
        <?php else: ?>
            <a href="login.php"><button class="btn">Login</button></a>
        <?php endif; ?>
    </nav>
</header>

<!-- Hero Section -->
<section class="hero" id="home">
    <div class="hero-content">
        <h1>Rent & Sell Books Easily</h1>
        <p>
            Discover affordable books for rent or sell your used books to thousands of readers. 
            Join our community today and start saving on books!
        </p>

        <div class="hero-buttons">
            <a href="browse_books.php"><button class="btn-primary">Browse Books</button></a>
            <a href="sell.php"><button class="btn-secondary">Sell a Book</button></a>
        </div>
    </div>

    <div class="hero-image">
        <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=500&h=600&fit=crop" alt="Books">
    </div>
</section>

<!-- API Search Section -->
<section class="api-search-section" id="book-search">
    <div class="api-search-header">
        <h2>Search Books Online</h2>
        <p>Search Open Library for book titles, authors, publish years, and covers.</p>
    </div>

    <form class="api-search-form" id="bookSearchForm">
        <input type="search" id="bookSearchInput" placeholder="Search by title, author, or ISBN" required>
        <button type="submit">Search Books</button>
    </form>

    <div class="api-search-status" id="bookSearchStatus">Try searching for "Harry Potter", "Atomic Habits", or an ISBN.</div>
    <div class="book-grid api-results-grid" id="bookSearchResults"></div>
</section>

<!-- Featured Books -->
<section class="featured" id="popular">
    <h2>📚 Popular Books</h2>
    <p style="text-align: center; color: #666; margin-bottom: 30px;">Browse hundreds of books available for rent and purchase</p>
    <div style="text-align: center;">
        <a href="browse_books.php" style="display: inline-block; padding: 15px 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: 600; transition: all 0.3s ease; text-decoration: none; font-size: 16px;">
            🔍 View All Books →
        </a>
    </div>

    <div class="book-grid" style="margin-top: 40px;">
        <div class="book-card">
            <img src="https://covers.openlibrary.org/b/id/7008042-M.jpg" alt="The Great Gatsby">
            <h3>The Great Gatsby</h3>
            <p style="color: #666;">F. Scott Fitzgerald</p>
            <p>💰 Rent: $5/week</p>
            <a href="browse_books.php" style="display: block; padding: 10px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s ease; text-align: center; text-decoration: none; margin-top: 10px;">Rent Now</a>
        </div>

        <div class="book-card">
            <img src="https://covers.openlibrary.org/b/id/8231996-M.jpg" alt="Atomic Habits">
            <h3>Atomic Habits</h3>
            <p style="color: #666;">James Clear</p>
            <p>💵 Buy: $18</p>
            <a href="browse_books.php" style="display: block; padding: 10px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s ease; text-align: center; text-decoration: none; margin-top: 10px;">View in Store</a>
        </div>

        <div class="book-card">
            <img src="https://covers.openlibrary.org/b/id/9871996-M.jpg" alt="Rich Dad Poor Dad">
            <h3>Rich Dad Poor Dad</h3>
            <p style="color: #666;">Robert Kiyosaki</p>
            <p>💰 Rent: $4/week</p>
            <a href="browse_books.php" style="display: block; padding: 10px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s ease; text-align: center; text-decoration: none; margin-top: 10px;">Rent Now</a>
        </div>

        <div class="book-card">
            <img src="https://covers.openlibrary.org/b/id/7222246-M.jpg" alt="Harry Potter">
            <h3>Harry Potter</h3>
            <p style="color: #666;">J.K. Rowling</p>
            <p>💵 Buy: $15</p>
            <a href="browse_books.php" style="display: block; padding: 10px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s ease; text-align: center; text-decoration: none; margin-top: 10px;">View in Store</a>
        </div>

    </div>
</section>

<!-- Rent Section -->
<section id="rent" class="rent-section">
    <h2>🏪 Why Rent Books From Us?</h2>

    <div class="rent-container">
        <div class="rent-box">
            <h3>📖 Student Books</h3>
            <p>Affordable textbook rentals for all subjects and courses.</p>
        </div>

        <div class="rent-box">
            <h3>📕 Bestselling Novels</h3>
            <p>Latest and greatest novels for your reading pleasure.</p>
        </div>

        <div class="rent-box">
            <h3>🔬 Research Books</h3>
            <p>Academic and reference books for your studies.</p>
        </div>

        <div class="rent-box">
            <h3>🌍 Save Money</h3>
            <p>Rent books at up to 80% cheaper than buying.</p>
        </div>

        <div class="rent-box">
            <h3>⚡ Quick Delivery</h3>
            <p>Fast shipping and easy returns within 48 hours.</p>
        </div>

        <div class="rent-box">
            <h3>🎓 No Hidden Fees</h3>
            <p>Transparent pricing with no extra charges or penalties.</p>
        </div>
    </div>
</section>

<!-- Sell Section -->
<section id="sell" class="sell-section">
    <h2>💸 Sell Your Books</h2>
    <p style="text-align: center; color: #666; margin-bottom: 40px; font-size: 16px;">
        Have books you no longer need? Sell them on BookHub and earn money easily!
    </p>

    <form class="sell-form" action="sell.php" method="POST">

        <input type="text" name="title" placeholder="Book Title" required>

        <input type="text" name="author" placeholder="Author Name" required>

        <input type="number" name="price" placeholder="Price ($)" step="0.01" required>

        <select name="type" required style="width: 100%; padding: 15px; margin-bottom: 20px; border: 2px solid #e0e0e0; border-radius: 10px; font-size: 15px; font-family: 'Poppins', sans-serif;">
            <option value="">Select Type</option>
            <option value="sell">Sell</option>
            <option value="rent">Rent</option>
        </select>

        <textarea name="description" placeholder="Book Description" rows="4" style="resize: none;"></textarea>

        <button type="submit" name="submit">List Book Now</button>

    </form>
</section>

<!-- Reviews -->
<section id="reviews" class="reviews">
    <h2>⭐ Customer Reviews</h2>

    <div class="review-container">

        <div class="review-card">
            <div style="color: #ffc107; margin-bottom: 10px; font-size: 18px;">⭐⭐⭐⭐⭐</div>
            <p>"Amazing platform for students! I saved hundreds on textbooks this semester. Highly recommended!"</p>
            <h4>- Sarah Johnson</h4>
        </div>

        <div class="review-card">
            <div style="color: #ffc107; margin-bottom: 10px; font-size: 18px;">⭐⭐⭐⭐⭐</div>
            <p>"Sold my old books quickly and got paid within days. Great experience with BookHub!"</p>
            <h4>- John Smith</h4>
        </div>

        <div class="review-card">
            <div style="color: #ffc107; margin-bottom: 10px; font-size: 18px;">⭐⭐⭐⭐⭐</div>
            <p>"Very affordable rental prices and excellent customer service. Will definitely use again!"</p>
            <h4>- Michael Brown</h4>
        </div>

    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="sell-section">
    <h2>📞 Get In Touch</h2>
    <p style="text-align: center; color: #666; margin-bottom: 40px; font-size: 16px;">
        Have questions? We'd love to hear from you. Send us a message!
    </p>

    <form class="contact-form" action="contact.php" method="POST">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <textarea name="message" placeholder="Your Message" rows="5" style="resize: none;"></textarea>
        <button type="submit" name="contact">Send Message</button>
    </form>
</section>

<!-- Footer -->
<footer>
    <p>&copy; 2024 BookHub. All rights reserved. | Rent & Sell Books Online</p>
    <p style="margin-top: 10px; opacity: 0.8;">Made with ❤️ for book lovers everywhere</p>
</footer>

<script src="js/script.js"></script>
</body>
</html>
