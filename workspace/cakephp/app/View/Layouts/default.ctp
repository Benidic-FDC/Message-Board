<!Doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon -->
    <link rel="icon" href="/cakephp/app/webroot/img/favicon.png" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.2/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <?php echo $this->Html->script('message'); ?>

    <title>Message Board</title>
    <style>
        .no-arrow .dropdown-toggle::after{
            display:none;
        }
    </style>
  </head>
<body>
    <?php if ($this->Session->check('Auth.User')): ?>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <!-- <a class="navbar-brand" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-chat-left-dots-fill" viewBox="0 0 16 16">
                    <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4.414a1 1 0 0 0-.707.293L.854 15.146A.5.5 0 0 1 0 14.793zm5 4a1 1 0 1 0-2 0 1 1 0 0 0 2 0m4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                    </svg>
                </a> -->
                <h2>Message Board</h2>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <?php
                        $currentController = $this->request->params['controller'];
                        $currentAction = $this->request->params['action'];
                    ?>
                    <li class="nav-item mt-1">
                        <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'index')) ?>" class="nav-link <?php echo ($currentController === 'users' && $currentAction === 'index') ? 'active' : ''; ?>">Home</a>
                    </li>
                    <li class="nav-item mt-1">
                        <a href="<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'index')) ?>" class="nav-link <?php echo ($currentController === 'messages') ? 'active' : ''; ?>">Messages</a>
                    </li>
                    <li class="nav-item border-start mx-2"></li>
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="me-2"><?php echo h($user['name']); ?></span>
                            <img src="<?php echo !empty($user['propic']) ? $this->webroot . 'img/uploads/' . $user['propic'] : $this->webroot . 'default.png'; ?>" class="img-fluid rounded-circle border" width="35" height="35" alt="Profile Picture">
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><?php echo $this->Html->link(
                                '<i class="bi bi-person-fill"></i> Profile',
                                    array('controller' => 'users', 'action' => 'profile'),
                                    array('class' => 'dropdown-item', 'escape' => false)
                                ); ?>
                            </li>
                            <li><?php echo $this->Html->link(
                                    '<i class="bi bi-envelope-fill"></i> Account',
                                    array('controller' => 'users', 'action' => 'edit'),
                                    array('class' => 'dropdown-item mt-1', 'escape' => false)
                                ); ?>
                            </li>
                            <li><?php echo $this->Html->link(
                                'Logout',
                                array('controller' => 'users', 'action' => 'logout'),
                                array('class' => 'btn btn-sm btn-outline-primary d-block mt-2 mx-1', 'escape' => false)
                            ); ?>
                            </li>
                        </ul>
                    </li>
                </ul>
                </div>
            </div>
        </nav>
    <?php endif; ?>

    <?php echo $this->Flash->render(); ?>
    <?php echo $this->fetch('content'); ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>