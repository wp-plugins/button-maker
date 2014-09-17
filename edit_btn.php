<script type="text/javascript">
jQuery(document).ready(function(){
   jQuery('#check_preview').click(function(){
       var text = jQuery('#btn_text').val();
       var size = jQuery('#font_size').val();
       var color = jQuery('#font_color').val();
       var backcolor = jQuery('#back_color').val();
       var height = jQuery('#height').val();
       var width = jQuery('#width').val();
       var shape = jQuery('input[name=shape]:radio:checked').val();
       if(text != '') {
            jQuery('#btnAddProfile').val(text);
       }
       jQuery('#btnAddProfile').attr('style', 'font-size: '+size+' !important; color: #'+color+' !important; height: '+height+'px !important; width: '+width+'px !important; background: #'+backcolor+';');

       if(shape == 1) {
           jQuery('#btnAddProfile').removeClass("btn_square btn_shadow");
           jQuery('#btnAddProfile').addClass('btn_round');
       } else if(shape == 2) {
           jQuery('#btnAddProfile').removeClass("btn_round btn_shadow");
           jQuery('#btnAddProfile').addClass('btn_square');
       } else if(shape == 3) {
           jQuery('#btnAddProfile').removeClass("btn_round btn_square");
           jQuery('#btnAddProfile').addClass('btn_shadow');
       }
       var html = jQuery('.my_buttons').html();
       jQuery('#btn_html').val(html);
   });
   
   jQuery('#btnAddProfile').mouseover(function(){
        var hovercolor = jQuery('#hover_color').val();
        jQuery(this).css('background','#'+hovercolor);
   });
   jQuery('#btnAddProfile').mouseout(function(){
        var backcolor = jQuery('#back_color').val();
        jQuery(this).css('background','#'+backcolor);
   });
});
function Getbutton() {
	jQuery('#check_preview').trigger( "click" );
    var html = jQuery('.my_buttons').html();
    jQuery('#btn_html').val(html);
}
</script>
<?php global $wpdb;
$id = $_GET['editid'];
$getbtn = $wpdb->get_results("Select * from `wp_custom_btns` where id=$id");
$shape = $getbtn[0]->shape;
//echo "<pre>";print_r($getbtn);exit; ?>
<form method="post" action="" onsubmit="return Getbutton();">
    <div class="my_buttons">
        <?php echo $getbtn[0]->html; ?>
    </div>
    <div class="" style="display: none;">
        <input type="hidden" name="btn_html" id="btn_html" value="<?php //echo $getbtn[0]->html; ?>" />
        <input type="hidden" name="btnid" value="<?php echo $id; ?>" />
    </div>
    <div class="manage_btns">
        <h2>Edit Your Button</h2>
        <div>
            <div class="field_content">
                <label class="field_label">Enter Text : </label>
                <input class="text_field" type="text" id="btn_text" name="btn_text" value="<?php echo $getbtn[0]->text; ?>" />
            </div>
            <div class="field_content">
                <label class="field_label">Select Font Size : </label>
                <select id="font_size" name="font_size">
                    <option value="8px">8px</option>
                    <option value="9px">9px</option>
                    <option value="10px">10px</option>
                    <option value="11px">11px</option>
                    <option value="12px">12px</option>
                    <option value="13px">13px</option>
                    <option value="14px" selected="selected">14px</option>
                    <option value="15px">15px</option>
                    <option value="16px">16px</option>
                    <option value="17px">17px</option>
                    <option value="18px">18px</option>
                </select>
            </div>
            <div class="field_content">
                <label class="field_label">Select Font Color : </label>
                <input id="font_color" name="font_color" class="color" value="<?php echo $getbtn[0]->color; ?>" />
            </div>
            <div class="field_content">
                <label class="field_label">Select Background Color : </label>
                <input id="back_color" name="back_color" class="color" value="<?php echo $getbtn[0]->background; ?>" />
            </div>
            <div class="field_content">
                <label class="field_label">Select Background Hover Color: </label>
                <input id="hover_color" name="hover_color" class="color" value="<?php echo $getbtn[0]->hover; ?>" />
            </div>
            <div class="field_content">
                <label class="field_label">Enter Url : </label>
                <input class="text_field" type="text" name="url" value="<?php echo $getbtn[0]->url; ?>" />
            </div>
            <div class="field_content">
                <label class="field_label">Enter Height : </label>
                <input class="text_field" type="text" name="height" id="height" placeholder="25" value="<?php echo $getbtn[0]->height; ?>" />
            </div>
            <div class="field_content">
                <label class="field_label">Enter Width : </label>
                <input class="text_field" type="text" name="width" id="width" placeholder="150" value="<?php echo $getbtn[0]->width; ?>" />
            </div>
            <div class="field_content">
                <label class="field_label">Select Shape : </label>
                <div style="float: left; width: 65%;">
                    <span class="btn_pre">
                        <input type="radio" name="shape" id="btn_shape1" value="1" <?php if($shape == 1) { echo 'checked="checked"'; } ?> />
                        <label for="btn_shape1"><input type="button" value="Rectangle" style="border: 2px solid #bcbcbc; border-radius: 5px; padding: 8px; color: #555555;" /></label>
                    </span>
                    <span class="btn_pre">
                        <input type="radio" name="shape" id="btn_shape2" value="2" <?php if($shape == 2) { echo 'checked="checked"'; } ?> />
                        <label for="btn_shape2"><input type="button" value="Lozenge" style="border: 2px solid #bcbcbc; width: 90px; border-radius: 25px; padding: 8px; color: #555555;" /></label>
                    </span>
                    <span class="btn_pre">
                        <input type="radio" name="shape" id="btn_shape3" value="3" <?php if($shape == 3) { echo 'checked="checked"'; } ?> />
                        <label for="btn_shape3"><input type="button" value="Drop shadow" style="border: 2px solid #bcbcbc; border-radius: 5px; padding: 8px; color: #555555; box-shadow: 0 2px 0 #000;" /></label>
                    </span>
                </div>
            </div>
            <div class="field_content extra_class">
                <input type="button" value="Preview" id="check_preview" title="Preview" />
                <a style="text-decoration: none;" href="javascript:void(0);" onclick="del_btn(this.rel);" rel="<?php echo site_url(); ?>/wp-admin/admin.php?page=ButtonMaker/buttons.php&delid=<?php echo $id; ?>">
                    <input type="button" value="Delete" id="del_this_btn" title="Delete" />
                </a>
                <input type="submit" value="Update" name="edit_btn" id="submit_changes" title="Update" />
            </div>
        </div>
    </div>
</form>