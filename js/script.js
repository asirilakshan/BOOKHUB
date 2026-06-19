const searchForm = document.getElementById('bookSearchForm');
const searchInput = document.getElementById('bookSearchInput');
const searchStatus = document.getElementById('bookSearchStatus');
const searchResults = document.getElementById('bookSearchResults');

const escapeHtml = (value) => String(value)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');

const coverUrl = (book) => {
    if (book.cover_i) {
        return `https://covers.openlibrary.org/b/id/${book.cover_i}-M.jpg`;
    }

    if (book.isbn && book.isbn.length > 0) {
        return `https://covers.openlibrary.org/b/isbn/${book.isbn[0]}-M.jpg`;
    }

    return 'https://placehold.co/300x420/f0f4ff/667eea?text=No+Cover';
};

const openLibraryUrl = (book) => {
    const key = book.key || '';
    return key.startsWith('/') ? `https://openlibrary.org${key}` : `https://openlibrary.org/${key}`;
};

const renderBooks = (books) => {
    searchResults.innerHTML = books.map((book) => {
        const title = book.title || 'Untitled book';
        const authors = book.author_name ? book.author_name.slice(0, 3).join(', ') : 'Unknown author';
        const year = book.first_publish_year ? `First published ${book.first_publish_year}` : 'Publish year unavailable';
        const editions = book.edition_count ? `${book.edition_count} edition${book.edition_count === 1 ? '' : 's'}` : 'Edition count unavailable';

        return `
            <div class="book-card">
                <img src="${escapeHtml(coverUrl(book))}" alt="${escapeHtml(title)}">
                <h3>${escapeHtml(title)}</h3>
                <p class="api-book-meta">${escapeHtml(authors)}</p>
                <div class="api-book-details">${escapeHtml(year)}<br>${escapeHtml(editions)}</div>
                <a class="api-book-link" href="${escapeHtml(openLibraryUrl(book))}" target="_blank" rel="noopener">View Details</a>
            </div>
        `;
    }).join('');
};

const searchBooks = async (query) => {
    const params = new URLSearchParams({
        q: query,
        limit: '12',
        fields: 'key,title,author_name,first_publish_year,cover_i,isbn,edition_count'
    });

    const response = await fetch(`https://openlibrary.org/search.json?${params.toString()}`);

    if (!response.ok) {
        throw new Error('Book API request failed');
    }

    return response.json();
};

if (searchForm) {
    searchForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        const query = searchInput.value.trim();
        if (!query) {
            return;
        }

        const submitButton = searchForm.querySelector('button');
        submitButton.disabled = true;
        searchStatus.textContent = 'Searching Open Library...';
        searchResults.innerHTML = '';

        try {
            const data = await searchBooks(query);
            const books = data.docs || [];

            if (books.length === 0) {
                searchStatus.textContent = `No books found for "${query}".`;
                return;
            }

            searchStatus.textContent = `Showing ${books.length} of ${data.numFound || books.length} results for "${query}".`;
            renderBooks(books);
        } catch (error) {
            searchStatus.textContent = 'Could not search books right now. Please check your internet connection and try again.';
        } finally {
            submitButton.disabled = false;
        }
    });
}
