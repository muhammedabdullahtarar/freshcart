$(document).ready(function () {
    $('#formSignin').on('submit', function (e) {
        e.preventDefault();

        $('.invalid-feedback').remove();
        $('.is-invalid').removeClass('is-invalid');

        var email = $('#formSigninEmail').val().trim();
        var password = $('#formSigninPassword').val().trim();

        var hasErrors = false;

        if (!email) {
            $('#formSigninEmail').addClass('is-invalid');
            $('#formSigninEmail').after('<div class="invalid-feedback">Please fill this field</div>');
            hasErrors = true;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            $('#formSigninEmail').addClass('is-invalid');
            $('#formSigninEmail').after('<div class="invalid-feedback">Invalid email address</div>');
            hasErrors = true;
        }

        if (!password) {
            $('#formSigninPassword').addClass('is-invalid');
            $('#formSigninPassword').after('<div class="invalid-feedback">Please fill this field</div>');
            hasErrors = true;
        }

        if (!hasErrors) {

            showLoader();

            $.ajax({
                url: '/api/signin',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    email: email,
                    password: password,
                }),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function (response) {

                    showMessage('success', 'Login successful! Redirecting...');

                    localStorage.setItem('token', response.data.token);
                    localStorage.setItem('user', JSON.stringify(response.data.user));

                    var userType = response.data.user.type;
                    var redirectUrl = '/';
                    
                    if (userType === 'super_admin' || userType === 'admin') {
                        redirectUrl = '/admin/dashboard';
                    }

                    setTimeout(function () {
                        window.location.href = redirectUrl;
                    }, 3000);
                },
                error: function (xhr) {
                    console.log('Signin API Error:', xhr.responseJSON);
                    hideLoader();
                    if (xhr.status === 403 && xhr.responseJSON && xhr.responseJSON.email_verified === false) {
                        showMessage('normal', 'Your email is not verified. A verification email sent.');
                    } else {
                        showMessage('error', 'Login failed. Please check your credentials.');
                    }
                }
            });
        }
    });
}); 