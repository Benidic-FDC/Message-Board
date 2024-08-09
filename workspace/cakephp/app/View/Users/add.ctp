<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <h1 class="text-center mb-4 fw-bold text-primary">Registration</h1>

                    <!-- Error Messages -->
                    <?php if ($this->Session->check('Message.flash')): ?>
                        <div class="alert alert-danger">
                            <?php echo $this->Session->flash(); ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo $this->Html->url(array('action' => 'add'))?>" method="post">
                        <div class="mb-4">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control form-control-lg" placeholder="Enter Your Name" required>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="Enter Your Email" required>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="Enter Your Password" required>
                        </div>
                        <div class="mb-4">
                            <label for="cpassword" class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="cpassword" class="form-control form-control-lg" placeholder="Confirm Your Password" required>
                        </div>
                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-primary btn-lg">Register</button>
                        </div>
                        <div class="text-center mt-3">
                            <p class="text-muted mb-0">Already have an account? <a href="/cakephp/users/login" class="link-primary">Login</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Front-end Validation. Disable this to display error messages above the form -->
<script type="text/javascript">
    $(document).ready(function() {
        $('form').submit(function(event) {
            var isValid = true;
            
            // Validate Name
            var name = $('#name').val();
            if (name.length < 5 || name.length > 20) {
                isValid = false;
                alert('Name must be between 5 and 20 characters long');
            }
            
            // Validate Email
            var email = $('#email').val();
            var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (!emailPattern.test(email)) {
                isValid = false;
                alert('Please provide a valid email address');
            } else {
                // Check if email is unique
                $.ajax({
                    url: '<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'check_email')) ?>',
                    type: 'POST',
                    data: { email: email },
                    dataType: 'json',
                    async: false,
                    success: function(response) {
                        if (!response.isUnique) {
                            isValid = false;
                            alert('Email address is already in use');
                        }
                    },
                    error: function() {
                        isValid = false;
                        alert('This email is already been taken');
                    }
                });
            }

            // Validate Password
            var password = $('#password').val();
            if (password.length < 1) {
                isValid = false;
                alert('Password is required');
            }
            
            // Validate Password Confirmation
            var confirmPassword = $('#cpassword').val();
            if (password !== confirmPassword) {
                isValid = false;
                alert('Passwords do not match');
            }

            // If not valid, prevent form submission
            if (!isValid) {
                event.preventDefault();
            }
        });
    });
</script>