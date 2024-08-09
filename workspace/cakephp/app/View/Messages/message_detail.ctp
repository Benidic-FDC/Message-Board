<div class="container-fluid d-flex justify-content-center align-items-center mt-5">
    <div class="d-flex flex-column bg-white rounded shadow-lg" style="height: 80vh; width: 35%; display: flex; flex-direction: column;">
        <?php if ($message['Message']['recipient_id'] == $userId): ?>
            <?php $imageSrc = !empty($sender['User']['propic']) ? $this->webroot . 'img/uploads/' . $sender['User']['propic'] : $this->webroot . 'default.png'; ?>
        <?php else: ?>
            <?php $imageSrc = !empty($recipient['User']['propic']) ? $this->webroot . 'img/uploads/' . $recipient['User']['propic'] : $this->webroot . 'default.png'; ?>
        <?php endif; ?>
        <!-- Chat Header -->
        <div class="d-flex align-items-center p-3 bg-primary text-white">
            <a href="<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'index')); ?>" class="text-white">
                <i class="fa-solid fa-arrow-left me-3"></i>
            </a>
            <img src="<?php echo $imageSrc; ?>" alt="User Avatar" class="rounded-circle me-2" width="35" height="35">
            <div><?php echo h($message['Message']['recipient_id'] == $userId ? $sender['User']['name'] : $recipient['User']['name']); ?></div>
        </div>

        <!-- Chat Messages -->
        <div class="overflow-auto bg-grey flex-grow-1">
            <div class="p-3">
                <?php foreach ($messages as $message): ?>
                    <?php if ($message['Message']['recipient_id'] == $userId): ?>
                        <div class="d-flex mb-4" id="message-row-<?php echo $message['Message']['id']; ?>">
                            <div class="dropdown">
                                <img src="<?php echo $imageSrc; ?>" alt="User Avatar" width="35" height="35" class="rounded-circle dropdown-toggle" id="dropdownMenu<?php echo $message['Message']['id']; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu<?php echo $message['Message']['id']; ?>">
                                    <li><a class="dropdown-item text-danger delete-message" href="#" data-message-id="<?php echo $message['Message']['id']; ?>"><i class="bi bi-trash-fill"></i> Delete Message</a></li>
                                </ul>
                            </div>
                            <div class="message-container">
                                <div class="bg-light text-dark p-2 rounded-3 border border-light mx-2">
                                <p class="mb-0">
                                    <?php
                                    $messageText = h($message['Message']['message']);
                                    $maxLength = 30;
                                    $truncatedMessage = mb_strimwidth($messageText, 0, $maxLength, '...');
                                    echo $truncatedMessage;
                                    ?>
                                </p>
                                </div>
                                <span class="text-muted small message-time"><?php echo date('h:i A', strtotime($message['Message']['created'])); ?></span>
                            </div>
                        </div>
                    <?php else:?>
                        <div class="d-flex justify-content-end mb-4" id="message-row-<?php echo $message['Message']['id']; ?>">
                            <div class="message-container">
                                <div class="bg-primary text-light p-2 rounded-3 border border-light mx-2">
                                <p class="mb-0">
                                    <?php
                                    $messageText = h($message['Message']['message']);
                                    $maxLength = 30;
                                    $truncatedMessage = mb_strimwidth($messageText, 0, $maxLength, '...');
                                    echo $truncatedMessage;
                                    ?>
                                </p>
                                </div>
                                <span class="text-muted small message-time float-end"><?php echo date('h:i A', strtotime($message['Message']['created'])); ?></span>
                            </div>
                            <div class="dropdown">
                                <img src="<?php echo !empty($you['User']['propic']) ? $this->webroot . 'img/uploads/' . $you['User']['propic'] : $this->webroot . 'default.png'; ?>" alt="User Avatar" height="35" class="rounded-circle dropdown-toggle" id="dropdownMenu<?php echo $message['Message']['id']; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu<?php echo $message['Message']['id']; ?>">
                                    <li><a class="dropdown-item text-danger delete-message" href="#" data-message-id="<?php echo $message['Message']['id']; ?>"><i class="bi bi-trash-fill"></i> Delete Message</a></li>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php if (count($messages) >= 10): ?>
                <div class="bg-light text-center border-0 rounded-bottom p-1">
                    <button id="show-more-msg" class="btn btn-outline-primary rounded-pill shadow-sm my-1">Show More</button>
                </div>
            <?php endif; ?>
        </div>
        <input type="hidden" name="sender_id" value="<?php echo $message['Message']['sender_id']; ?>">
        <input type="hidden" name="recipient_id" value="<?php echo $message['Message']['recipient_id']; ?>">
        <!-- Chat Input -->
        <div class="d-flex align-items-center px-2 py-3 bg-light border-top">
            <input type="text" id="message-input" name="message" placeholder="Type a message..." class="form-control me-2 py-2">
            <button type="button" id="send-button" class="btn btn-primary rounded-circle d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
                <i class="bi bi-send-fill fs-5"></i>
            </button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#send-button').on('click', function() {
            var message = $('#message-input').val();
            var recipientId = $('input[name=recipient_id]').val();
            var senderId = $('input[name=sender_id]').val();;
            console.log(recipientId);
            console.log(senderId);

            if (message.trim() === '') {
                alert('Please enter a message.');
                return;
            }

            $.ajax({
                url: "/cakephp/messages/replyMessage",
                type: 'POST',
                data: {
                    'message': message,
                    'recipient_id': recipientId,
                    'sender_id' : senderId
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.status == 'success') {
                        console.log(result.status);
                        $('#message-input').val('');
                            var newMessageHtml = `
                            <div class="d-flex mb-4">
                                <img src="https://via.placeholder.com/35" alt="User Avatar" width="35" height="35" class="rounded-circle me-2">
                                <div class="bg-light text-dark p-2 rounded-3 border border-light position-relative" style="max-width: 70%;">
                                    <p class="mb-0">${result.message}</p>
                                    <span class="text-muted small position-absolute" style="left: 10px; bottom: -20px;">${result.timestamp}</span>
                                </div>
                            </div>
                        `;
                        $('.overflow-auto').append(newMessageHtml);
                    } else {
                        console.log("Consoleni"+ result.status); 
                        // alert(result.message);
                    }
                    window.location.reload();

                },
                error: function() {
                    alert('Failed to send message.');
                }
            });
        });
    });

    $(document).on('click', '.delete-message', function(e) {
        e.preventDefault();
        const messageId = $(this).data('message-id');
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            reverseButtons: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteMessage(messageId);
            }
        });
    });

    function deleteMessage(messageId) {
        $.ajax({
            url: "/cakephp/messages/delete_message",
            type: 'POST',
            data: { id: messageId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#message-row-' + messageId).fadeOut(500, function() {
                        $(this).remove();
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'An error occurred while processing your request.', 'error');
            }
        });
    }
</script>