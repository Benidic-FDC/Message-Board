<?php
class UsersController extends AppController {
    public $uses = ['User'];
    public $components = array('Session');
	public function beforeFilter() {
        parent::beforeFilter();

        // always restrict your whitelists to a per-controller basis
        $this->Auth->allow(["add", "thankyou", "edit"]);
    }

    public function login() {
        $currentUserID = $this->Auth->user('id');
        if ($currentUserID) {
            // $this->Flash->error(__('You are already logged in.'));
            return $this->redirect(array('controller' => 'users', 'action' => 'index'));
        }
    
        if ($this->request->is('post')) {
            $email = $this->request->data['User']['email'];
            $password = $this->request->data['User']['password'];
    
            $user = $this->User->findByEmail($email);
    
            if ($user) {
                if (Security::hash($password, 'sha1', true) === $user['User']['password']) {
                    $this->User->id = $user['User']['id'];
                    $this->User->saveField('login_at', date('Y-m-d H:i:s'));
                    
                    $this->Auth->login($user['User']);
                    return $this->redirect(array('controller' => 'users', 'action' => 'index'));
                }
            }
            
            $this->Flash->error(__('Invalid username or password, try again'));
        }
    }
    

    // public function ajaxLogin () {

    //     $user = $this->User->find('first', array(
    //         'conditions' => array(
    //             'username' => $this->request->data['username'],
    //             'password' => $this->request->data['password']
    //         )
    //     ));

    //     $didLogin = $this->Auth->login($user['User']);
        
    //     echo json_encode(array(
    //         "status" => "success",
    //         "user" => $this->Auth->user()
    //     ));
    //     die();
    // }

    public function logout() {
        $this->Auth->logout();
        $this->redirect('/users/login');
    }
    
    public function index() {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }

    public function view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        $this->set('user', $this->User->findById($id));
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Flash->success(__('The user has been saved'));
                return $this->redirect(array('action' => 'thankyou'));
            }
            $errors = $this->User->validationErrors;
            if (!empty($errors['email'])) {
                $this->Flash->error(__('Email is already been taken'));
            } elseif (!empty($errors['name'])) {
                foreach ($errors['name'] as $error) {
                    $this->Flash->error(__($error));
                }
            } elseif (!empty($errors['password'])) {
                foreach ($errors['password'] as $error) {
                    $this->Flash->error(__($error));
                }
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
    }

    public function edit($id = null) {
        if (!$id) {
            $id = $this->Auth->user('id');
        }
    
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }

        if ($this->request->is(array('post', 'put'))) {
            // Check if a file was uploaded
            if (!empty($this->request->data['User']['propic']['name'])) {
                $file = $this->request->data['User']['propic'];
                $fileOrigName = pathinfo($file['name'], PATHINFO_FILENAME);
                $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = $fileOrigName . '_' . time() . '.' . $fileExtension;
                $uploadPath = WWW_ROOT . 'img' . DS . 'uploads' . DS;
                $uploadFile = $uploadPath . $filename;
    
                if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                    $this->request->data['User']['propic'] = $filename;
                } else {
                    $this->Flash->error('File upload failed.');
                    return;
                }
            } else {
                // Retain the existing file if no new file was uploaded
                $existingUser = $this->User->findById($id);
                $this->request->data['User']['propic'] = $existingUser['User']['propic'];
            }
    
            // debug($this->request->data);

            if ($this->User->save($this->request->data)) {
                $this->Flash->success(__('User profile has been updated'));
                return $this->redirect(array('action' => 'profile'));
            }
            $errors = $this->User->validationErrors;
            $this->Flash->error(__('User profile could not be saved. Please, try again.'));
            $this->set('errors', $errors);
        } else {
            $this->request->data = $this->User->findById($id);
            unset($this->request->data['User']['password']);
        }
    
        $this->set('user', $this->request->data['User']);
    }
    
    public function profile(){
        $userId = $this->Auth->user('id');
    
        $user = $this->User->findById($userId);
        
        if (!$user) {
            throw new NotFoundException(__('Invalid user'));
        }
        
        $this->set('user', $user['User']);
    }

    public function change_password($id) {
        if ($this->request->is('post')) {
            $currentPassword = $this->request->data['current_password'];
            $newPassword = $this->request->data['new_password'];
            $confirmPassword = $this->request->data['confirm_password'];
    
            $user = $this->User->findById($id);
    
            if (!$user) {
                $this->Flash->error(__('User not found.'));
                return;
            }
    
            if (AuthComponent::password($currentPassword) !== $user['User']['password']) {
                $this->Flash->error(__('Current password is incorrect.'));
                return;
            }
    
            if ($newPassword !== $confirmPassword) {
                $this->Flash->error(__('New password and confirm password do not match.'));
                return;
            }
    
            $this->User->id = $id;
            if ($this->User->saveField('password', $newPassword)) {
                $this->Flash->success(__('Password has been changed successfully.'));
                return $this->redirect(array('action' => 'profile'));
            } else {
                $this->Flash->error(__('Unable to change password. Please try again.'));
            }
        }
    }

    public function delete($id = null) {
        // Prior to 2.5 use
        // $this->request->onlyAllow('post');

        $this->request->allowMethod('post');

        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->User->delete()) {
            $this->Flash->success(__('User deleted'));
            return $this->redirect(array('action' => 'index'));
        }
        $this->Flash->error(__('User was not deleted'));
        return $this->redirect(array('action' => 'index'));
    }

    public function thankyou() {

    }

}