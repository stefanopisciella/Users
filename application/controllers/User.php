<?php
namespace controllers;

require "application/controllers/AbstractController.php";
use models\User as ModelUser;

use function PHPSTORM_META\sql_injection_subst;

class User extends AbastractController{
    public static function remove() {
        $user_id = $GLOBALS['f3']->get('PARAMS.id');

        if(!parent::isValid($user_id, "user")) {
            // the passed id is invalid
            http_response_code(404); 
            exit;
        }

        ModelUser::remove($user_id);

        User::respondUserTableHtml();
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

    public static function respondUserTableHtml() {
        $users = ModelUser::index(); 
        $user_table = User::makeUserTable($users);

        $data = array();
        $data['html'] = $user_table;
        
        $response = array(
            'status' => 'success',
            'data' => $data
        );

        echo json_encode($response); // converts the array to a JSON and then the latter will be sent to the client
    }

    public static function renderLayout() {
        echo parent::render('application/layouts/layout.html'); // inject user_table within the user.html page */
    }

    public static function save() {
        /*
        $ar = array();
        $ar["mex"] = "CI ARRIVO";
        echo json_encode($ar); */
        
        $d = User::validateUserInput();

        if($d['status'] == "success") {
            $user = $d;
            ModelUser::save($user);
            User::respondUserTableHtml();
        } else if ($d['status'] == "fail") {
            $response = $d;
            echo json_encode($response);
        }
    }

    public static function validateUserInput() {
        $user = array();
        $fail_response = User::getEmptyFailResponse();
        
        if($GLOBALS['f3']->exists('POST.name')) {
            $name = trim($GLOBALS['f3']->get('POST.name'));
            if($name == "") {
                $fail_response['data']['name'] = 'Il campo "nome" non può essere lasciato vuoto';
                return $fail_response;
            } else {
                $user['name'] = $name;
            }
        }

        if($GLOBALS['f3']->exists('POST.email')) {
            $email = trim($GLOBALS['f3']->get('POST.email'));
            if($email == "") {
                $fail_response['data']['email'] = 'Il campo "email" non può essere lasciato vuoto';
                return $fail_response;
            } else {
                if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    // the submitted email is valid
                    $user['email'] = $email;
                } else {
                    $fail_response['data']['email'] = "L'email inserita non è valida";
                    return $fail_response;
                }
            }
        }

        if($GLOBALS['f3']->exists('POST.birth_year')) {
            $birth_year = $GLOBALS['f3']->get('POST.birth_year');
            if($birth_year == '-') {
                $fail_response['data']['birth_year'] = "Non è stato selezionato l'anno di nascita";
                return $fail_response;
            } else {
                $user['birth_year'] = intval($birth_year);
            }
        }

        if($GLOBALS['f3']->exists('POST.is_male') && $GLOBALS['f3']->exists('POST.is_female') ) {
            $is_male = $GLOBALS['f3']->exists('POST.is_male');
            $is_female = $GLOBALS['f3']->exists('POST.is_female');
            
            if($is_male == false && $is_female == false) {
                // CASE1: user didn't select his/her sex
                $user['is_male'] = null;
            }

            // CASE2: user select his/her sex
            $user['is_male'] = $is_male;
        }

        if($GLOBALS['f3']->exists('POST.privacy_agreed')) {
            $user['privacy_agreed'] = $GLOBALS['f3']->get('POST.privacy_agreed');
        } 

        if($GLOBALS['f3']->exists('POST.user_id')) {
            $user['user_id'] = $GLOBALS['f3']->get('POST.user_id');
        }

        return $user; // this array contains the parameters to be use for the insert query
    }

    public static function update() {
        $user_id = $GLOBALS['f3']->get('PARAMS.id');

        if(!parent::isValid($user_id, "user")) {
            // the passed id is invalid
            http_response_code(404); 
            exit;
        }
        
        $old_user = ModelUser::getById($user_id);

        echo json_encode($old_user);
    }

    public static function getEmptySuccessResponse() {
        $data = array();
        
        $response = array(
            "status" => "success",
            "data" => $data
        );

        return $response;
    }

    public static function getEmptyFailResponse() {
        $data = array();
        
        $response = array(
            "status" => "fail",
            "data" => $data
        );

        return $response;
    }

    public static function prova() {
        if($GLOBALS['f3']->exists('POST.email')) {
            echo $GLOBALS['f3']->get('POST.email') ;
        }

    }
}