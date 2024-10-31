<?php
switch ($field['type']) {
	case 'textarea': ?>
		<div class="row mb-3">
		    <label class="col-sm-4 col-form-label">
		        <?php echo esc_attr( $field['title'] ); ?>
		    </label>
		    <div class="col-sm-8">
	            <textarea class="form-control form-control-sm field_<?php echo esc_attr( $field['name'] ); ?>"><?php
	            if ( isset($data[$field['name']]) ) {
	                if (is_array($data[$field['name']])) {
	                    $options = implode("\n", $data[$field['name']]);
	                    echo esc_attr( stripcslashes($options) );
	                } else {
	                    echo esc_attr( stripcslashes($data[$field['name']]) );
	                }
	            }
	            ?></textarea>
		    </div>
		</div>
		<?php break;

	case 'select': ?>
		<div class="row mb-3">
		    <label class="col-sm-4 col-form-label">
		        <?php echo esc_attr( $field['title'] ); ?>
		    </label>
		    <div class="col-sm-8">
		        <select class="form-select form-select-sm field_<?php echo esc_attr( $field['name'] ); ?>">
		            <?php
		            	$options = $field['options'];
		                foreach ($options as $key => $value) {
		                    $selected = (isset($data[$field['name']]) && $data[$field['name']] == $key) ? 'selected' : '' ;
		                    echo '<option value="'.esc_attr( $key ).'" '.esc_attr( $selected ).'>'.esc_attr( $value ).'</option>';
		                }
		            ?>
		        </select>
		    </div>
		</div>
		<?php break;

	case 'checkbox': ?>
		<div class="row mb-3">
		    <label class="col-sm-4 col-form-label">
		        <?php echo esc_attr( $field['title'] ); ?>
		    </label>
		    <div class="col-sm-8">
				<div class="checkbox">
					<label>
						<input type="checkbox" class="field_<?php echo esc_attr( $field['name'] ); ?>" <?php echo (isset($data[$field['name']]) && $data[$field['name']] == 'true') ? 'checked' : '' ; ?>>
						<?php _e( 'Enable', 'ultimate-classified-listings' ); ?>	
					</label>
				</div>
		    </div>
		</div>
		<?php break;

	case 'checkboxes': ?>
		<div class="row mb-3">
		    <label class="col-sm-4 col-form-label">
		        <?php echo esc_attr( $field['title'] ); ?>
		    </label>
		    <div class="col-sm-8">
		    	<?php
					foreach ($field['options'] as $key => $label) { ?>
						<div class="checkbox">
							<label>
								<input value="<?php echo esc_attr( $key ); ?>" type="checkbox" class="field_<?php echo esc_attr( $field['name'] ); ?>" <?php echo (isset($data[$field['name']]) && in_array($key, $data[$field['name']])) ? 'checked' : '' ; ?>>
								<?php echo esc_attr( $label ); ?>	
							</label>
						</div>
					<?php }
		    	?>
		    </div>
		</div>
		<?php break;

	case 'number': ?>
		<div class="row mb-3">
		    <label class="col-sm-4 col-form-label">
		        <?php echo esc_attr( $field['title'] ); ?>
		    </label>
		    <div class="col-sm-8">
		        <input type="number" class="form-control form-control-sm field_<?php echo esc_attr( $field['name'] ); ?>" value="<?php echo (isset($data[$field['name']])) ? $data[$field['name']] : '' ; ?>">
		    </div>
		</div>
		<?php break;

	case 'icon': ?>
		<div class="row mb-3">
		    <label class="col-sm-4 col-form-label">
		        <?php echo esc_attr( $field['title'] ); ?>
		    </label>
		    <div class="col-sm-8">
		    	<select class="ucl-iconpicker">
		    		<option value=""><?php _e( 'No icon', 'ultimate-classified-listings' ) ?></option>
		    		<?php
		    			$icons = uclwp_get_icons_list();
		    			foreach ($icons as $iconClass) {
		    				$selected = (isset($data[$field['name']]) && $data[$field['name']] == $iconClass) ? 'selected' : '' ;
		    				echo "<option $selected>{$iconClass}</option>";
		    			}
		    		?>
		    	</select>
		    </div>
		</div>
		<?php break;

	case 'hidden': ?>
		<input type="hidden" class="field_<?php echo esc_attr( $field['name'] ); ?>" value="<?php echo (isset($data[$field['name']]) && $data[$field['name']] == false) ? 'false' : 'true' ; ?>">
		<?php break;
	
	default: ?>
		<div class="row mb-3">
		    <label class="col-sm-4 col-form-label">
		        <?php echo esc_attr( $field['title'] ); ?>
		    </label>
		    <div class="col-sm-8">
		        <input type="text" class="form-control form-control-sm field_<?php echo esc_attr( $field['name'] ); ?>" value="<?php echo (isset($data[$field['name']])) ? stripcslashes( $data[$field['name']] ) : '' ; ?>" <?php echo ($field['name'] == 'key' && isset($data['editable']) && $data['editable'] == false) ? 'disabled' : '' ; ?>>
		    </div>
		</div>
		<?php break;
}
?>