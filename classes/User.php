<?php

    class User {
        private $_db,
                $_data,
                $_sessionName;

        public function __construct($user = null) {
            $this->_db = DB::getInstance();
            $this->_sessionName = Config::get('session/session_name');

            if ($user) {
                $this->find($user);
            } else if (Session::exists($this->_sessionName)) {
                $userId = Session::get($this->_sessionName);
                $this->find($userId);
            }
        }

        public function create($fields = array()) {
            if(!$this -> _db -> insert('users', $fields)) {
                throw new Exception ('There was an error creating an account.');
            }
        }

        public function find($user = null) {
            if($user) {
                $field = (is_numeric($user)) ? 'id' : 'username';
                $data = $this -> _db -> get('users', array($field, '=', $user));

                if($data -> count()){
                    $this -> _data = $data -> first();
                    return true;
                } 
            }
            return false;
        }

        public function login($username = null, $password = null) {
            if (!$username || !$password) {
                return false;
            }
            $found = $this->find($username);
            if ($found && isset($this->_data->password, $this->_data->salt)) {
                if ($this->_data->password === Hash::make($password, $this->_data->salt)) {
                    Session::put($this->_sessionName, $this->_data->id);
                    return true;
                }
            }
            return false;
        }

        public function logout() {
            Session::delete($this->_sessionName);
        }

        public function data() {
            return $this->_data;
        }

        public function isLoggedIn() {
            return !empty($this->_data);
        }

        public function hasRole($role) {
            if (!$this->_data) return false;
            // assumes `grupo` integer role: 1=candidate, 2=admin
            if ($role === 'admin') return (int)($this->_data->grupo ?? 1) === 2;
            if ($role === 'candidate') return (int)($this->_data->grupo ?? 1) === 1;
            return false;
        }
    }