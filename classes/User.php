<?php

    class User {
        private $_db,
                $_data;

        public function __construct($user = null) {
            $this -> _db = DB::getInstance();
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
            $user = $this -> find($username);
            
            if($user) {
                if($this -> data() -> password === Hash::make($password, $this -> data() -> salt)) {
                    echo 'Ok';
                } else {
                    echo 'dont match <br>';
                }
            }
            //   print_r($user);

            return false;
        }

        private function data() {
            return $this -> _data;
        }
    }