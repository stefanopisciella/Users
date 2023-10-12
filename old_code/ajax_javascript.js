let httpRequest;

            function removeUser(clicked_button_object) {
                var user_id = getClickedButtonValue(clicked_button_object);
                
                let confirm_text = "Sei sicuro di voler rimuovere l'utente #" + user_id + "?";
                if(confirm(confirm_text)) {
                    // user confirmed the action
                    requestUserRemoval(user_id);
                }
            }
            
            function getClickedButtonValue(clicked_button_object) {
                clicked_button_value = clicked_button_object.value;
                return clicked_button_value;
            } 

            function requestUserRemoval(user_id) {
                httpRequest = new XMLHttpRequest();

                if (!httpRequest) {
                    alert("Cannot create an XMLHTTP instance");
                    return false;
                }
                
                httpRequest.onreadystatechange = processResponseToUserRemoval;
                
                var current_url = window.location.href;
                httpRequest.open("GET", current_url + '/remove/' + user_id);
                httpRequest.send();
            }

            function processResponseToUserRemoval() {
                try {
                    if (httpRequest.readyState === XMLHttpRequest.DONE) {
                        if (httpRequest.status === 200) {
                            var deserialized_response = JSON.parse(httpRequest.responseText);
                            var new_user_table = deserialized_response.data.html;

                            $("#page_content").html(new_user_table); // replace the old user_table with the updated user-table
                        } else {
                            alert("There was a problem with the request.");
                        }
                    }
                } catch (e) {
                    alert(`Caught Exception: ${e.description}`);
                }
            }

            function createUser() {
                var post_content = getUserInput();
                requestUserCreation(post_content);


            }

            function requestUserCreation(post_content) {
                httpRequest = new XMLHttpRequest();

                if (!httpRequest) {
                    alert("Cannot create an XMLHTTP instance");
                    return false;
                }

                httpRequest.onreadystatechange = processResponseToUserCreation;
                
                var current_url = window.location.href;
                httpRequest.open("POST", current_url + '/save');
                
                httpRequest.setRequestHeader(
                    "Content-Type",
                    "application/json",
                );
                // httpRequest.send(`userName=${encodeURIComponent(userName)}`);
                httpRequest.send(post_content);
            }

            function getUserInput() {
                var name = $("#input_name").val();
                var email = $("#input_email").val();
                var birth_year = parseInt($("#birth_year").val());
                var is_female = document.getElementById("is_female_button").checked;
                var is_male = document.getElementById("is_male_button").checked;
                var privacy_agreed = document.getElementById("privacy_button").checked;

                var post_content = {"name" : name , "email" : email, "birth_year" : birth_year, "is_female" : is_female, "is_male" : is_male, "privacy_agreed" : privacy_agreed};

                //
                // alert(JSON.stringify(post_content));
                return JSON.stringify(post_content);
            }

          




            function processResponseToUserCreation() {
                /*
                if (httpRequest.readyState === XMLHttpRequest.DONE) {
                    if (httpRequest.status === 200) {
                    const response = JSON.parse(httpRequest.responseText);
                    alert(response.computedString);
                    } else {
                    alert("There was a problem with the request.");
                    }
                } */
            }