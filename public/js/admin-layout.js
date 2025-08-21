$(document).ready(function() {
    checkAuth();
    showUserInfo();
});

function showUserInfo() {
    var userData = localStorage.getItem('user');
    if (!userData) return;

    var user = JSON.parse(userData);
    var $userType = $('#userType');
    
    $('#userName').text(user.name);
    $('#userEmail').text(user.email);

    if (user.type === 'admin') {
        $userType.text('Admin').addClass('bg-primary');
    } else if (user.type === 'super_admin') {
        $userType.text('👑 Super Admin').addClass('bg-warning');
    }
}

function logout() {
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to logout?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, logout!',
        cancelButtonText: 'Cancel'
    }).then(function(result) {
        if (result.isConfirmed) {
            var token = localStorage.getItem('token');
            
            $.ajax({
                url: '/api/logout',
                method: 'POST',
                headers: {
                    'Authorization': token ? 'Bearer ' + token : '',
                    'Accept': 'application/json'
                },
                success: function() {
                    clearAndRedirect();
                },
                error: function() {
                    clearAndRedirect();
                }
            });
        }
    });
}

function clearAndRedirect() {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    window.location.href = adminRoutes.signin;
}