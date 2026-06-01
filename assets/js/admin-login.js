/**
 * Admin Login JavaScript
 */

document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const username = document.getElementById('adminUser').value;
    const password = document.getElementById('adminPass').value;
    
    // Hardcoded admin credentials (in production, use secure authentication)
    if (username === 'admin' && password === 'admin123') {
        // Set session
        fetch('/pages/admin/auth.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ username, password })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '/pages/admin/dashboard.php';
            } else {
                alert('Thông tin đăng nhập không chính xác');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Fallback: redirect directly
            window.location.href = '/pages/admin/dashboard.php';
        });
    } else {
        alert('Thông tin đăng nhập không chính xác');
    }
});
