<tr>
    <td><?php echo $user_id; ?></td>
    <td><?php echo $name; ?></td>
    <td><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></td>
    <td><?php echo $birth_year; ?></td>
    <td>
        <?php if($is_male == 1): 
                echo "Maschio";
              else:
                echo 'Femmina';
              endif;?>
    </td>
    <td>
        <?php if($privacy_agreed == true): 
                echo '<span class="glyphicon glyphicon-ok" id="privacy_agreed_icon" aria-hidden="true"></span>';
              endif;?>
    </td>
    <td>
        <div class="btn-group" role="group" aria-label="...">
            <!-- update user -->
            <button id="update_user_button" type="button" class="btn btn-default" value="<?php echo $user_id; ?>" onclick="updateUser(this)" data-toggle="modal" data-target="#myModal">
                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
            </button>
            <!-- remove user button -->
            <button type="button" class="btn btn-default" value="<?php echo $user_id; ?>" onclick="removeUser(this);">
                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
            </button>
        </div>
    </td>
</tr>

<style>
    
</style>