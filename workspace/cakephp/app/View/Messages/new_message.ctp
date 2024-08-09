<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-header bg-primary text-white text-center rounded-top py-3">
                <h2 class="mb-0 fw-bold">Compose New Message</h2>
            </div>
            <div class="card-body p-4">
                <form action="<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'new_message')); ?>" method="post">
                    <div class="mb-4">
                        <div class="input-group input-group-lg">
                        <select name="recipient_id" id="recipient" class="form-control" placeholder="Search for a recipient">
                            <option value=""></option>
                        </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <textarea name="message" id="message" class="form-control form-control-lg" rows="6" placeholder="Type your message here..."></textarea>
                    </div>
                    <div class="d-grid mb-2">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm">Send Message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#recipient').select2({
            placeholder: 'Search for a recipient',
            minimumInputLength: 1,
            ajax: {
                url: '<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'search')); ?>',
                dataType: 'json',
                data: function(params) {
                    return {
                        q: params.term // search term
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: item.name,
                                propic: item.propic
                            };
                        })
                    };
                },
                cache: true
            },
            templateResult: formatUser,
            templateSelection: formatUserSelection
        });
    });

    function formatUser(user) {
        if (!user.id) {
            return user.text;
        }
        var defaultImagePath = '/cakephp/app/webroot/default.png';  
        var imagePath = user.propic ? '/cakephp/app/webroot/img/uploads/' + user.propic : defaultImagePath;

        var $user = $(
            '<span><img src="' + imagePath + '" class="img-flag rounded" style="width="35px; height=35px;"/> ' + user.text + '</span>'
        );
        return $user;   
    }

    function formatUserSelection(user) {
        return user.text;
    }
</script>