/**
 * Booking JavaScript
 * Xử lý logic đặt vé
 */

let bookingState = {
    movie: null,
    showDate: null,
    showtime: null,
    selectedSeats: [],
    customer: {
        name: '',
        phone: '',
        email: ''
    },
    currentStep: 1
};

const SEAT_ROWS = 10;
const SEAT_COLS = 10;

// Initialize booking page
document.addEventListener('DOMContentLoaded', function() {
    initializeBooking();
    setupEventListeners();
});

function initializeBooking() {
    const movieId = new URLSearchParams(window.location.search).get('movie_id');
    
    if (movieId) {
        loadMoviesForBooking();
        loadGenres();
    } else {
        loadMoviesForBooking();
    }
}

// Load phim cho booking
function loadMoviesForBooking() {
    fetch('/api/movies.php?action=list')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayMoviesForBooking(data.data);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Hiển thị phim cho booking
function displayMoviesForBooking(movies) {
    const list = document.getElementById('moviesListBooking');
    
    list.innerHTML = movies.map(movie => `
        <div class="movie-card-booking" data-id="${movie.id}" onclick="selectMovie(this, ${movie.id}, '${movie.title}')">
            <div class="movie-card-booking-title">${movie.title}</div>
            <div class="movie-card-booking-genre">${movie.genre_name}</div>
        </div>
    `).join('');
}

// Chọn phim
function selectMovie(elem, movieId, title) {
    document.querySelectorAll('.movie-card-booking').forEach(card => {
        card.classList.remove('selected');
    });
    
    elem.classList.add('selected');
    bookingState.movie = { id: movieId, title };
    
    // Load ngày chiếu
    loadShowDates(movieId);
}

// Load ngày chiếu
function loadShowDates(movieId) {
    fetch(`/api/showtimes.php?action=by-movie&movie_id=${movieId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const dates = [...new Set(data.data.map(s => s.show_date))];
                displayDatePicker(dates);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Hiển thị date picker
function displayDatePicker(dates) {
    const picker = document.getElementById('datePickerBooking');
    if (!picker) return;
    
    picker.innerHTML = dates.sort().map(date => `
        <button class="date-btn" data-date="${date}" onclick="selectShowDate('${date}')">
            ${formatDate(date)}
        </button>
    `).join('');
}

// Chọn ngày chiếu
function selectShowDate(date) {
    document.querySelectorAll('.date-btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    bookingState.showDate = date;
    loadShowtimes(bookingState.movie.id, date);
}

// Load suất chiếu
function loadShowtimes(movieId, date) {
    fetch(`/api/showtimes.php?action=by-movie&movie_id=${movieId}&show_date=${date}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayShowtimes(data.data);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Hiển thị danh sách suất chiếu
function displayShowtimes(showtimes) {
    const list = document.getElementById('showtimesList');
    if (!list) return;
    
    list.innerHTML = showtimes.map(showtime => `
        <button class="showtime-btn" data-id="${showtime.id}" onclick="selectShowtime(event, ${showtime.id})">
            <div class="showtime-time">${formatTime(showtime.start_time)}</div>
            <div class="showtime-price">${formatPrice(showtime.ticket_price)}</div>
        </button>
    `).join('');
}

// Chọn suất chiếu
function selectShowtime(e, showtimeId) {
    e.preventDefault();
    document.querySelectorAll('.showtime-btn').forEach(btn => btn.classList.remove('active'));
    event.target.closest('.showtime-btn').classList.add('active');
    
    bookingState.showtime = showtimeId;
    
    // Load ghế ngồi
    loadSeatsMap(showtimeId);
}

// Load bản đồ ghế
function loadSeatsMap(showtimeId) {
    fetch(`/api/bookings.php?action=get-seats&showtime_id=${showtimeId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displaySeatsMap(data.data || []);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Hiển thị bản đồ ghế
function displaySeatsMap(bookedSeats) {
    const grid = document.getElementById('seatsGrid');
    if (!grid) return;
    
    const letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
    let html = '';
    
    for (let row = 0; row < SEAT_ROWS; row++) {
        html += '<div class="seats-row">';
        for (let col = 1; col <= SEAT_COLS; col++) {
            const seatNum = letters[row] + col;
            const isBooked = bookedSeats.includes(seatNum);
            const isSelected = bookingState.selectedSeats.includes(seatNum);
            
            let seatClass = 'seat available';
            if (isBooked) seatClass = 'seat booked';
            else if (isSelected) seatClass = 'seat selected';
            
            html += `<div class="${seatClass}" data-seat="${seatNum}" onclick="toggleSeat('${seatNum}', this)">${seatNum}</div>`;
        }
        html += '</div>';
    }
    
    grid.innerHTML = html;
}

// Toggle ghế
function toggleSeat(seatNum, elem) {
    if (elem.classList.contains('booked')) return;
    
    if (elem.classList.contains('selected')) {
        elem.classList.remove('selected');
        bookingState.selectedSeats = bookingState.selectedSeats.filter(s => s !== seatNum);
    } else {
        elem.classList.add('selected');
        bookingState.selectedSeats.push(seatNum);
    }
}

// Setup event listeners
function setupEventListeners() {
    const btnNext = document.getElementById('btnNext');
    const btnPrev = document.getElementById('btnPrevious');
    const btnConfirm = document.getElementById('btnConfirm');

    if (btnNext) btnNext.addEventListener('click', nextStep);
    if (btnPrev) btnPrev.addEventListener('click', previousStep);
    if (btnConfirm) btnConfirm.addEventListener('click', confirmBooking);
}

// Next step
function nextStep() {
    // Validate current step
    if (bookingState.currentStep === 1) {
        if (!bookingState.movie) {
            alert('Vui lòng chọn phim');
            return;
        }
        bookingState.currentStep = 2;
    } else if (bookingState.currentStep === 2) {
        if (!bookingState.showtime || bookingState.selectedSeats.length === 0) {
            alert('Vui lòng chọn suất chiếu và ghế ngồi');
            return;
        }
        bookingState.currentStep = 3;
    } else if (bookingState.currentStep === 3) {
        if (!validateCustomerForm()) return;
        captureCustomerInfo();
        bookingState.currentStep = 4;
    }
    
    updateBookingUI();
}

// Previous step
function previousStep() {
    if (bookingState.currentStep > 1) {
        bookingState.currentStep--;
    }
    updateBookingUI();
}

// Update booking UI
function updateBookingUI() {
    for (let i = 1; i <= 4; i++) {
        const step = document.getElementById(`bookingStep${i}`);
        const stepIndicator = document.getElementById(`step${i}`);
        
        if (i <= bookingState.currentStep) {
            step.classList.remove('hidden');
            stepIndicator.classList.add('active');
        } else {
            step.classList.add('hidden');
            stepIndicator.classList.remove('active');
        }
    }
    
    const btnPrev = document.getElementById('btnPrevious');
    const btnNext = document.getElementById('btnNext');
    const btnConfirm = document.getElementById('btnConfirm');
    
    if (bookingState.currentStep > 1) {
        btnPrev.style.display = 'inline-block';
    } else {
        btnPrev.style.display = 'none';
    }
    
    if (bookingState.currentStep === 4) {
        btnNext.style.display = 'none';
        btnConfirm.style.display = 'inline-block';
        displayBookingSummary();
    } else {
        btnNext.style.display = 'inline-block';
        btnConfirm.style.display = 'none';
    }
}

// Validate customer form
function validateCustomerForm() {
    const name = document.getElementById('customerName');
    const phone = document.getElementById('customerPhone');
    const email = document.getElementById('customerEmail');
    
    if (!name.value || !phone.value || !email.value) {
        alert('Vui lòng điền đầy đủ thông tin');
        return false;
    }
    
    if (!/^0\d{9}$/.test(phone.value)) {
        alert('Số điện thoại không hợp lệ');
        return false;
    }
    
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
        alert('Email không hợp lệ');
        return false;
    }
    
    return true;
}

// Capture customer info
function captureCustomerInfo() {
    bookingState.customer.name = document.getElementById('customerName').value;
    bookingState.customer.phone = document.getElementById('customerPhone').value;
    bookingState.customer.email = document.getElementById('customerEmail').value;
}

// Display booking summary
function displayBookingSummary() {
    const summary = document.getElementById('bookingSummary');
    
    let totalPrice = 0;
    let seatsHtml = '';
    
    // Estimate: 120,000 VND per seat (can be updated with actual price)
    const pricePerSeat = 120000;
    totalPrice = bookingState.selectedSeats.length * pricePerSeat;
    
    seatsHtml = `<strong>Ghế: ${bookingState.selectedSeats.join(', ')}</strong>`;
    
    summary.innerHTML = `
        <div class="booking-summary">
            <div class="summary-item">
                <strong>Phim:</strong>
                <span>${bookingState.movie.title}</span>
            </div>
            <div class="summary-item">
                <strong>Ngày chiếu:</strong>
                <span>${formatDate(bookingState.showDate)}</span>
            </div>
            <div class="summary-item">
                <strong>Ghế ngồi:</strong>
                <span>${bookingState.selectedSeats.join(', ')}</span>
            </div>
            <div class="summary-item">
                <strong>Khách hàng:</strong>
                <span>${bookingState.customer.name}</span>
            </div>
            <div class="summary-item">
                <strong>SĐT:</strong>
                <span>${bookingState.customer.phone}</span>
            </div>
            <div class="summary-item">
                <strong>Email:</strong>
                <span>${bookingState.customer.email}</span>
            </div>
            <div class="summary-total">
                <strong>Tổng cộng:</strong>
                <span>${formatPrice(totalPrice)}</span>
            </div>
        </div>
    `;
}

// Confirm booking
function confirmBooking() {
    const items = bookingState.selectedSeats.map(seat => ({
        showtime_id: bookingState.showtime,
        seat_number: seat,
        price: 120000
    }));

    const payload = {
        customer_name: bookingState.customer.name,
        phone: bookingState.customer.phone,
        email: bookingState.customer.email,
        total_price: bookingState.selectedSeats.length * 120000,
        items: items
    };

    fetch('/api/bookings.php?action=create', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Đặt vé thành công! Mã hóa đơn: ' + data.booking_id);
            window.location.href = '/index.php';
        } else {
            alert('Lỗi: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra');
    });
}

// Helper functions
function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price);
}

function formatDate(date) {
    return new Date(date).toLocaleDateString('vi-VN');
}

function formatTime(time) {
    return time.substring(0, 5);
}

function loadGenres() {
    // Not needed for booking page, but kept for compatibility
}
