<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modifica utente</h4>
      </div>
      <div class="modal-body">
        <!-- modal content -->
        <div class="row">
            <div class="col-xs-11">   
                <form class="form-horizontal">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-xs-2">
                                <label for="input_name" class="control-label">Nome</label>
                            </div>
                            <div class="col-xs-10">
                                <input type="text" class="form-control" id="input_name" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-xs-2">
                                <label for="input_email" class="control-label">Email</label>
                            </div>
                            <div class="col-xs-10">
                                <input type="email" class="form-control" id="input_email" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-2">
                            <label for="birth_year">Anno nascita</label>
                        </div>
                        <div class="col-xs-2"> <!-- we give 2 columns to the select for resizing it (because by default this select is too large) -->
                            <select id="birth_year" class="form-control">
                                <option>-</option> <!-- default option -->
                                <?php for($i=2005;$i>=1930;$i--): ?> 
                                <?php   echo '<option>' . $i . '</option>'; ?>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox"> Accetta privacy
                                    </label>
                                </div>
                            </div>
                        </div> 
                    </div>
                </form>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="page-header">
            <h1>Utenti</h1>
        </div>
        <div class="row">
            <div class="col-xs-6"> <!-- is used col-xs so that in any device the keywords_input will not stack under the "New article button"  -->
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                    Nuovo utente
                </button> 
            </div>
            <div class="col-xs-6"> <!-- is used col-xs so that in any device the keywords_input will not stack under the "New article button"  -->
              <div class="input-group">
                <input id="keywords_input" type="text" class="form-control" placeholder="Cerca...">
                <span class="input-group-btn">
                  <button id="search_keywords_button" class="btn btn-default" type="button" onclick="searchKeywords();">
                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                  </button>
                </span>
              </div><!-- /input-group -->
            </div><!-- /.col-lg-6 -->
        </div><!-- /.row -->  
        <?php echo $results_for_heading; ?>
    </div>
    <div class="table-responsive">          
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>
                        <a type="button" href="./user?page=1&order=1&dir=<?php echo $order1_dir; ?><?php echo $search_param; ?>">Nome <?php echo $direction_arrow_order1; ?></a>        
                    </th>
                    <th>email</th>
                    <th>
                        <a type="button" href="./user?page=1&order=2&dir=<?php echo $order1_dir; ?><?php echo $search_param; ?>">anno nascita <?php echo $direction_arrow_order2; ?></a>        
                    </th>
                    <th>sesso</th>
                    <th>privacy</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($table_rows as $table_row): ?> 
                <?php echo $table_row; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <?php echo $results_num_label; ?>

    <div class="row"> 
        <div class="text-center">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php echo $previous_page_button; ?>
                    <?php echo $page_buttons; ?>
                    <?php echo $next_page_button; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<script>
    function searchKeywords() {
        var current_url = new URL(document.location.href);
        
        var keywords = document.getElementById("keywords_input").value;

        var params = new URLSearchParams(current_url.search);

        // it checks if the current url contains the query string
        if(current_url.toString().includes('?') == true) {
            // the current URL has the query string

            // the following 4 instructions remove all prexisting parameters so that we can replace them with the new parameters relative to the pagination of the search pages
            params.delete("page");
            params.delete("order");
            params.delete("dir");
            params.delete("search");

            params.append("search", keywords); // it adds the new search parameter

            location.search = params.toString(); // redirect to the current url that now contains the new parameters
        } else {
            document.location.href = current_url + '?search=' + keywords; 
        }
    }

    // Get the input field
    var input = document.getElementById("keywords_input");

    // Execute a function when the user presses a key on the keyboard
    input.addEventListener("keypress", function(event) {
    // If the user presses the "Enter" key on the keyboard
    if (event.key === "Enter") {
        // Cancel the default action, if needed
        event.preventDefault();
        // Trigger the button element with a click
        document.getElementById("search_keywords_button").click();
    }
    });
    

    
    function getClickedButtonValue(clicked_button_object) {
        clicked_button_value = clicked_button_object.value;
        return clicked_button_value;
    }
    
    function deleteArticle(clicked_button_object) {
        clicked_button_value = getClickedButtonValue(clicked_button_object);
        
        let confirm_text = "Do you really want to remove article #" + clicked_button_value + "?";
        if(confirm(confirm_text)) {
            var articles_url = window.location.href;
            var remove_article_url = articles_url + "/remove/" + clicked_button_value;
            
            /* don't use Ajax when you have to redirect a webpage, it won't work!
            var xhttp = new XMLHttpRequest();
            xhttp.open("GET", remove_article_url, false);
            xhttp.send(); //send a http request */

            window.location.href = remove_article_url; // redirect to the url contained in remove_article_url
            // at this point the client will be redirected to the '/article' page by the "reroute" function present in the 
            // controller class
        }
    }

    function updateArticle(clicked_button_object) {
        clicked_button_value = getClickedButtonValue(clicked_button_object);
        
        var articles_url = window.location.href;
        var edit_article_url = articles_url + "/view?id=" + clicked_button_value;
        
        window.location.href = edit_article_url; // redirect to the url contained in edit_article_url
    }

    function getArticleDetails(clicked_button_object) {
        clicked_button_value = getClickedButtonValue(clicked_button_object);
        
        var articles_url = window.location.href;
        var article_details_url = articles_url + "/" + clicked_button_value;
        
        window.location.href = article_details_url; // redirect to the url contained in edit_article_url
    }
</script>

<style>
    .last_update_datetime {
        text-align: center;
    }

    .custom_dropdown {
        border-width : 0;
        font-weight: bold;
    }
</style>