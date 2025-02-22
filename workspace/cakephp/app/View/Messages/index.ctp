<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <?php if ($this->Session->check('Message.flash')): ?>
                <div class="alert alert-success">
                    <?php echo $this->Session->flash(); ?>
                </div>
            <?php endif; ?>
            <div class="row mb-4 align-items-center">
                <div class="col-md-8">
                    <h1 class="display-5 fw-bold">Message List</h1>
                </div>
                <!-- <div class="col-md-4 float-end">
                    <input type="search" id="searchMessage" class="form-control" placeholder="Search Message">
                </div> -->
                <div class="col-md-4 text-md-end">
                    <?php echo $this->Html->link('New Message', array('controller' => 'messages', 'action' => 'new_message'), array('class' => 'btn btn-primary shadow-lg')); ?>
                </div>
            </div>
            <!-- <div id="searchResults">
            </div> -->

            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-primary text-white rounded-top">
                    <h5 class="my-1">Recent Messages</h5>
                </div>
                <ul class="list-group list-group-flush" style="max-height: 500px; overflow-y: auto;" id="message-list">
                    <?php echo $this->element('messages'); ?>
                </ul>
                <?php if (!empty($messages)): ?>
                <div class="card-footer bg-light text-center border-0 rounded-bottom">
                    <button class="btn btn-outline-primary rounded-pill shadow-sm my-1" id="show-more">Show More</button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    var page = 1;
    $('#show-more').click(function() {
        page++;
        $.ajax({
            url: '<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'index')); ?>' + '?page=' + page,
            type: 'GET',
            success: function(response) {
                $('#message-list').append(response);
            }
        });
    });
    
    $('#searchMessage').on('input', function() {
        var query = $(this).val();

        if (query.length >= 3) {
            $.ajax({
                url: '<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'messageSearch')); ?>',
                method: 'GET',
                data: { q: query },
                success: function(response) {
                    $('#message-list').html(response);
                },
                error: function() {
                    $('#message-list').html('<p>Error retrieving results.</p>');
                }
            });
        } else {
            $.ajax({
                url: '<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'index')); ?>',
                method: 'GET',
                success: function(response) {
                    $('#message-list').html($(response).find('#message-list').html());
                }
            });
        }
    });
});
</script>