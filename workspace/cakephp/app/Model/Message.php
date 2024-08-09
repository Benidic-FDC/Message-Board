<?php

class Message extends AppModel {
    public $belongsTo = array(
        'Sender' => array(
            'className' => 'User',
            'foreignKey' => 'sender_id'
        ),
        'Recipient' => array(
            'className' => 'User',
            'foreignKey' => 'recipient_id'
        )
    );

    public $validate = array(
        'message' => array(
            'rule' => 'notBlank',
            'message' => 'Message cannot be empty'
        ),
        'recipient' => array(
            'rule' => 'notBlank',
            'message' => 'Recipient cannot be empty'
        )
    );

    public function beforeSave($options = array()) {
        $ipAddress = env('REMOTE_ADDR');

        if (isset($this->data['Message']['id']) && $this->data['Message']['id']) {
            $this->data['Message']['modified_ip'] = $ipAddress;
        } else {
            $this->data['Message']['created_ip'] = $ipAddress;
            $this->data['Message']['modified_ip'] = $ipAddress;
        }

        return true;
    }

    public function beforeFind($query) {
        if (!isset($query['conditions'])) {
            $query['conditions'] = array();
        }
        $query['conditions']['Message.deleted'] = 0;
        return $query;
    }
}