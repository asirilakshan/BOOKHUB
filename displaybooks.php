<?php
include 'config/db.php';

$sql = "SELECT * FROM books";
$result = $conn->query($sql);

if($result && $result->num_rows > 0){
    while($row = $result->fetch_assoc()){
?>

<div class="book">
    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
    <p><?php echo htmlspecialchars($row['author']); ?></p>
    <p>$<?php echo htmlspecialchars($row['price']); ?></p>
    <p><?php echo htmlspecialchars($row['book_type']); ?></p>
</div>

<?php
    }
} else {
    echo "<p>No books found</p>";
}
?>