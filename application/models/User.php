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
        if($user['user_id'] == "not_set") {
            // CREATION of the user
            
            //
            $query = "INSERT INTO user(name, email, birth_year, is_male, privacy_agreed) VALUES ('{$user["name"]}','{$user["email"]}', {$user["birth_year"]}, {$user["is_male"]}, {$user["privacy_agreed"]})";

            $GLOBALS['f3']->get('DB')->exec($query);
        } else {
            // UPDATE of the user
            $GLOBALS['f3']->get('DB')->exec(
                "UPDATE user
                 SET name = '{$user["name"]}', email = '{$user["email"]}', birth_year = '{$user["birth_year"]}', is_male = '{$user["is_male"]}', privacy_agreed = '{$user["privacy_agreed"]}'
                 WHERE ID = '{$user["user_id"]}';"
            );
        }
    }
}