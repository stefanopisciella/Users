<?php
namespace controllers;
class AbastractController{

    public static function render($page_content){
        $page_content = $GLOBALS['view']->render($page_content);
        $GLOBALS['f3']->set('page_content', $page_content);
        return $GLOBALS['view']->render('application/layouts/layout.html');
    }

    public static function formatDateTime($datetime_str){
        if(is_null($datetime_str)) {
            // in this case we return so that DateTime will not take in input a null parameter (if DateTime takes in input a null parameter, it will rise an exception!)
            return NULL; 
        }

        $datetime = new \DateTime($datetime_str); // "\DateTime" because DateTime is located in the global namespace
        return $datetime->format('d/m/Y g:i A');
    }

    public static function getPreviousPage(){
        $previous_page = $GLOBALS['f3']->get('SERVER.HTTP_REFERER');
        
        if(!is_null($previous_page)) {
            return $previous_page;
        } else {
            return "/article"; // return to the home         
        }
    }

    /*
    PAREMETERS    
        context: gives information about what kind of items are supposed to be paginated
        items: are all the items to be paginated
        html_page: is the html page that will be divided in pages
    */
    public static function definePagination($max_num_of_items_for_page, $number_of_items, $url_to_html_view, $current_page, $order, $dir, $keywords){
        if($keywords == "") {
            // client hasn't requested a search
            $search_param = "";
        } else {
            // client has requested a search
            $search_param = "&search=";
        }
        
        $num_pages_needed = intval($number_of_items / $max_num_of_items_for_page); // !!! remamber to convert this value into an Integer using the function intval()
        if($number_of_items % $max_num_of_items_for_page != 0) {
            // the last page will contain a number of items different to max_num_of_items_for_page
            $num_pages_needed += 1;
        } 

        AbastractController::addPageButtons($url_to_html_view, $num_pages_needed, $current_page, $order, $dir, $keywords, $search_param);
        AbastractController::addPreviousPageButton($current_page, $url_to_html_view, $order, $dir, $keywords, $search_param);
        AbastractController::addNextPageButton($current_page, $url_to_html_view, $num_pages_needed, $order, $dir, $keywords, $search_param);
    }

    public static function addPageButtons($url_to_html_view, $num_pages, $current_page, $order, $dir, $keywords, $search_param){
        $page_buttons = "";
        for($i=1;$i<=$num_pages;$i++) {
            $url_to_page = $url_to_html_view . "?page=" . $i . "&order=" . $order . "&dir=" . $dir . $search_param . $keywords; 

            if($i == $current_page) {
                $page_buttons .= AbastractController::addCurrentPageButton($current_page); // this button in relative to the current page
            } else {
                $page_buttons .= '<li><a href="' . $url_to_page. '">'. $i .'</a></li>';
            }
        }
        $GLOBALS['f3']->set('page_buttons', $page_buttons);
    }

    public static function addCurrentPageButton($current_page){
        return '<li class="active"><a href="">' . $current_page . '<span class="sr-only">(current)</span></a></li>'; // bootstrap documentation recommends to use <span> instead of <a>
        // note that the href attribute of this button is empty because this button hasn't to redirect to any page (this because we are considering the current page button)
    }

    public static function addPreviousPageButton($current_page, $url_to_html_view, $order, $dir, $keywords, $search_param){
        if($current_page == 1) {
            // disable the previous_page_button
            $previous_page_button = '<li class="disabled">
                                        <a href="" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>'; // !!! remamber to remove the value of the attribute "href" if you want to disable the button

        } else {
            $url_to_previous_page = $url_to_html_view  . "?page=" . ($current_page - 1) . "&order=" . $order . "&dir=" . $dir . $search_param . $keywords;  
            $previous_page_button = '<li>
                                        <a href="'. $url_to_previous_page .'" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>';
        }

        $GLOBALS['f3']->set('previous_page_button', $previous_page_button);
    }

    public static function addNextPageButton($current_page, $url_to_html_view, $last_page_num, $order, $dir, $keywords, $search_param){
        if($current_page == $last_page_num) {
            // disable the next_page_button
            $next_page_button = '<li class="disabled">
                                    <a href="" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                 </li>'; // !!! remamber to remove the value of the attribute "href" if you want to disable the button

        } else {
            $url_to_next_page = $url_to_html_view  . "?page=" . ($current_page + 1) . "&order=" . $order . "&dir=" . $dir . $search_param . $keywords;  
            $next_page_button = '<li>
                                    <a href="' . $url_to_next_page . '" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                 </li>';
        }

        $GLOBALS['f3']->set('next_page_button', $next_page_button);
    }
}