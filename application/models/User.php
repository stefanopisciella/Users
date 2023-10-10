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
}