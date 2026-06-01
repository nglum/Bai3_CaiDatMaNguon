/**
 * Main JavaScript
 * Xử lý logic trang chủ
 */

// Load danh sách phim
function loadMovies(filters = {}) {
    let url = '/api/movies.php?action=list';
    
    if (filters.genre_id) url += `&genre_id=${filters.genre_id}`;
    if (filters.search) url += `&search=${encodeURIComponent(filters.search)}`;
    if (filters.status) url += `&status=${encodeURIComponent(filters.status)}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayMovies(data.data);
            } else {
                console.error('Error:', data.error);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Hiển thị danh sách phim
function displayMovies(movies) {
    const grid = document.getElementById('moviesGrid');
    
    if (!movies || movies.length === 0) {
        grid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; padding: 2rem;">Không tìm thấy phim</p>';
        return;
    }

    grid.innerHTML = movies.map(movie => `
        <div class="movie-card" onclick="goToBooking(${movie.id})">
            <div class="movie-image">🎬</div>
            <div class="movie-info">
                <div class="movie-title">${movie.title}</div>
                <div class="movie-genre">${movie.genre_name}</div>
                <div class="movie-duration">⏱️ ${movie.duration} phút</div>
                <div class="movie-description">${movie.description ? movie.description.substring(0, 80) + '...' : 'Không có mô tả'}</div>
            </div>
        </div>
    `).join('');
}

// Load danh sách thể loại
function loadGenres() {
    fetch('/api/genres.php?action=list')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayGenreFilter(data.data);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Hiển thị filter thể loại
function displayGenreFilter(genres) {
    const filter = document.getElementById('genreFilter');
    if (!filter) return;
    
    filter.innerHTML = '<option value="">Tất cả thể loại</option>' + 
        genres.map(genre => `<option value="${genre.id}">${genre.name}</option>`).join('');
}

// Xử lý tìm kiếm
function setupSearchFilters() {
    const searchInput = document.getElementById('searchInput');
    const genreFilter = document.getElementById('genreFilter');
    const dateFilter = document.getElementById('dateFilter');

    [searchInput, genreFilter, dateFilter].forEach(elem => {
        if (elem) {
            elem.addEventListener('change', applyFilters);
            elem.addEventListener('input', debounce(applyFilters, 300));
        }
    });
}

// Áp dụng filter
function applyFilters() {
    const filters = {};
    
    const searchInput = document.getElementById('searchInput');
    const genreFilter = document.getElementById('genreFilter');
    const dateFilter = document.getElementById('dateFilter');

    if (searchInput && searchInput.value) filters.search = searchInput.value;
    if (genreFilter && genreFilter.value) filters.genre_id = genreFilter.value;
    
    loadMovies(filters);
}

// Debounce function
function debounce(func, delay) {
    let timeoutId;
    return function(...args) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => func(...args), delay);
    };
}

// Chuyển đến trang đặt vé
function goToBooking(movieId) {
    window.location.href = `/pages/booking.php?movie_id=${movieId}`;
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadMovies();
    loadGenres();
    setupSearchFilters();
});
