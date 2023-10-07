<tr>
    <td><?php echo $user_id; ?></td>
    <td><?php echo $name; ?></td>
    <td><?php echo $email; ?></td>
    <td><?php echo $birth_year; ?></td>
    
    <td>
        <?php if($is_male == 1): 
                echo "Maschio";
              else:
                echo 'Femmina';
              endif;?>
    </td>
    <td>
        <?php if($privacy_agreed == 1): 
                echo '<span class="glyphicon glyphicon-ok" id="privacy_agreed_icon" aria-hidden="true"></span>';
              endif;?>
    </td>
    <td>
        <div class="btn-group" role="group" aria-label="...">
            <a href="" type="button" class="btn btn-default">
                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
            </a>
            <a href="" type="button" class="btn btn-default">
                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
            </a>
        </div>
    </td>
</tr>

<style>
    
</style>