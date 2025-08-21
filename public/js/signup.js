$(document).ready(function () {
    $('#formSignup').on('submit', function (e) {
        e.preventDefault();

        $('.invalid-feedback').remove();
        $('.is-invalid').removeClass('is-invalid');

        var firstName = $('#formSignupfname').val().trim();
        var lastName = $('#formSignuplname').val().trim();
        var email = $('#formSignupEmail').val().trim();
        var password = $('#formSignupPassword').val().trim();

        var hasErrors = false;

        if (!firstName) {
            $('#formSignupfname').addClass('is-invalid');
            $('#formSignupfname').after('<div class="invalid-feedback">Please fill this field</div>');
            hasErrors = true;
        } else if (/\d/.test(firstName)) {
            $('#formSignupfname').addClass('is-invalid');
            $('#formSignupfname').after('<div class="invalid-feedback">No numbers allowed</div>');
            hasErrors = true;
        }

        if (!lastName) {
            $('#formSignuplname').addClass('is-invalid');
            $('#formSignuplname').after('<div class="invalid-feedback">Please fill this field</div>');
            hasErrors = true;
        } else if (/\d/.test(lastName)) {
            $('#formSignuplname').addClass('is-invalid');
            $('#formSignuplname').after('<div class="invalid-feedback">No numbers allowed</div>');
            hasErrors = true;
        }

        if (!email) {
            $('#formSignupEmail').addClass('is-invalid');
            $('#formSignupEmail').after('<div class="invalid-feedback">Please fill this field</div>');
            hasErrors = true;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            $('#formSignupEmail').addClass('is-invalid');
            $('#formSignupEmail').after('<div class="invalid-feedback">Invalid email address</div>');
            hasErrors = true;
        }

        if (!password) {
            $('#formSignupPassword').addClass('is-invalid');
            $('#formSignupPassword').after('<div class="invalid-feedback">Please fill this field</div>');
            hasErrors = true;
        } else if (password.length < 8) {
            $('#formSignupPassword').addClass('is-invalid');
            $('#formSignupPassword').after('<div class="invalid-feedback">Password too short</div>');
            hasErrors = true;
        }
        else if (!/^(?=.*[A-Za-z])(?=.*\d).{8,}$/.test(password)) {
            $('#formSignupPassword').addClass('is-invalid');
            $('#formSignupPassword').after('<div class="invalid-feedback">Password must have at least one letter and one number</div>');
            hasErrors = true;
        }

        if (!hasErrors) {
           
            showLoader();
            
            $.ajax({
                url: '/api/signup',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    name: firstName + ' ' + lastName,
                    email: email,
                    password: password,
                }),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function (response) {
                    
                    showMessage('success', 'Registration successful! A verification email has been sent. Redirecting...');
                   
                    setTimeout(function() {
                        window.location.href = '/signin';
                    }, 2000);
                },
                error: function (xhr) {
                    console.log('Signup API Error:', xhr.responseJSON);
                    
                    hideLoader();
                     
                    showMessage('error', 'Registration failed. Please check your information and try again.');
                }
            });
        }
    });
}); 