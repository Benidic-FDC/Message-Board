<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <?php if ($this->Session->check('Message.flash')): ?>
                <div class="alert alert-success">
                    <?php echo $this->Session->flash(); ?>
                </div>
            <?php endif; ?>
            <h2 class="text-center mb-4">User Profile</h2>
            <div class="card border-0 shadow-lg rounded-4 mb-4">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <img src="<?php echo !empty($user['propic']) ? $this->webroot . 'img/uploads/' . $user['propic'] : $this->webroot . 'default.png'; ?>" class="img-fluid rounded-circle border" width="150" height="150" alt="Profile Picture">
                    </div>

                    <h2 class="card-title mb-3">
                        <?php 
                        echo h($user['name']);
                        $birthdate = new DateTime($user['birthdate']);
                        $now = new DateTime();
                        $age = $now->diff($birthdate)->y;
                        echo ' <span class="text-muted">' . h($age) . '</span>';
                        ?>
                    </h2>
                    
                    <p class="card-text"><strong>Email:</strong> <?php echo h($user['email']); ?></p>
                    <p class="card-text"><strong>Gender:</strong> <?php echo h(ucfirst($user['gender'])); ?></p>
                    <p class="card-text"><strong>Birthdate:</strong> <?php 
                        $birthdate = new DateTime($user['birthdate']);
                        echo h($birthdate->format('F j, Y'));
                    ?></p>
                    <p class="card-text"><strong>Joined:</strong> <?php 
                        $joined = new DateTime($user['created']);
                        echo h($joined->format('F j, Y \a\t g:i A'));
                    ?></p>
                    <p class="card-text"><strong>Last Login:</strong> <?php 
                        $login = new DateTime($user['login_at']);
                        echo h($login->format('F j, Y \a\t g:i A'));
                    ?></p>
                    <p class="card-text mt-3"><strong>Hobby:</strong></p>
                    <p class="card-text text-justify"><?php echo h($user['hobby']); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
