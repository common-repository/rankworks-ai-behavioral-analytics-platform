<?php

if( ! defined('ABSPATH') )
	exit; // Exit if accessed directly

?>

<div class="wrap">
	
	<div class="rankworks-connection-settings-container">
		
		<div class="rankworks-form-section">
			<?php
				
				$images_dir = rankworks_connection_dir_url . 'assets/images/';
				$settings = rankworks_connection::get_settings();
				
				$script_url = $settings['script_url'];
				$hide_css = '';
				$display_css = '';
				if( ! empty( $script_url ) )
				{
					$hide_css = "display: none;";
					$display_css = "display: flex;";
				}
				
			?>
				
				<div class="main-container mobile-form">
					<div class="create-accout-container" style="<?php echo esc_attr( $hide_css ); ?>">
						<div class="left-section" id="left-section">
							<img src="<?php echo esc_url( $images_dir ); ?>left-sec-img.png" alt="">
						</div>
			
						<div class="form-container">
							
							<div class="logo">
								<img src="<?php echo esc_url( $images_dir ); ?>form-logo.png" alt="RankWorks logo">
							</div>
			
							<div class="form">
								<div class="form-heading">
									<p>Create account</p>
								</div>
			
								<div class="single-field">
									<label>First name</label>
									<input type="text" id="first_name" required>
								</div>
			
								<div class="single-field">
									<label>Last name</label>
									<input type="text" id="last_name" required>
								</div>
			
								<div class="single-field">
									<label>Email</label>
									<input type="email" id="email" required>
								</div>
			
								<button id="btn_account_form_submit" >Next</button>
							</div>
						</div>
					</div>

					<div class="business-info-container" style="<?php echo esc_attr( $hide_css ); ?>">
						<div class="left-section">
							<img src="<?php echo esc_url( $images_dir ); ?>left-sec-img.png" alt="">
						</div>
			
						<div class="form-container">
							<span class="close-btn">
								<svg xmlns="http://www.w3.org/2000/svg" width="17" height="18" viewBox="0 0 17 18" fill="none">
									<line x1="16.3536" y1="1.35355" x2="0.353882" y2="17.3532" stroke="#001955"/>
									<line x1="0.353553" y1="0.646447" x2="16.3532" y2="16.6461" stroke="#001955"/>
								</svg>
							</span>
							<div class="logo">
								<img src="<?php echo esc_url( $images_dir ); ?>form-logo.png" alt="RankWorks logo">
							</div>
			
							<div class="form">
								<div class="form-heading">
									<p>Tell us about your business</p>
								</div>
			
								<div class="single-field custom-radio">
									<label for="s-type-1">
										eCommerce <input id="s-type-1" value="eCommerce" name="s-type" type="radio" required>
									</label>
									<label for="s-type-2">
										Service Website <input id="s-type-2" value="Service Website" name="s-type" type="radio" required>
									</label>
								</div>
			
								<div class="single-field" style="display: none;">
									<label>Website url</label>
									<input type="text" id="website_url" value="<?php echo esc_url( site_url() ); ?>">
								</div>
			
								<div class="single-field">
									<label>Company name</label>
									<input type="text" id="company_name" required>
								</div>
			
								<div class="single-field">
									<label>Industry type</label>
									<select id="industry_type" name="industry_type" required>
									<?php
										$cats = $this->get_business_categories();
										
										if( is_array( $cats ) )
										{
											foreach( $cats as $cat )
											{
												echo '<option value="' . esc_attr( $cat ) . '">' . esc_html( $cat ) . '</option>';
											}
										}
									?>
									</select>
									
								</div>
			
								<div class="single-field">
									<label>Service area</label>
									<select id="service_area" name="service_area" required>
									</select>
								</div>
			
								<button id="btn_business_info_submit" >Done</button>
							</div>
						</div>
					</div>

					<div class="validation-container" style="<?php echo esc_attr( $hide_css ); ?>">
						<div class="form-container validation-box">
							<span class="close-btn">
								<svg xmlns="http://www.w3.org/2000/svg" width="17" height="18" viewBox="0 0 17 18" fill="none">
									<line x1="16.3536" y1="1.35355" x2="0.353882" y2="17.3532" stroke="#001955"/>
									<line x1="0.353553" y1="0.646447" x2="16.3532" y2="16.6461" stroke="#001955"/>
								</svg>
							</span>
							
							<div class="logo">
								<img src="<?php echo esc_url( $images_dir ); ?>form-logo.png" alt="RankWorks logo">
							</div>
			
							<div class="form">
								<div class="form-heading">
									<p>Validation email</p>
								</div>
			
								<p>
									An email has been sent to the registered address. Please verify you email inbox. You can now close this page.
								</p>
			
								<button id="btn_close_validation" >Close</button>
							</div>
						</div>
					</div>

					<div class="login-container" style="<?php echo esc_attr( $display_css ); ?>">
						<div class="form-container validation-box login-box">
							<p class="connected">Connected</p>
							<div class="logo">
								<img src="<?php echo esc_url( $images_dir ); ?>form-logo.png" alt="RankWorks logo">
							</div>
			
							<div class="form">
								<button>Login</button>
							</div>
						</div>
					</div>
				</div>
			
		</div>
		
	</div>
	
</div>

<?php
