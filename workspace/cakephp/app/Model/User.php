<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class User extends AppModel {
    public $validate = array(
        'name' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Name is required',
                'required' => true
            ),
            'nameLength' => array(
                'rule' => array('between', 5, 20),
                'message' => 'Name must be between 5 and 20 characters long'
            )
        ),
        'email' => array(
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'This email is already been taken'
            ),
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Email is required'
            ),
            'email' => array(
                'rule' => 'email',
                'message' => 'Please provide a valid email address'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Password is required'
            ),
            'match' => array(
                'rule' => array('matchPasswords'),
                'message' => 'Password do not match'
            )
        ),
        'propic' => array(
            'extension' => array(
                'rule' => array('validatePropic'),
                'message' => 'Please supply a valid image (jpg, jpeg, gif, or png).'
            )
        ),
        'birthdate' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Birthdate is required.'
            ),
            'date' => array(
                'rule' => 'date',
                'message' => 'Please enter a valid date.'
            )
        ),
        'gender' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Gender is required.'
            )
            ),
        'hobby' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Hobby is required.'
            )
        )
    );
    
    public function matchPasswords($data) {
        if ($data['password'] === $this->data['User']['password_confirmation']) {
            return true;
        }
        $this->invalidate('password_confirmation', 'Your passwords do not match');
        return false;
    }

    public function extension($check, $extensions) {
        $file = array_shift($check);
        if (is_array($file) && isset($file['name'])) {
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            return in_array($ext, $extensions);
        }
        return false;
    }

    public function validatePropic($check) {
        $file = $check['propic'];
        if (is_array($file)) {
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            return in_array(strtolower($extension), array('jpg', 'jpeg', 'gif', 'png'));
        }
        return true;
    }
    
    public function beforeSave($options = array()) {
        if (isset($this->data['User']['password'])) {
            $this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
        }
        $ipAddress = env('REMOTE_ADDR');

        if (isset($this->data['User']['id']) && $this->data['User']['id']) {
            $this->data['User']['modified_ip'] = $ipAddress;
        } else {
            $this->data['User']['created_ip'] = $ipAddress;
            $this->data['User']['modified_ip'] = $ipAddress;
        }

        return true;
    }
    
}
