<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2>Edit Profile</h2>
                </div>
                <div class="card-body">
                    <?php
                        $imagePath = $this->webroot . 'img/uploads/' . $user['propic'];
                        $defaultImage = $this->webroot . 'default.png';
                        $imageSrc = !empty($user['propic']) ? $imagePath : $defaultImage;
                    ?>
                    <?php
                        if (isset($errors) && !empty($errors)) {
                            echo '<div class="alert alert-danger">';
                            foreach ($errors as $field => $validationErrors) {
                                foreach ($validationErrors as $error) {
                                    echo '<li>' . h($field) . ': ' . h($error) . '</li>';
                                }
                            }
                            echo '</div>';
                        }
                    ?>
                    <form action="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'edit', $user['id'])); ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="data[User][id]" value="<?php echo h($user['id']); ?>">

                        <div class="d-flex justify-content-center mb-3">
                            <img id="preview" src="<?php echo $imageSrc; ?>" width="120" height="120" alt="Default Image">
                        </div>
                        <div class="row justify-content-center mb-3">
                            <div class="col-md-10">
                                <input type="file" name="data[User][propic]" id="propic" class="form-control">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="row justify-content-center">
                                <div class="col-md-10">
                                    <label for="name">Name</label>
                                    <input type="text" name="data[User][name]" id="name" class="form-control" value="<?php echo h($user['name']); ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="row justify-content-center">
                                <div class="col-md-10">
                                    <label for="email">Email</label>
                                    <input type="email" name="data[User][email]" id="email" class="form-control" value="<?php echo h($user['email']); ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="row justify-content-center">
                                <div class="col-md-10">
                                    <label for="birthdate">Birthdate</label>
                                    <div class="input-group">
                                        <input type="text" name="data[User][birthdate]" id="birthdate" class="form-control" value="<?php echo h($user['birthdate']); ?>" required>
                                        <span class="input-group-text">
                                            <i class="bi bi-calendar-week"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="row justify-content-center">
                                <div class="col-md-10">
                                    <label for="gender">Gender</label>
                                    <div id="gender">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="data[User][gender]" id="male" value="male" <?php echo $user['gender'] === 'male' ? 'checked' : ''; ?> required>
                                            <label class="form-check-label" for="male">Male</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="data[User][gender]" id="female" value="female" <?php echo $user['gender'] === 'female' ? 'checked' : ''; ?> required>
                                            <label class="form-check-label" for="female">Female</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="row justify-content-center">
                                <div class="col-md-10">
                                    <label for="hobby">Hobby</label>
                                    <textarea name="data[User][hobby]" id="hobby" class="form-control" rows="4"><?php echo h($user['hobby']); ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-10">
                                <button type="submit" class="btn btn-primary float-end my-3">Update</button>  
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center my-4">
        <div class="col-lg-6 d-flex align-items-stretch">
            <div class="card w-100 border position-relative overflow-hidden">
                <div class="card-body p-5">
                    <h4 class="card-title">Change Password</h4>
                    <p class="card-subtitle mb-4">To change your password please confirm here</p>
                    <form action="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'change_password', $user['id'])); ?>" method="post">
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="currentPassword" name="current_password">
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="newPassword" name="new_password">
                        </div>
                        <div class="mb-4">
                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirm_password">
                        </div>
                        <button type="submit" class="btn btn-primary float-end">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
    $(document).ready(function() {
        $('#propic').on('change', function() {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
            $('#preview').attr('src', e.target.result).css({width: '120px', height: '120px'}).show();
            }
            reader.readAsDataURL(file);
        }
        });
    });

    $(function() {
        $("#birthdate").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+0"
        });
    });
    $(".input-group-text").click(function() {
        $("#birthdate").focus();
    });
</script>