<div class="container-fluid mt-5">
    <!-- Welcome Banner -->
    <div class="jumbotron text-center rounded">
        <h1 class="display-4">Welcome to the Message Board!</h1>
        <p class="lead">Hello, <?php echo h($currentUser['name']); ?>! We're glad to have you here.</p>
        <p class="mb-0">Explore the latest conversations, connect with your friends, and stay updated!</p>
    </div>

    <div class="row mt-5">
        <div class="col-md-12">
            <div class="text-center">
                <a href="<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'index')); ?>" class="btn btn-primary me-4">View Messages</a>
                <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'profile')); ?>" class="btn btn-success ms-4">View Profile</a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="text-center">
            </div>
        </div>
    </div>

    <!-- Quick Links
    <div class="row mt-5">
        <div class="col-md-12 text-center">
            <div class="btn-group" role="group" aria-label="Quick Links">
                <a href="<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'new_message')); ?>" class="btn btn-outline-primary">Compose Message</a>
                <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'edit')); ?>" class="btn btn-outline-primary">Edit Profile</a>
                <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'index')); ?>" class="btn btn-outline-primary">Users List</a>
            </div>
        </div>
    </div> -->
</div>


<!-- <table>
    <tr>
        <th>Id</th>
        <th>Title</th>
        <th>Edit</th>
        <th>Created</th>
    </tr>
    
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?php echo $user['User']['id']; ?></td>
        <td>
            <?php echo $user['User']['username']
            ?>
        </td>
        <td>
            <?php
                echo $this->Form->postLink(
                    'Delete',
                    array('action' => 'delete', $user['User']['id']),
                    array('confirm' => 'Are you sure?')
                );
            ?>
            <?php
                echo $this->Html->link(
                    'Edit',
                    array('action' => 'edit', $user['User']['id'])
                );
            ?>
        </td>
        <td><?php echo $user['User']['created']; ?></td>
    </tr>
    <?php endforeach; ?>

    <?php echo $this->Paginator->numbers(); ?>
    
    <?php unset($users); ?>

    
</table> -->