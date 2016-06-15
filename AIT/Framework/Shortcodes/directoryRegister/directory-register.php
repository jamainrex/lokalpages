<?php
function directory_register( $atts ) {
	$atts = extract( shortcode_atts( array( 'id'=>'ait-dir-register-shortcode' ),$atts ) );
	ob_start();
	?>
	<!-- register -->
	<div id="<?php echo $id; ?>">
		<form method="post" action="<?php echo home_url('/?dir-register=register'); ?>" class="wp-user-form">
			<div class="register-username">
				<label for="user_login"><?php _e('Username','ait'); ?> </label>
				<input type="text" name="user_login" value="" size="20" id="user_login_register_shortcode" tabindex="101" />
			</div>
			<div class="register-fname">
				<label for="user_fname"><?php _e('First Name','ait'); ?> </label>
				<input type="text" name="user_fname" value="" size="20" id="user_fname_register_widget" tabindex="102" />
			</div>
			<div class="register-lname">
				<label for="user_lname"><?php _e('Last Name','ait'); ?> </label>
				<input type="text" name="user_lname" value="" size="20" id="user_lname_register_widget" tabindex="103" />
			</div>
			<div class="register-email">
				<label for="user_email"><?php _e('Email','ait'); ?> </label>
				<input type="text" name="user_email" value="" size="25" id="user_email_register_shortcode" tabindex="104" />
			</div>
			<div class="register-agree">
				<a href="http://lokalpages.com/terms-and-policy/" style="margin-left: 60px; position: absolute">Agree to Terms?</a>
				<label for="user_agree"><input type="checkbox" id="user-agree" name="user_agree" value="user_agree" onclick="manageSubmit();"></label>	
			</div>
			<div class="register-role">
				<label for="directory-role"><?php _e('Package','ait'); ?> </label>
				<select name="directory-role">
				<?php
				global $aitThemeOptions;
				$currency = (isset($aitThemeOptions->members->paypalCurrencyCode)) ? $aitThemeOptions->members->paypalCurrencyCode : 'USD';
				for ($i=1; $i <= 5; $i++) {
					$roleEnable = 'role'.$i.'Enable';
					$roleName = 'role'.$i.'Name';
					$rolePrice = 'role'.$i.'Price';
					$free = (trim($aitThemeOptions->members->$rolePrice) == '0') ? true : false;
					if(isset($aitThemeOptions->members->$roleEnable)){
						echo '<option value="directory_'.$i.'"';
						if($free) { echo ' class="free"'; }
						echo '>'.$aitThemeOptions->members->$roleName;
						if(!$free) { echo ' ('.$aitThemeOptions->members->$rolePrice.' '.$currency.')'; } else { echo ' ('.__('Free','ait').')'; }
						echo '</option>';
					}
				}
				?>
				</select>
			</div>
			<div class="login-fields">
				<?php do_action('register_form'); ?>
				<input type="submit" id="user-submit" name="user-submit" value="<?php _e('Sign up!', 'ait'); ?>" class="user-submit" tabindex="103" disabled="true" />
				<input type="hidden" name="redirect_to" value="<?php echo home_url(); ?>" />
				<input type="hidden" name="user-cookie" value="1" />
			</div>
		</form>
	</div>
	<script>
	jQuery(document).ready(function($) {
		var tabRegisterShortcode = $('#<?php echo $id; ?>'),
			selectShortcode = tabRegisterShortcode.find('select[name=directory-role]'),
			buttonSubmitShortcode = tabRegisterShortcode.find('input[name=user-submit]'),
			freeTitleShortcode = '<?php _e('Sign up','ait'); ?>',
			buyTitleShortcode = '<?php _e('Buy with PayPal','ait'); ?>';
		if(selectShortcode.find('option:selected').hasClass('free')){
			buttonSubmitShortcode.val(freeTitleShortcode);
		} else {
			buttonSubmitShortcode.val(buyTitleShortcode);
		}
		selectShortcode.change(function(event) {
			if(selectShortcode.find('option:selected').hasClass('free')){
				buttonSubmitShortcode.val(freeTitleShortcode);
			} else {
				buttonSubmitShortcode.val(buyTitleShortcode);
			}
		});
	});
	</script>
	
	<script>
		function manageSubmit()
		{	
			if(document.getElementById("user-agree").checked==true)
				document.getElementById("user-submit").disabled=false;
			else
				document.getElementById("user-submit").disabled=true;
		}
	</script>
	<?php
	$output = ob_get_contents();
	ob_end_clean();

	return $output;
}
add_shortcode( "directory_register", "directory_register" );
