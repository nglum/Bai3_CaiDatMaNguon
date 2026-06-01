/**
 * Admin Movies JavaScript
 */

let currentMovieId = null;

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadMovies();
    loadGenres();
    setupEventListeners();
});

// Load movies
function loadMovies() {
    fetch('/api/movies.php?action=list')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayMoviesTable(data.data);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Display movies table
function displayMoviesTable(movies) {
    const tbody = document.getElementById('moviesTableBody');
    
    tbody.innerHTML = movies.map(movie => `
        <tr>
            <td>${movie.id}</td>
            <td>${movie.title}</td>
            <td>${movie.genre_name}</td>
            <td>${movie.duration} phút</td>
            <td><span class="badge ${movie.status === 'Đang chiếu' ? 'badge-success' : 'badge-warning'}">${movie.status}</span></td>
            <td>
                <div class="table-actions">
                    <button class="btn btn-primary" onclick="editMovie(${movie.id})">Sửa</button>
                    <button class="btn btn-danger" onclick="deleteMovie(${movie.id})">Xóa</button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Load genres
function loadGenres() {
    fetch('/api/genres.php?action=list')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const genreSelect = document.getElementById('movieGenre');
                genreSelect.innerHTML = '<option value="">Chọn thể loại</option>' + 
                    data.data.map(genre => `<option value="${genre.id}">${genre.name}</option>`).join('');
            }
        })
        .catch(error => console.error('Error:', error));
}

// Setup event listeners
function setupEventListeners() {
    const btnAdd = document.getElementById('btnAddMovie');
    const movieForm = document.getElementById('movieForm');
    
    if (btnAdd) {
        btnAdd.addEventListener('click', openMovieModal);
    }
    
    if (movieForm) {
        movieForm.addEventListener('submit', saveMovie);
    }
}

// Open movie modal
function openMovieModal() {
    currentMovieId = null;
    document.getElementById('movieModalTitle').textContent = 'Thêm Phim';
    document.getElementById('movieForm').reset();
    document.getElementById('movieId').value = '';
    document.getElementById('movieModal').classList.remove('hidden');
}

// Close movie modal
function closeMovieModal() {
    document.getElementById('movieModal').classList.add('hidden');
}

// Edit movie
function editMovie(id) {
    fetch(`/api/movies.php?action=get&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const movie = data.data;
                document.getElementById('movieModalTitle').textContent = 'Sửa Phim';
                document.getElementById('movieId').value = movie.id;
                document.getElementById('movieTitle').value = movie.title;
                document.getElementById('movieGenre').value = movie.genre_id;
                document.getElementById('movieDuration').value = movie.duration;
                document.getElementById('movieDescription').value = movie.description || '';
                document.getElementById('movieStatus').value = movie.status;
                
                document.getElementById('movieModal').classList.remove('hidden');
                currentMovieId = id;
            }
        })
        .catch(error => console.error('Error:', error));
}

// Save movie
function saveMovie(e) {
    e.preventDefault();
    
    const movieId = document.getElementById('movieId').value;
    const title = document.getElementById('movieTitle').value;
    const genre = document.getElementById('movieGenre').value;
    const duration = document.getElementById('movieDuration').value;
    const description = document.getElementById('movieDescription').value;
    const status = document.getElementById('movieStatus').value;
    
    if (!title || !genre || !duration) {
        alert('Vui lòng điền đầy đủ thông tin');
        return;
    }
    
    const payload = {
        title,
        genre_id: parseInt(genre),
        duration: parseInt(duration),
        description,
        status
    };
    
    if (movieId) {
        payload.id = parseInt(movieId);
    }
    
    const action = movieId ? 'update' : 'create';
    const method = movieId ? 'POST' : 'POST';
    
    fetch(`/api/movies.php?action=${action}`, {
        method: method,
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            closeMovieModal();
            loadMovies();
        } else {
            alert('Lỗi: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra');
    });
}

// Delete movie
function deleteMovie(id) {
    if (confirm('Bạn chắc chắn muốn xóa phim này?')) {
        fetch(`/api/movies.php?action=delete&id=${id}`, {
            method: 'GET'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                loadMovies();
            } else {
                alert('Lỗi: ' + data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}
