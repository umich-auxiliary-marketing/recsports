<tr><td width="150px"><label><?php _e( 'Field Size', 'frmsig' ) ?></label></td>
    <td>
    <input type="text" name="field_options[size_<?php echo (int) $field['id'] ?>]" value="<?php echo esc_attr( $field['size'] ); ?>" size="5" /> <span class="howto"><?php _e( 'pixels wide', 'frmsig' ) ?></span>

    <input type="text" name="field_options[max_<?php echo (int) $field['id'] ?>]" value="<?php echo esc_attr( $field['max'] ); ?>" size="5" /> <span class="howto"><?php _e( 'pixels high', 'frmsig' ) ?></span>
    </td>
</tr>

<tr><td valign="top"><label><?php _e( 'Signature Options', 'frmsig' ) ?></label></td>
    <td>
    <div style="width:33%;float:left">
    <label for="label1_<?php echo (int) $field['id'] ?>" class="howto"><?php _e( 'Draw It Label', 'frmsig' ) ?></label>
    <input type="text" name="field_options[label1_<?php echo (int) $field['id'] ?>]" value="<?php echo esc_attr( $field['label1'] ); ?>" class="frm_long_input" id="label1_<?php echo (int) $field['id'] ?>"  />
    </div>
    
    <div style="width:33%;float:left">
    <label for="label2_<?php echo (int) $field['id'] ?>" class="howto"><?php _e( 'Type It Label', 'frmsig' ) ?></label>
    <input type="text" name="field_options[label2_<?php echo (int) $field['id'] ?>]" value="<?php echo esc_attr( $field['label2'] ); ?>" class="frm_long_input" id="label2_<?php echo (int) $field['id'] ?>" />
    </div>
    
    <div style="width:33%;float:left">
    <label for="label3_<?php echo (int) $field['id'] ?>" class="howto"><?php _e( 'Clear Label','frmsig' ) ?></label>
    <input type="text" name="field_options[label3_<?php echo (int) $field['id'] ?>]" value="<?php echo esc_attr( $field['label3'] ); ?>" class="frm_long_input" id="label3_<?php echo (int) $field['id'] ?>" />
    </div>
    
    <p style="clear:both"><br/><input type="checkbox" name="field_options[restrict_<?php echo (int) $field['id'] ?>]" id="restrict_<?php echo (int) $field['id'] ?>" value="1" <?php FrmAppHelper::checked( $field['restrict'], 1 ); ?> /> <label for="restrict_<?php echo (int) $field['id'] ?>"><?php _e( 'Hide Draw It and Type It tabs', 'frmsig' ) ?></label></p>
    </td>
</tr>