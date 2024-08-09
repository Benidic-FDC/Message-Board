<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5 mt-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <h1 class="text-center mb-4 fw-bold text-primary">Login</h1>

                    <!-- Error Messages -->
                    <?php if ($this->Session->check('Message.flash')): ?>
                        <div class="alert alert-danger">
                            <?php echo $this->Session->flash(); ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Display Validation Errors -->
                    <?php if (!empty($this->Form->validationErrors)): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($this->Form->validationErrors as $field => $messages): ?>
                                    <?php foreach ($messages as $message): ?>
                                        <li><?php echo h($message); ?></li>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo $this->Html->url(array('action' => 'login'))?>" method="post">
                        <div class="mb-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" name="data[User][email]" id="email" class="form-control form-control-lg" placeholder="Enter Your Email" required>
                        </div>
                        <div class="mb-5">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="data[User][password]" id="password" class="form-control form-control-lg" placeholder="Enter Your Password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Login</button>
                        </div>
                        <div class="text-center mt-3">
                            <p class="text-muted mb-0">Don't have an account yet? <a href="/cakephp/users/add" class="link-primary">Register</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>