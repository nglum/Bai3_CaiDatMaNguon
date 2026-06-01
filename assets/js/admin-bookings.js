/**
 * Admin Bookings JavaScript
 */

let currentBookingId = null;

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadBookings();
    setupEventListeners();
});

// Load bookings
function loadBookings() {
    const statusFilter = document.getElementById('statusFilter');
    const status = statusFilter ? statusFilter.value : '';
    
    let url = '/api/bookings.php?action=list';
    if (status) {
        url += `&status=${encodeURIComponent(status)}`;
    }
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayBookingsTable(data.data);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Display bookings table
function displayBookingsTable(bookings) {
    const tbody = document.getElementById('bookingsTableBody');
    
    tbody.innerHTML = bookings.map(booking => `
        <tr>
            <td>#${booking.id}</td>
            <td>${booking.customer_name}</td>
            <td>${booking.phone}</td>
            <td>${booking.email}</td>
            <td>${formatPrice(booking.total_price)}</td>
            <td>
                <span class="badge ${getStatusBadgeClass(booking.status)}">
                    ${booking.status}
                </span>
            </td>
            <td>${formatDateTime(booking.booking_date)}</td>
            <td>
                <div class="table-actions">
                    <button class="btn btn-primary" onclick="viewBookingDetail(${booking.id})">Chi tiết</button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Setup event listeners
function setupEventListeners() {
    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
        statusFilter.addEventListener('change', loadBookings);
    }
}

// View booking detail
function viewBookingDetail(id) {
    fetch(`/api/bookings.php?action=get&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const booking = data.data;
                currentBookingId = id;
                
                document.getElementById('detailBookingId').textContent = booking.id;
                document.getElementById('detailCustomerName').textContent = booking.customer_name;
                document.getElementById('detailPhone').textContent = booking.phone;
                document.getElementById('detailEmail').textContent = booking.email;
                document.getElementById('detailTotal').textContent = formatPrice(booking.total_price);
                document.getElementById('newStatus').value = booking.status;
                
                // Display tickets
                const ticketsBody = document.getElementById('detailTickets');
                ticketsBody.innerHTML = booking.items.map(item => `
                    <tr>
                        <td>${item.title}</td>
                        <td>${item.room_name}</td>
                        <td>${formatDate(item.show_date)} - ${formatTime(item.start_time)}</td>
                        <td><strong>${item.seat_number}</strong></td>
                        <td>${formatPrice(item.price)}</td>
                    </tr>
                `).join('');
                
                document.getElementById('bookingDetailModal').classList.remove('hidden');
            }
        })
        .catch(error => console.error('Error:', error));
}

// Close booking detail modal
function closeBookingDetailModal() {
    document.getElementById('bookingDetailModal').classList.add('hidden');
}

// Update booking status
function updateBookingStatus() {
    const newStatus = document.getElementById('newStatus').value;
    
    if (!newStatus) {
        alert('Vui lòng chọn trạng thái');
        return;
    }
    
    fetch('/api/bookings.php?action=update-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id: currentBookingId,
            status: newStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            closeBookingDetailModal();
            loadBookings();
        } else {
            alert('Lỗi: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra');
    });
}

// Get status badge class
function getStatusBadgeClass(status) {
    switch(status) {
        case 'Đã thanh toán':
            return 'badge-success';
        case 'Chờ thanh toán':
            return 'badge-warning';
        case 'Đã hủy':
            return 'badge-danger';
        default:
            return 'badge-warning';
    }
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

function formatDateTime(datetime) {
    return new Date(datetime).toLocaleString('vi-VN');
}
