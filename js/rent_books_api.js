const rentForm = document.getElementById('rentApiSearchForm');
const rentInput = document.getElementById('rentApiSearchInput');
const rentStatus = document.getElementById('rentApiSearchStatus');
const rentResults = document.getElementById('rentApiResults');

const escapeHtml = (value) => String(value)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');

const getCoverUrl = (book) => {
    if (book.cover_i) {
        return `https://covers.openlibrary.org/b/id/${book.cover_i}-M.jpg`;
    }

    if (book.isbn && book.isbn.length > 0) {
        return `https://covers.openlibrary.org/b/isbn/${book.isbn[0]}-M.jpg`;
    }

    return 'https://placehold.co/300x420/f0f4ff/667eea?text=No+Cover';
};

const getBookUrl = (book) => {
    const key = book.key || '';
    return key.startsWith('/') ? `https://openlibrary.org${key}` : `https://openlibrary.org/${key}`;
};

const getRentPrice = (index) => {
    const prices = [2, 3, 4, 5, 6];
    return prices[index % prices.length];
};

const renderRentBooks = (books) => {
    rentResults.innerHTML = books.map((book, index) => {
        const title = book.title || 'Untitled book';
        const author = book.author_name ? book.author_name.slice(0, 2).join(', ') : 'Unknown author';
        const publishYear = book.first_publish_year || 'Unknown year';
        const rentPrice = getRentPrice(index);
        const bookUrl = getBookUrl(book);

        return `
            <div class="book-card">
                <img src="${escapeHtml(getCoverUrl(book))}" alt="${escapeHtml(title)}">
                <h3>${escapeHtml(title)}</h3>
                <p style="color: #666; margin-bottom: 8px;">by ${escapeHtml(author)}</p>
                <p style="color: #667eea; font-weight: 600; font-size: 16px; margin-bottom: 8px;">Published: ${escapeHtml(publishYear)}</p>
                <p style="color: #667eea; font-weight: 600; font-size: 18px; margin-bottom: 15px;">Rent estimate: $${rentPrice}/day</p>
                <form method="POST" action="rent_api_to_cart.php" style="display: flex; flex-direction: column; gap: 10px; padding: 0 20px 20px;">
                    <input type="hidden" name="title" value="${escapeHtml(title)}">
                    <input type="hidden" name="author" value="${escapeHtml(author)}">
                    <input type="hidden" name="price" value="${rentPrice}">
                    <input type="hidden" name="source_url" value="${escapeHtml(bookUrl)}">
                    <select name="rental_days" required style="padding: 10px; border: 2px solid #e0e0e0; border-radius: 8px; font-family: 'Poppins', sans-serif;">
                        <option value="1">1 Day - $${rentPrice}</option>
                        <option value="7" selected>7 Days - $${rentPrice * 7}</option>
                        <option value="14">14 Days - $${rentPrice * 14}</option>
                        <option value="30">30 Days - $${rentPrice * 30}</option>
                    </select>
                    <button type="submit" style="padding: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                        Add Rental to Cart
                    </button>
                </form>
            </div>
        `;
    }).join('');
};

const searchOpenLibrary = async (query) => {
    const params = new URLSearchParams({
        q: query,
        limit: '24',
        fields: 'key,title,author_name,first_publish_year,cover_i,isbn'
    });

    const response = await fetch(`https://openlibrary.org/search.json?${params.toString()}`);

    if (!response.ok) {
        throw new Error('Open Library request failed');
    }

    return response.json();
};

const loadRentBooks = async (query) => {
    const button = rentForm.querySelector('button');
    button.disabled = true;
    rentStatus.textContent = 'Loading rental books from Open Library...';
    rentResults.innerHTML = '';

    try {
        const data = await searchOpenLibrary(query);
        const books = data.docs || [];

        if (books.length === 0) {
            rentStatus.textContent = `No online rental books found for "${query}".`;
            return;
        }

        rentStatus.textContent = `Showing ${books.length} online rental-style books for "${query}".`;
        renderRentBooks(books);
    } catch (error) {
        rentStatus.textContent = 'Could not load online rental books. Please check your internet connection and try again.';
    } finally {
        button.disabled = false;
    }
};

if (rentForm) {
    rentForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const query = rentInput.value.trim();

        if (query) {
            loadRentBooks(query);
        }
    });

    loadRentBooks(rentInput.value.trim());
}
