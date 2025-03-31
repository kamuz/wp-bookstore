const loadBooksByRestButton = document.getElementById('bookstore-load-books');
if (loadBooksByRestButton) {
    loadBooksByRestButton.addEventListener('click', function() {
        const allBooks = new wp.api.collections.Books();
        allBooks.fetch().done(
            function(books) {
                const textarea = document.getElementById('bookstore-booklist');
                books.forEach(function(book) {
                    textarea.value += book.title.rendered + ',' + book.link + ',\n';
                })
            }
        )
    });
}

const fetchBooksByRestButton = document.getElementById('bookstore-fetch-books');
if (fetchBooksByRestButton) {
    fetchBooksByRestButton.addEventListener('click', function() {
        wp.apiFetch({ path: '/wp/v2/posts' }).then(posts => {
            console.log(posts);
        });
        wp.apiFetch({ path: '/wp/v2/books' }).then(
            (books) => {
                const textarea = document.getElementById('bookstore-booklist');
                books.map(
                    (book) => {
                        textarea.value += book.title.rendered + ',' + book.link + ',\n';
                    }
                )
            }
        )
    });
}

const submitBookButton = document.getElementById('bookstore-submit-book');
if (submitBookButton) {
    submitBookButton.addEventListener('click', () => {
        const title = document.getElementById('bookstore-book-title').value;
        const content = document.getElementById('bookstore-book-content').value;
        wp.apiFetch({
            path: '/wp/v2/books/',
            method: 'POST',
            data: {
                title: title,
                content: content,
                status: 'publish'
            },
        }).then((result) => {
            alert('Book saved!');
        });
    });
}

function updateBook() {
    const id = document.getElementById('bookstore-book-id').value;
    const newTitle = document.getElementById('bookstore-book-title').value;
    const newContent = document.getElementById('bookstore-book-content').value;

    wp.apiFetch({
        path: '/wp/v2/books/' + id,
        method: 'POST',
        data: {
            title: newTitle,
            content: newContent
        },
    }).then((result) => {
        alert('Book Updated!');
    });
}

function deleteBook() {
    const id = document.getElementById('bookstore-book-id').value;

    wp.apiFetch({
        path: '/wp/v2/books/' + id,
        method: 'DELTE'
    }).then((result) => {
        alert('Book Deleted!');
    });
}