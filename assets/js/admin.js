/**
 * Admin Dashboard JavaScript
 */

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardStats();
});

// Load dashboard statistics
function loadDashboardStats() {
    // Load total movies
    fetch('/api/movies.php?action=list')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('totalMovies').textContent = data.data.length;
            }
        })
        .catch(error => console.error('Error:', error));
    
    // Load total showtimes
    fetch('/api/showtimes.php?action=list')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('totalShowtimes').textContent = data.data.length;
            }
        })
        .catch(error => console.error('Error:', error));
    
    // Load total bookings and revenue
    fetch('/api/bookings.php?action=list')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const bookings = data.data;
                document.getElementById('totalBookings').textContent = bookings.length;
                
                // Calculate total revenue (only paid bookings)
                const totalRevenue = bookings
                    .filter(b => b.status === 'Đã thanh toán')
                    .reduce((sum, b) => sum + parseFloat(b.total_price), 0);
                
                document.getElementById('totalRevenue').textContent = formatPrice(totalRevenue);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Helper function
function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price);
}
