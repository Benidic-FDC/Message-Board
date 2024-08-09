<?php 
App::uses('AppController', 'Controller');

class MessagesController extends AppController {
    public $uses = array('Message', 'User');
    public $components = array('Paginator');

    public function index() {
        $userId = $this->Auth->user('id');
        $this->Message->recursive = 0;
    
        $page = isset($this->request->query['page']) ? (int)$this->request->query['page'] : 1;
    
        // Fetch all relevant messages for the current user
        $this->Paginator->settings = array(
            'joins' => array(
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Message.recipient_id = User.id'
                    )
                )
            ),
            'conditions' => array(
                'OR' => array(
                    'Message.sender_id' => $userId,
                    'Message.recipient_id' => $userId
                )
            ),
            'fields' => array('Message.*', 'User.name', 'User.propic'),
            'order' => array('Message.created' => 'DESC'),
            'limit' => 1,
            'page' => $page
        );
    
        $allMessages = $this->Paginator->paginate('Message');
    
        $conversations = array();
        foreach ($allMessages as $message) {
            $otherId = ($message['Message']['sender_id'] == $userId) ? $message['Message']['recipient_id'] : $message['Message']['sender_id'];
            $conversationKey = min($message['Message']['sender_id'], $message['Message']['recipient_id']) . '-' . max($message['Message']['sender_id'], $message['Message']['recipient_id']);
    
            if (!isset($conversations[$conversationKey]) || $message['Message']['created'] > $conversations[$conversationKey]['Message']['created']) {
                $conversations[$conversationKey] = $message;
            }
        }
 
        $messages = array_values($conversations);
    
        $conversationUsers = array();
        foreach ($messages as &$message) {
            $otherId = ($message['Message']['sender_id'] == $userId) ? $message['Message']['recipient_id'] : $message['Message']['sender_id'];
            $conversationUsers[$otherId] = $this->User->findById($otherId);
        }
    
        $this->set(compact('messages', 'userId', 'conversationUsers'));
    
        if ($this->request->is('ajax')) {
            $this->render('/Elements/messages', 'ajax');
        }
    }
    

    public function message_detail($id,$sender_id,$recipient_id) {
        $userId = $this->Auth->user('id');
        
        $message = $this->Message->findById($id);
        $messages = $this->Message->find('all', array(
            'conditions' => array(
                'OR' => array(
                    array(
                        'Message.sender_id' => $sender_id,
                        'Message.recipient_id' => $recipient_id
                    ),
                    array(
                        'Message.sender_id' => $recipient_id,
                        'Message.recipient_id' => $sender_id
                    )
                )
            ),
            'order' => array('Message.created' => 'ASC')
        ));
        debug($messages);

        $you = $this->User->findById($userId);
        $sender = $this->User->findById($sender_id);
        $recipient = $this->User->findById($recipient_id);
        
        $this->set(compact('message', 'messages', 'sender', 'recipient', 'userId', 'you'));  
    }

    public function new_message() {
        if ($this->request->is('post')) {
            $recipientId = $this->request->data['recipient_id'];
            $recipient = $this->User->findById($recipientId);
            if ($recipient) {
                $this->request->data['Message']['recipient_id'] = $recipient['User']['id'];
                $this->request->data['Message']['sender_id'] = $this->Auth->user('id');
                $this->request->data['Message']['message'] = $this->request->data['message'];

                if ($this->Message->save($this->request->data)) {
                    $this->Flash->success(__('The message has been sent.'));
                    return $this->redirect(array('action' => 'index'));
                }
                $this->Flash->error(__('Unable to send your message.'));
            } else {
                $this->Flash->error(__('Recipient not found.'));
            }
        }
    }

    public function search() {
        $this->autoRender = false;
        $this->response->type('json');

        $term = $this->request->query('q');
        $conditions = array(
            'User.name LIKE' => '%' . $term . '%'
        );
        $users = $this->User->find('all', array(
            'conditions' => $conditions,
            'fields' => array('User.id', 'User.name', 'User.propic')
        ));

        $results = array();
        foreach ($users as $user) {
            $results[] = array(
                'id' => $user['User']['id'],
                'name' => $user['User']['name'],
                'propic' => $user['User']['propic']
            );
        }

        echo json_encode($results);
    }

    public function delete_message() {
        $this->autoRender = false;
    
        if ($this->request->is('ajax') && $this->request->is('post')) {
            $messageId = $this->request->data('id');
    
            $this->Message->id = $messageId;
            $message = $this->Message->findById($messageId);
    
            if ($message) {
                // Perform a soft delete by setting the 'deleted' field to 1
                if ($this->Message->saveField('deleted', 1)) {
                    $response = array(
                        'success' => true,
                        'message' => 'Message deleted successfully.'
                    );
                } else {
                    $response = array(
                        'success' => false,
                        'message' => 'Failed to delete message.'
                    );
                }
            } else {
                $response = array(
                    'success' => false,
                    'message' => 'Message not found.'
                );
            }
    
            echo json_encode($response);
        } else {
            $response = array(
                'success' => false,
                'message' => 'Invalid request.'
            );
    
            echo json_encode($response);
        }
    }

    public function deleteAllMessages() {
        $this->autoRender = false;
        $this->response->type('json');
        
        $senderId = $this->request->data['sender_id'];
        $recipientId = $this->request->data['recipient_id'];
        
        $deleted = $this->Message->deleteAll(array(
            'OR' => array(
                array(
                    'Message.sender_id' => $senderId,
                    'Message.recipient_id' => $recipientId
                ),
                array(
                    'Message.sender_id' => $recipientId,
                    'Message.recipient_id' => $senderId
                )
            )
        ), false);
        
        if ($deleted) {
            $this->response->body(json_encode(['status' => 'success']));
        } else {
            $this->response->body(json_encode(['status' => 'error', 'message' => 'Failed to delete messages.']));
        }
        return $this->response;
    }
    
    

    public function replyMessage() {
        $this->autoRender = false; 
    
        if ($this->request->is('ajax')) {
            $replyMessage = $this->request->data['message']; 
            $recipient_id = $this->request->data['recipient_id']; 
            $sender_id = $this->request->data['sender_id']; 
    
            if($this->Auth->user('id') == $sender_id) {
                $this->Message->create();
                $saved = $this->Message->save(array(
                    'sender_id' => $sender_id,
                    'recipient_id' => $recipient_id,
                    'message' => $replyMessage,
                ));
            } else {
                $this->Message->create();
                $saved = $this->Message->save(array(
                    'sender_id' => $recipient_id,
                    'recipient_id' => $sender_id,
                    'message' => $replyMessage,
                ));
            }
    
            if ($saved) {
                $messageId = $this->Message->id;
                $message = $this->Message->findById($messageId); 
    
                $this->set('message', $message); 
                echo json_encode(array('success' => true, 'message' => $message));
            } else {
                echo json_encode(array('success' => false, 'message' => 'Failed to save message.'));
            }
        }
    }
    
    public function messageSearch() {
        $this->autoRender = false;
        $this->layout = 'ajax';
    
        if ($this->request->is('ajax')) {
            $query = $this->request->query('query');
            $userId = $this->Auth->user('id');
    
            $messages = $this->Message->find('all', array(
                'conditions' => array(
                    'OR' => array(
                        array('Message.sender_id' => $userId),
                        array('Message.recipient_id' => $userId)
                    ),
                    'Message.message LIKE' => '%' . $query . '%'
                ),
                'order' => array('Message.created' => 'DESC'),
                'limit' => 10
            ));
    
            $this->set('messages', $messages);
            $this->set('userId', $userId);
            $this->set('conversationUsers', $this->_getConversationUsers($messages));
            $this->render('search_results', 'ajax');
        }
    }
    
    protected function _getConversationUsers($messages) {
        $userIds = array();
        foreach ($messages as $message) {
            $userIds[] = $message['Message']['sender_id'];
            $userIds[] = $message['Message']['recipient_id'];
        }
        $userIds = array_unique($userIds);
    
        $users = $this->Message->User->find('all', array(
            'conditions' => array('User.id' => $userIds),
            'fields' => array('id', 'name', 'propic')
        ));
    
        $conversationUsers = array();
        foreach ($users as $user) {
            $conversationUsers[$user['User']['id']] = $user;
        }
    
        return $conversationUsers;
    }
}