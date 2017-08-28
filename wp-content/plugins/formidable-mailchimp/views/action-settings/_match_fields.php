<?php

if ( ! isset( $list_fields['merge_fields'] ) ) {
	return;
}
?>
<div class="frm_mlcmp_fields <?php echo esc_attr( $action_control->get_field_id('frm_mlcmp_fields') ) ?>">

<?php
if ( isset( $list_fields['merge_fields'] ) && is_array( $list_fields['merge_fields'] ) ) {
	$email_field = array(
		'name' => __( 'Email Address', 'frmmlcmp' ), 'tag' => 'EMAIL',
		'required' => true, 'type' => 'email',
	);
	array_unshift( $list_fields['merge_fields'], $email_field );

foreach ( $list_fields['merge_fields'] as $list_field ) { ?>
<p><label class="frm_left_label"><?php echo esc_html( $list_field['name'] ); ?>
    <?php
    if ( $list_field['required'] ) {
        ?><span class="frm_required">*</span><?php
    } ?>
    </label>
    
    <select name="<?php echo esc_attr( $action_control->get_field_name('fields') ) ?>[<?php echo esc_attr( $list_field['tag'] ) ?>]">
        <option value=""><?php esc_html_e( '&mdash; Select &mdash;' ); ?></option>
        <?php foreach ( $form_fields as $form_field ) {
                if ( $list_field['type'] == 'email' && ! in_array( $form_field->type, array( 'email', 'hidden', 'user_id', 'text' ) ) ) {
                    continue;
                }
                
                $selected = ( isset($list_options['fields'][$list_field['tag'] ]) && $list_options['fields'][$list_field['tag']] == $form_field->id ) ? ' selected="selected"' : '';
            ?>
        <option value="<?php echo esc_attr( $form_field->id ) ?>" <?php echo esc_html( $selected ) ?>><?php echo FrmAppHelper::truncate($form_field->name, 40) ?></option>
        <?php } ?>
    </select>
</p>
<?php
}
}

if ( $groups ) {
	$old_groups = FrmMlcmpAppController::get_group_ids( $groups, $groups['list_id'] );

foreach ( $groups['categories'] as $group ) {

    if ( ! isset($group['id']) ) {
        continue;
    }

	$old_group_id = isset( $old_groups[ $group['id'] ] ) ? $old_groups[ $group['id'] ] : '';
	$selected_field = '';
	if ( isset( $list_options['groups'][ $group['id'] ] ) ) {
		$selected_field = $list_options['groups'][ $group['id'] ]['id'];
	} elseif ( isset( $list_options['groups'][ $old_group_id ] ) ) {
		$selected_field = $list_options['groups'][ $old_group_id ]['id'];
	}
?>
<div class="frm_mlcmp_group_box" data-gid="<?php echo esc_attr( $group['id'] ) ?>">
    <label class="frm_left_label"><?php echo esc_html($group['title']); ?></label>
    <select name="<?php echo esc_attr( $action_control->get_field_name('groups') ) ?>[<?php echo esc_attr( $group['id'] ) ?>][id]" class="frm_mlcmp_group">
            <option value=""><?php esc_html_e( '&mdash; Select &mdash;' ); ?></option>
            <?php 
            foreach ( $form_fields as $form_field ) {
                if ( ! in_array($form_field->type, array('hidden', 'select', 'radio', 'checkbox', 'data')) ) {
                    continue;
                }

                if ( $selected_field == $form_field->id ) {
                    $new_field = $form_field;
                }
                
            ?>
            <option value="<?php echo esc_attr( $form_field->id ) ?>" <?php selected( $selected_field, $form_field->id ) ?>><?php echo FrmAppHelper::truncate( $form_field->name, 40 ) ?></option>
            <?php } ?>
    </select>
    <?php
    include('_group_values.php');
        
    if ( isset($new_field) ) {
        unset($new_field);
    }
        
    ?>
</div>
<?php }
} ?>

<p><label class="frm_left_label"><?php esc_html_e( 'Opt In', 'frmmlcmp' ) ?></label>
    <select name="<?php echo esc_attr( $action_control->get_field_name('optin') ) ?>" id="<?php echo esc_attr( $action_control->get_field_id('mlcmp_optin') ) ?>">
        <option value="0"><?php esc_html_e( 'Single', 'frmmlcmp' ) ?></option>
        <option value="1" <?php selected($list_options['optin'], 1); ?>><?php esc_html_e( 'Double', 'frmmlcmp' ) ?></option>
    </select> 
</p>

</div>