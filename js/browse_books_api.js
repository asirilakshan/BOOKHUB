const browseForm = document.getElementById('browseApiSearchForm');
const browseInput = document.getElementById('browseApiSearchInput');
const browseStatus = document.getElementById('browseApiSearchStatus');
const browseResults = document.getElementById('browseApiResults');

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

const getBuyPrice = (index) => {
    const prices = [8, 10, 12, 15, 18, 20];
    return prices[index % prices.length];
};

const renderApiBooks = (books) => {
    browseResults.innerHTML = books.map((book, index) => {
        const title = book.title || 'Untitled book';
        const author = book.author_name ? book.author_name.slice(0, 2).join(', ') : 'Unknown author';
        const publishYear = book.first_publish_year || 'Unknown year';
        const editionCount = book.edition_count || 0;
        const buyPrice = getBuyPrice(index);

        return `
            <div class="book-card">
                <img src="${escapeHtml(getCoverUrl(book))}" alt="${escapeHtml(title)}">
                <h3>${escapeHtml(title)}</h3>
                <p style="color: #666; margin-bottom: 8px;">by ${escapeHtml(author)}</p>
                <p style="color: #667eea; font-weight: 600; font-size: 16px; margin-bottom: 8px;">Published: ${escapeHtml(publishYear)}</p>
                <p style="color: #666; margin-bottom: 15px;">${escapeHtml(editionCount)} edition${editionCount === 1 ? '' : 's'}</p>
                <p style="color: #667eea; font-weight: 600; font-size: 18px; margin-bottom: 15px;">Buy: $${buyPrice}</p>
                <form method="POST" action="browse_api_to_cart.php" style="display: flex; flex-direction: column; gap: 10px; padding: 0 20px 20px;">
                    <input type="hidden" name="title" value="${escapeHtml(title)}">
                    <input type="hidden" name="author" value="${escapeHtml(author)}">
                    <input type="hidden" name="price" value="${buyPrice}">
                    <input type="hidden" name="source_url" value="${escapeHtml(getBookUrl(book))}">
                    <button type="submit" style="padding: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                        Add to Cart
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
        fields: 'key,title,author_name,first_publish_year,cover_i,isbn,edition_count'
    });

    const response = await fetch(`https://openlibrary.org/search.json?${params.toString()}`);

    if (!response.ok) {
        throw new Error('Open Library request failed');
    }

    return response.json();
};

const loadApiBooks = async (query) => {
    const button = browseForm.querySelector('button');
    button.disabled = true;
    browseStatus.textContent = 'Loading books from Open Library...';
    browseResults.innerHTML = '';

    try {
        const data = await searchOpenLibrary(query);
        const books = data.docs || [];

        if (books.length === 0) {
            browseStatus.textContent = `No online books found for "${query}".`;
            return;
        }

        browseStatus.textContent = `Showing ${books.length} online books for "${query}".`;
        renderApiBooks(books);
    } catch (error) {
        browseStatus.textContent = 'Could not load online books. Please check your internet connection and try again.';
    } finally {
        button.disabled = false;
    }
};

if (browseForm) {
    browseForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const query = browseInput.value.trim();

        if (query) {
            loadApiBooks(query);
        }
    });

    loadApiBooks(browseInput.value.trim());
}
