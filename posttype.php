<script type="text/javascript">
    function checkpost() {
        var post = jQuery('#mypost').val();
        if(post == '') {
            jQuery('.error_msg').show();
            return false;
        }
    }
</script>
<?php
global $wpdb;
$args = array( 'numberposts' => -1); 
$posts = get_posts( $args );
//echo "<pre>";print_r($posts);

$results = $wpdb->get_results("Select * from `wp_select_post`");
$postid = '';
if($results != NULL) {
    $postid = $results[0]->post;
}
//echo $postid;
?>
<form method="post" action="" onsubmit="return checkpost();">
    <div class="manage_btns">
        <h2>Select Your Post</h2>
        <div style="float: left; margin-top: 15px;">
            <div class="field_content">
                <label class="field_label">Select Post : </label>
                <select name="mypost_id" id="mypost">
                    <option value="">Select</option>
                    <?php if($posts != NULL) {
                        foreach($posts as $post) { ?>
                            <option value="<?php echo $post->ID; ?>" <?php if($post->ID == $postid) { echo 'selected="selected"'; } ?>><?php echo $post->post_title; ?></option>
                        <?php }
                    } ?>
                </select>
                <span class="error_msg" style="display: none;">Please select any post.</span>
            </div>
            <div class="field_content">
                <input type="submit" value="Save" name="submit_post" id="submit_post" title="Save" />
            </div>
        </div>
    </div>
</form>