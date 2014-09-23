<script type="text/javascript">
function del_btn(rel) {
      var conf = confirm("Are you sure you want to delete the button ?");
      if(conf == true) {
          document.location.href=rel;
      } else {
          return false;
      }
}
function clear_stats(stat) {
    var conf = confirm("Are you sure you want to clear the stats for this button ?");
    if(conf == true) {
        document.location.href=stat;
    } else {
        return false;
    }
}
function getchecked() {
	var total = jQuery('input[name="is_show[]"]:checked').length;
	
	if(total == 0) {
		alert('Please check atleast one button.');
		return false;
	} else {
		return true;
	}
}
</script>
<?php global $wpdb;
if(isset($_GET['editid']) && $_GET['editid'] != NULL) {
    include 'edit_btn.php';
} else if (isset($_GET['delid']) && $_GET['delid'] != NULL) {
    
    $id = $_GET['delid'];
    $query = "Delete from wp_custom_btns where id=$id";
    $wpdb->query($query);
    $nextpage = site_url().'/wp-admin/admin.php?page=button-maker/buttons.php';
    echo "<script type='text/javascript'>document.location.href='$nextpage';</script>";
    exit;
    
} else if(isset($_GET['clearid']) && $_GET['clearid'] != NULL) {
    
    $id = $_GET['clearid'];
    $query = "Update wp_custom_btns set clicks=0,impressions=0 where id=$id";
    $wpdb->query($query);
    $nextpage = site_url().'/wp-admin/admin.php?page=button-maker/buttons.php';
    echo "<script type='text/javascript'>document.location.href='$nextpage';</script>";
    exit;
    
} else {
    $buttons = $wpdb->get_results("Select * from `wp_custom_btns` order by id desc");
    //echo "<pre>";print_r($buttons);exit; ?>
    <div class="show_btns">
        <h2>List of Buttons</h2>
		<form method="post" action="" onsubmit="return getchecked();">
        <table border="1" id="btn_table">
            <tr>
                <th>Show ?</th>
                <th>No.</th>
                <th>Text</th>
                <th>Clicks</th>
                <th>Impressions</th>
                <th>Click rate</th>
                <th>Action</th>
                <th>Order</th>
            </tr>
            <?php if($buttons == NULL) { ?>
                <tr>
                    <td colspan="8"><div class="empty_data">No Records Found.</div></td>
                </tr>
            <?php } else {
                $a = 1;
                foreach($buttons as $button) {
                    $clicks = $button->clicks;
                    $impressions = $button->impressions;
                    //echo "<pre>";print_r($button);exit; ?>
                    <tr>
                        <td><input type="checkbox" value="<?php echo $button->id; ?>" name="is_show[]" <?php if($button->show == '1') { echo 'checked="checked"'; } ?> /></td>
                        <td><?php echo $a; ?></td>
                        <td><?php echo $button->text; ?></td>
                        <td><?php echo $clicks; ?></td>
                        <td><?php echo $impressions; ?></td>
                        <td>
                            <?php if($impressions != 0) {
                                $rate = ($clicks/$impressions)*100;
                                echo number_format($rate,2).' %';
                            } else {
                                echo '0 %';
                            } ?>
                        </td>
                        <td style="width: 40%">
                            <a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=button-maker/buttons.php&editid=<?php echo $button->id; ?>">Edit</a>&nbsp;&nbsp;
                            <a onclick="del_btn(this.rel);" href="javascript:void(0);" rel="<?php echo site_url(); ?>/wp-admin/admin.php?page=button-maker/buttons.php&delid=<?php echo $button->id; ?>">Delete</a>&nbsp;&nbsp;
                            <a onclick="clear_stats(this.rel);" href="javascript:void(0);" rel="<?php echo site_url(); ?>/wp-admin/admin.php?page=button-maker/buttons.php&clearid=<?php echo $button->id; ?>">Reset the stats</a>&nbsp;&nbsp;
                        </td>
                        <td>
                            <input type="text" name="order[<?php echo $button->id; ?>]" value="<?php echo $button->order; ?>" class="set_order"  />
                        </td>
                    </tr>
                <?php $a++; }
                    }
            ?>
        </table>
		<?php if($buttons != NULL) { ?>
			<div style="float: left; margin: 10px 0px;">
				<input type="submit" title="Save" class="show_buttons" name="show_btns" value="Save" />
			</div>
		<?php } ?>
		</form>
    </div>
<?php } ?>