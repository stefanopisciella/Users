<?php
namespace models;

class User {
    public static function remove($user_id) {
        $GLOBALS['f3']->get('DB')->exec(
            'DELETE FROM user WHERE ID=?;',
            $user_id
        );
    }

    public static function index() {
        $query = "SELECT * 
                  FROM user;";
            
        $users = $GLOBALS['f3']->get('DB')->exec($query);

        return $users;
    }

    public static function getById($user_id) {
        $user = $GLOBALS['f3']->get('DB')->exec(
            'SELECT * FROM user WHERE ID=?',
            $user_id
        );

        return $user[0]; // !!! NOTE [0]
    }

    public static function save($user) {
        // CHECK
    
        $ar = array(
            0 => $user['user_id'],
            1 => $user['name'],
            2 => $user['email'],
            3 => $user['birth_year'],
            4 => $user['is_male'],
            5 => $user['privacy_agreed']
        ); 
        
        
        
        if($user['user_id'] == "not_set") {
            // CREATION of the user
            $GLOBALS['f3']->get('DB')->exec("INSERT INTO `user`(`name`, `email`, `birth_year`, `is_male`, `privacy_agreed`) VALUES ('{$ar[1]}','{$ar[2]}', '{$ar[3]}', '{$ar[4]}', '{$ar[5]}');");
        } else {
            // UPDATE of the user
            $GLOBALS['f3']->get('DB')->exec(
                "UPDATE user
                 SET name = {$user['name']}, email = {$user['email']}, birth_year = {$user['birth_year']}, is_male = {$user['is_male']}, privacy_agreed = {$user['privacy_agreed']}
                 WHERE ID = {$user['user_id']};"
            );
        }
    }
}