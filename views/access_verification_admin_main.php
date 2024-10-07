<?php
$rows 		= apply_filters( 'aav_get_verify_rows', true );
$paginavi 	= apply_filters( 'aav_my_pagination', $rows['page'], $rows['pages'], 'vpage' );
$v_users    = $rows['res'];

$bulk_actions_buttom = "<form class='bulk-verify-contacts'>
                            <select name=action>
                                <option value=''>Select action</option>
                                <option value='delete'>Delete</option>
                            </select>

                            <input type='submit' value='Apply'>
                        </form>";
?>
<h1>
    <?php echo __( get_admin_page_title() ) ?>
</h1>

<?php echo $bulk_actions_buttom?>
<table class="wp-list-table widefat fixed striped table-view-list pages verify-contacts">
    <thead>
        <tr>
            <th scope="col" class="manage-column column-title">
                <input type="checkbox" name="select-all-contacts">
            </th>

            <th scope="col" class="manage-column column-title">
                <?php echo __( 'Phone' ) ?>
            </th>

            <th scope="col" class="manage-column column-id">
                <?php echo __( 'Status' ) ?>
            </th>

            <th scope="col" class="manage-column column-id">
                <?php echo __( 'Date' ) ?>
            </th>
        </tr>
    </thead>

    <tbody>
        <?php
            foreach( $v_users as $v_user ):
            ?>
            <tr>
                <td class="check column-checkbox">
                    <input type="checkbox" value="<?php echo $v_user->id ?>">
                </td>

                <td class="title column-title">
                    <?php echo $v_user->phone ?>
                </td>

                <td class="id column-id">
                    <?php echo $v_user->status ?>
                </td>

                <td class="date column-date">
                    <?php echo $v_user->send_date ?>
                </td>
            </tr>
            <?php
            endforeach;
        ?>
    </tbody>
</table>
<?php echo $bulk_actions_buttom?>
