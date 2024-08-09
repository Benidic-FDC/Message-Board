function deleteAllMessages(senderId, recipientId) {
    Swal.fire({
        title: "Are you sure you want to delete all messages with this user?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#d333085d6",
        reverseButtons: true,
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/cakephp/messages/deleteAllMessages",
                type: 'POST',
                data: {
                    sender_id: senderId,
                    recipient_id: recipientId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Optionally, remove the messages from the view
                        $('li').each(function() {
                            var currentSenderId = $(this).find('input[name="sender_id"]').val();
                            var currentRecipientId = $(this).find('input[name="recipient_id"]').val();
                            if ((currentSenderId == senderId && currentRecipientId == recipientId) ||
                                (currentSenderId == recipientId && currentRecipientId == senderId)) {
                                $(this).remove();
                            }
                        });
                        Swal.fire({
                            title: "Deleted!",
                            text: "Messages have been deleted.",
                            icon: "success"
                        });
                    } else {
                        Swal.fire({
                            title: "Error!",
                            text: response.message,
                            icon: "error"
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: "Error!",
                        text: "Failed to delete messages.",
                        icon: "error"
                    });
                }
            });
        }
    });
}

$(document).ready(function() {
    $('[id^="message-row-"]').each(function() {
        var senderId = $('input[name=sender_id]').val();
        var recipientId = $('input[name=recipient_id]').val();
        $(this).css('cursor', 'pointer');
        $(this).on('click', function(event) {
            // Prevent click if it's on the dropdown or its children
            if ($(event.target).closest('.dropdown').length) return;
            
            var messageId = this.id.split('-').pop();
            var url = "/cakephp/messages/message_detail/" + messageId + "/" + senderId + "/" + recipientId;
            window.location.href = url;
        });
    });

});