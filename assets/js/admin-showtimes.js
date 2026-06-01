/**
 * Admin Showtimes JavaScript
 */

let currentShowtimeId = null;

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadShowtimes();
    loadMoviesForShowtime();
    setupEventListeners();
});

// Load showtimes
function loadShowtimes() {
    fetch('/api/showtimes.php?action=list')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayShowtimesTable(data.data);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Display showtimes table
function displayShowtimesTable(showtimes) {
    const tbody = document.getElementById('showtimesTableBody');
    
    tbody.innerHTML = showtimes.map(showtime => `
        <tr>
            <td>${showtime.id}</td>
            <td>${showtime.title}</td>
            <td>${showtime.room_name}</td>
            <td>${formatDate(showtime.show_date)}</td>
            <td>${formatTime(showtime.start_time)}</td>
            <td>${formatPrice(showtime.ticket_price)}</td>
            <td>
                <div class="table-actions">
                    <button class="btn btn-primary" onclick="editShowtime(${showtime.id})">Sửa</button>
                    <button class="btn btn-danger" onclick="deleteShowtime(${showtime.id})">Xóa</button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Load movies for showtime
function loadMoviesForShowtime() {
    fetch('/api/movies.php?action=list')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const movieSelect = document.getElementById('showtimeMovie');
                movieSelect.innerHTML = '<option value="">Chọn phim</option>' + 
                    data.data.map(movie => `<option value="${movie.id}">${movie.title}</option>`).join('');
            }
        })
        .catch(error => console.error('Error:', error));
}

// Setup event listeners
function setupEventListeners() {
    const btnAdd = document.getElementById('btnAddShowtime');
    const form = document.getElementById('showtimeForm');
    
    if (btnAdd) {
        btnAdd.addEventListener('click', openShowtimeModal);
    }
    
    if (form) {
        form.addEventListener('submit', saveShowtime);
    }
}

// Open showtime modal
function openShowtimeModal() {
    currentShowtimeId = null;
    document.getElementById('showtimeModalTitle').textContent = 'Thêm Suất Chiếu';
    document.getElementById('showtimeForm').reset();
    document.getElementById('showtimeId').value = '';
    document.getElementById('showtimeModal').classList.remove('hidden');
}

// Close showtime modal
function closeShowtimeModal() {
    document.getElementById('showtimeModal').classList.add('hidden');
}

// Edit showtime
function editShowtime(id) {
    // For simplicity, we'll just open modal for editing price
    // In real app, would fetch full showtime data
    alert('Chức năng sửa suất chiếu đang được phát triển');
}

// Save showtime
function saveShowtime(e) {
    e.preventDefault();
    
    const showtimeId = document.getElementById('showtimeId').value;
    const movie = document.getElementById('showtimeMovie').value;
    const room = document.getElementById('showtimeRoom').value;
    const date = document.getElementById('showtimeDate').value;
    const time = document.getElementById('showtimeTime').value;
    const price = document.getElementById('showtimePrice').value;
    
    if (!movie || !room || !date || !time || !price) {
        alert('Vui lòng điền đầy đủ thông tin');
        return;
    }
    
    const payload = {
        movie_id: parseInt(movie),
        room_name: room,
        show_date: date,
        start_time: time,
        ticket_price: parseInt(price)
    };
    
    if (showtimeId) {
        payload.id = parseInt(showtimeId);
    }
    
    const action = showtimeId ? 'update' : 'create';
    
    fetch(`/api/showtimes.php?action=${action}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            closeShowtimeModal();
            loadShowtimes();
        } else {
            alert('Lỗi: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra');
    });
}

// Delete showtime
function deleteShowtime(id) {
    if (confirm('Bạn chắc chắn muốn xóa suất chiếu này?')) {
        fetch(`/api/showtimes.php?action=delete&id=${id}`, {
            method: 'GET'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                loadShowtimes();
            } else {
                alert('Lỗi: ' + data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

// Helper functions
function formatDate(date) {
    return new Date(date).toLocaleDateString('vi-VN');
}

function formatTime(time) {
    return time.substring(0, 5);
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price);
}
