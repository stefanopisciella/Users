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

        $GLOBALS['f3']->reroute('/user');
    }

    public static function injectUserTable() {

    }
    
    public static function index() {
        if($GLOBALS['f3']->exists('GET.page')) {
            $current_page = $GLOBALS['f3']->get('GET.page');
        } else {
            $current_page = 1; // if the url doesn't contain the "page" parameter, by default, to the user will be showed the first page
        }
        
        // $order is relative to the currently requested page, it's not relative to the previously requested page
        if($GLOBALS['f3']->exists('GET.order')) {
            $order = $GLOBALS['f3']->get('GET.order'); // $order indicates the order of the articles of the current page 
        } else {
            // it considers the default order (it orders by the creation_datetime column of the DB table)
            $order = 1; 
        }

        // $dir is relative to the currently requested page, it's not relative to the previously requested page
        if($GLOBALS['f3']->exists('GET.dir')) {
            $dir = $GLOBALS['f3']->get('GET.dir');
        } else {
            // it considers the default direction DESC
            $dir = 1;  
        }

        if($GLOBALS['f3']->exists('GET.search')) {
            $keywords = $GLOBALS['f3']->get('GET.search');

            $GLOBALS['f3']->set('search_param', '&search=' . $keywords); // it adds the search param into the href attribute of the order button
            $GLOBALS['f3']->set('results_for_heading', '<h3><small>Results for: </small>' . $keywords . '</h3>'); 

            $users = ModelUser::search($keywords, $current_page, $GLOBALS['max_num_of_articles_per_page'], $order, $dir);
            $total_num_of_users = ModelUser::getNumOfMatchingUsers($keywords);

            $GLOBALS['f3']->set('results_num_label', '<label><small>' . $total_num_of_users .  ' results</small></label>'); 
        } else {
            $keywords = "";

            $GLOBALS['f3']->set('search_param', ''); // it doesn't add the search param into the href attribute of the order button
            $GLOBALS['f3']->set('results_for_heading', ''); 

            $users = ModelUser::index($current_page, $GLOBALS['max_num_of_users_per_page'], $order, $dir); 
            $total_num_of_users = ModelUser::getNumOfUsers();

            $GLOBALS['f3']->set('results_num_label', ''); // hide the results_num label
        }

        User::setTheDirectionOfUsersOrder($order, $dir);
        
        parent::definePagination($GLOBALS['max_num_of_users_per_page'], $total_num_of_users , $GLOBALS['url_prefix'] . "user", $current_page, $order, $dir, $keywords);
        
        User::injectUsersIntoCurrentPage($users);
    }

    public static function injectUsersIntoCurrentPage($users) {
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
            $GLOBALS['f3']->set('table_rows', $table_rows);
            
            echo parent::render('application/views/users.html');
        } else{
            echo 'No available users';
        }
    }

    public static function setTheDirectionOfUsersOrder($order, $dir) {
        // order = 1 means that it orders by the the creation_datetime column of the DB table
        // order = 2 means that it orders by the the last_update_datetime column of the DB table
        // dir = 1 is DESC
        // dir = 2 is ASC
        
        if($order == 1) {
            if($dir == 1) {
                $GLOBALS['f3']->set('order1_dir', 2); // switch the DIR

                $GLOBALS['f3']->set('direction_arrow_order1', '&darr;'); // ⬇️
            } else if ($dir == 2) {
                $GLOBALS['f3']->set('order1_dir', 1); // switch the DIR

                $GLOBALS['f3']->set('direction_arrow_order1', '&uarr;'); // ⬆️
            }
            
            $GLOBALS['f3']->set('order2_dir', 1);

            $GLOBALS['f3']->set('direction_arrow_order2', '');
        } else if ($order == 2) {
            if($dir == 1) {
                $GLOBALS['f3']->set('order2_dir', 2); // switch the DIR

                $GLOBALS['f3']->set('direction_arrow_order2', '&darr;'); // ⬇️
            } else if ($dir == 2) {
                $GLOBALS['f3']->set('order2_dir', 1); // switch the DIR

                $GLOBALS['f3']->set('direction_arrow_order2', '&uarr;'); // ⬆️
            }

            $GLOBALS['f3']->set('order1_dir', 1);

            $GLOBALS['f3']->set('direction_arrow_order1', '');
        } 
    }

    





}