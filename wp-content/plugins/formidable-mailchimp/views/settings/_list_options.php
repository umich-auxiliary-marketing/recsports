<tr class="hide_mailchimp mlchp_list mlchp_list_<?php echo esc_attr( $list_id ) ?>" <?php echo esc_html( $hide_mailchimp ); ?>>
    <td>
    <a class="frm_mlcmp_remove alignright frm_email_actions feature-filter" id="remove_list_<?php echo esc_attr( $list_id ) ?>" href="javascript:void(0)"><img src="<?php echo method_exists('FrmAppHelper', 'plugin_url') ? FrmAppHelper::plugin_url() : FRM_URL; ?>/images/trash.png" alt="<?php esc_attr_e( 'Remove', 'frmmlcmp' ) ?>" title="<?php esc_attr_e( 'Remove', 'frmmlcmp' ) ?>" /></a>
    <p>
        <?php if ( $lists ) { ?>
        <label class="frm_left_label" style="clear:none;"><?php esc_html_e( 'List to Subscribe', 'frmmlcmp' ) ?> <span class="frm_required">*</span></label>
        <select name="mlcmp_list[]" id="select_list_<?php echo esc_attr( $list_id ) ?>">
            <option value=""><?php esc_html_e( '&mdash; Select &mdash;' ); ?></option>
            <?php foreach($lists['data'] as $list){ ?>
            <option value="<?php echo esc_attr( $list['id'] ) ?>" <?php selected( $list_id, $list['id'] ) ?>><?php echo FrmAppHelper::truncate( $list['name'], 40 ) ?></option>
            <?php } ?>
        </select>
        <?php } else {
            esc_html_e('No MailChimp mailing lists found', 'frmmlcmp');
        } ?>
    </p>
<div class="clear"></div>


<div class="frm_mlcmp_fields"></div>

</td>
</tr>
