<?php if (!empty($messages)): ?>
    <?php foreach ($messages as $message): ?>
        <?php
        $otherId = ($message['Message']['sender_id'] == $userId) ? $message['Message']['recipient_id'] : $message['Message']['sender_id'];
        $otherUser = $conversationUsers[$otherId]['User'];
        ?>
        <li id="message-row-<?php echo $message['Message']['id']; ?>" class="list-group-item d-flex flex-column flex-md-row align-items-start px-4 py-3 border-0 border-bottom">
            <div class="me-3">
                <img src="<?php echo !empty($otherUser['propic']) ? $this->webroot . 'img/uploads/' . $otherUser['propic'] : $this->webroot . 'default.png'; ?>" alt="Profile Image" class="rounded-circle" width="50" height="50">
            </div>
            <div class="w-100">
                <div class="d-flex justify-content-between mb-2">
                    <input type="hidden" name="sender_id" value="<?php echo $message['Message']['sender_id']; ?>">
                    <input type="hidden" name="recipient_id" value="<?php echo $message['Message']['recipient_id']; ?>">
                    <h6 class="fw-bold text-dark mb-0"><?php echo h($otherUser['name']); ?></h6>
                    <div class="d-flex align-items-center">
                        <small class="text-muted me-2"><?php echo h(date('Y/m/d H:i', strtotime($message['Message']['created']))); ?></small>
                        <div class="dropdown">
                            <button class="btn btn-link text-dark p-0 ms-2" type="button" id="dropdownMenuButton<?php echo $message['Message']['id']; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton<?php echo $message['Message']['id']; ?>">
                                <li>
                                <a class="dropdown-item text-danger" href="#" onclick="deleteAllMessages(<?php echo $message['Message']['sender_id']; ?>, <?php echo $message['Message']['recipient_id']; ?>)">
                                    <i class="bi bi-trash-fill"></i> Delete message
                                </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <p class="mb-1 text-muted" style="margin-top: -9px;"><?php echo h($message['Message']['message']); ?></p>
            </div>
        </li>
    <?php endforeach; ?>
<?php else: ?>
    <p class="text-muted text-center mt-2">No messages found.</p>
<?php endif; ?>