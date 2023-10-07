<?php
namespace models;

class User {
    public static function remove($article_id) {
        $GLOBALS['f3']->get('DB')->exec(
            'DELETE FROM article WHERE ID=?;',
            $article_id
        );
    }

    public static function save($article, $article_id) {
        if($article_id == null) {
            // CREATION of the article
            $GLOBALS['f3']->get('DB')->exec(
                'INSERT INTO article (title, content, creation_datetime, last_update_datetime) VALUES (?,?, NOW(), NOW());',
                $article
            );
        } else {
            // UPDATE of the article
            array_push($article, $article_id);
        
            $GLOBALS['f3']->get('DB')->exec(
                'UPDATE article
                 SET title = ?, content = ?, last_update_datetime = NOW()
                 WHERE ID = ?;',
                $article
            );
        }
    }

    public static function getById($article_id) {
        $article = $GLOBALS['f3']->get('DB')->exec(
            'SELECT * FROM article WHERE ID=?',
            $article_id
        );

        return $article;
    }

    public static function index($current_page, $max_num_of_articles_for_page, $order, $dir) {
        if($order == 1) {
            // it orders by the the name column 
            if($dir == 1) {
                // from A to Z (this is the default order)
                $query = "SELECT * 
                          FROM user 
                          ORDER BY name ASC
                          LIMIT ?, ?";
            } else if ($dir == 2) {
                // from Z to A
                $query = "SELECT * 
                          FROM user 
                          ORDER BY name DESC
                          LIMIT ?, ?";
            }
        } else if ($order == 2) {
            // it orders by the the birth_year column 
            if($dir == 1) {
                // older users first
                $query = "SELECT * 
                          FROM user 
                          ORDER BY birth_year ASC
                          LIMIT ?, ?";
            } else if ($dir == 2) {
                // younger users first
                $query = "SELECT * 
                          FROM user 
                          ORDER BY birth_year DESC
                          LIMIT ?, ?";
            }
        }
        
        $parameters = array(
            1 => ($current_page - 1) * $max_num_of_articles_for_page,
            2 => $max_num_of_articles_for_page
        );

        $articles = $GLOBALS['f3']->get('DB')->exec($query, $parameters);

        return $articles;
    }

    public static function getNumOfUsers() {
        $restult = $GLOBALS['f3']->get('DB')->exec('SELECT COUNT(ID) FROM user');
        $num_of_articles = $restult[0]['COUNT(ID)'];

        return $num_of_articles;
    }

    public static function search($keywords, $current_page, $max_num_of_articles_for_page, $order, $dir) {
        $keywords = trim($keywords);

        $keywords = explode(' ', $keywords); // now $keywords contains an array of strings

        $keywords_num = count($keywords);

        $query = "SELECT * 
                  FROM article
                  WHERE "; 

        /*
        exmple of a 2 keywords query =
            SELECT * 
            FROM article
            WHERE (title LIKE '%ferrari%' || content LIKE '%ferrari%') && (title LIKE '%scuderia%' || content LIKE '%scuderia%');
        */

        for($i=0;$i<$keywords_num - 1;$i++) {
            // $query .= "title LIKE '%{$keywords[$i]}%' && ";
            $query .= "(title LIKE '%{$keywords[$i]}%' || content LIKE '%{$keywords[$i]}%') && "; // LIKE by default does a case-insensitive research
        }
        $query .= "(title LIKE '%{$keywords[$keywords_num - 1]}%' || content LIKE '%{$keywords[$keywords_num - 1]}%') "; // LIKE by default does a case-insensitive research

        // add the ORDER BY to the query
        if($order == 1) {
            // it orders by the the creation_datetime column 
            if($dir == 1) {
                // most recently created article first (this is the default order)
                $query .= "ORDER BY creation_datetime DESC ";
            } else if ($dir == 2) {
                // least recently created article first
                $query .= "ORDER BY creation_datetime ASC ";
            }
        } else if ($order == 2) {
            // it orders by the the last_update_datetime column 
            if($dir == 1) {
                // most recently updated article first
                $query .= "ORDER BY last_update_datetime DESC ";
            } else if ($dir == 2) {
                $query .= "ORDER BY last_update_datetime ASC ";
            }
        }

        $offset_param = ($current_page - 1) * $max_num_of_articles_for_page;
        $limit_param = $max_num_of_articles_for_page;
        $query .= "LIMIT {$offset_param}, {$limit_param};";
        
        $matched_articles = $GLOBALS['f3']->get('DB')->exec($query);

        return $matched_articles;
    }

    public static function getNumOfMatchingUsers($keywords) {
        $keywords = trim($keywords);

        $keywords = explode(' ', $keywords); // now $keywords contains an array of strings

        $keywords_num = count($keywords);

        $query = "SELECT COUNT(ID) as count 
                  FROM user
                  WHERE "; 

        for($i=0;$i<$keywords_num - 1;$i++) {
            $query .= "(title LIKE '%{$keywords[$i]}%' || content LIKE '%{$keywords[$i]}%') && "; // LIKE by default does a case-insensitive research
        }
        $query .= "(title LIKE '%{$keywords[$keywords_num - 1]}%' || content LIKE '%{$keywords[$keywords_num - 1]}%')"; // LIKE by default does a case-insensitive research

        $result = $GLOBALS['f3']->get('DB')->exec($query)[0];

        return $result['count'];
    }
}