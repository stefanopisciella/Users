<?php
namespace controllers;

require "application/controllers/AbstractController.php";
use models\User as ModelUser;

use function PHPSTORM_META\sql_injection_subst;

class User extends AbastractController{
    public static function remove() {
        /*
        $user_id = $GLOBALS['f3']->get('PARAMS.id');

        if(!parent::is_valid($user_id, "user")) {
            // the passed id is invalid
            
            User::respondInvalidId();
            exit;
        } 
        
        ModelUser::remove($user_id);
        
        User::sendUserTableToClient(); */

        //
        http_response_code(400); 
        exit;
    }

    public static function respondInvalidId() {
        $data = array();
        $data['id'] = 'invalid';
        
        $response = array(
            'status' => 'fail',
            'data' => $data
        );

        echo json_encode($response);
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
            $user_table = '<h2>No available users</h2>';
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
        echo json_encode($response); // converts the array to a JSON
    }

    public static function renderLayout() {
        echo parent::render('application/layouts/layout.html'); // inject user_table within the user.html page */
    }

    public static function prova() {
        echo "<label>CIAO</label>";
    }

    public static function save() {
        if($GLOBALS['f3']->exists('POST.')) {
            echo $GLOBALS['f3']->get('POST.');
        }



         /*
        $name = $_POST['userName'] ?? 'no name';
        $computedString = "Hi, " . $name . "!";
        $array = ['userName' => $name, 'computedString' => $computedString];
        echo json_encode($array); */


        
        
        
        
        
        
        
        /*
        if($GLOBALS['f3']->exists('POST.title') && $GLOBALS['f3']->exists('POST.content')) {
            // client requested with a POST method the UPDATE or the CREATION of the article 
            $article_title = $GLOBALS['f3']->get('POST.title');
            $article_content = $GLOBALS['f3']->get('POST.content');

            $article = array(
                1 => $article_title,
                2 => $article_content
            );

            $article_id = null;
            $article_id = $GLOBALS['f3']->get('PARAMS.id');
            
            ModelArticle::save($article, $article_id); 
            
            if($article_id == null) {
                // client requested with a POST method the CREATION of the article 
                $GLOBALS['f3']->reroute('/article?page=1&order=1&dir=1');
            } else {
                // client requested with a POST method the UPDATE of the article 
                $GLOBALS['f3']->reroute('/article/' . $article_id);
            }
        } */
    }


}