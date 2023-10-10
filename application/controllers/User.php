<?php
namespace controllers;

require "application/controllers/AbstractController.php";
use models\User as ModelUser;

use function PHPSTORM_META\sql_injection_subst;

class User extends AbastractController{
    public static function remove() {
        $user_id = $GLOBALS['f3']->get('PARAMS.id');
        
        // i should check wether the $article_id contains an integer
        
        ModelUser::remove($user_id); 

    }

    public static function index() {
        $users = ModelUser::index(); 

        $user_table = User::makeUserTable($users);
        
        $GLOBALS['f3']->set('page_content', $user_table); // inject user_table into layout
        echo $GLOBALS['view']->render('application/layouts/layout.html');
    }

    public static function makeUserTable($users) {
        $num_users = sizeof($users);
        if($num_users > 0) {
            $table_rows= Array();
    
            for($i=0;$i<$num_users;$i++) {
                $GLOBALS['f3']->set('user_id', $users[$i]['ID']);
                $GLOBALS['f3']->set('name', $users[$i]['name']);
                $GLOBALS['f3']->set('email', $users[$i]['email']);
                $GLOBALS['f3']->set('birth_year', $users[$i]['birth_year']);
                $GLOBALS['f3']->set('is_male', $users[$i]['is_male']);
                $GLOBALS['f3']->set('privacy_agreed', $users[$i]['privacy_agreed']);

                $table_row = $GLOBALS['view']->render('application/views/table_row.php'); // note that in this case i don't use "parent::render" because we don't have to flash the header
                array_push($table_rows, $table_row);
            }
            $GLOBALS['f3']->set('table_rows', $table_rows); // inject rows within the user_table
            $user_table = $GLOBALS['view']->render('application/views/user_table.html'); 

        } else{
            $user_table = 'No available users';
        }

        return $user_table;
    }

    public static function sendUserTableToClient() {
        $users = ModelUser::index(); 
        $user_table = User::makeUserTable($users);

        $data = array();
        $data['html'] = $user_table;
        
        $response = array(
            'status' => 'success',
            'data' => $data
        );

        // set the content type to json so that the JS does not need to parse it
        // CHECK 
        // header('Content-type: application/json');
        echo json_encode($response);
    }

    public static function renderLayout() {
        echo parent::render('application/layouts/layout.html'); // inject user_table within the user.html page */
    }


}