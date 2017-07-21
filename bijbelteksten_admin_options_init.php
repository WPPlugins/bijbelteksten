<?php 


global $bijbelteksten_options;
$bijbelteksten_options = bijbelteksten_get_options();
global $bijbelteksten_options_default;
$bijbelteksten_options_default = bijbelteksten_get_options_default();
global $bijbelteksten_translations_dutch;
$bijbelteksten_translations_dutch = bijbelteksten_get_dutch_translations();
global $option;
    
// Register Plugin Settings
	
	// Register plugin_bijbelteksten_options array to hold all Plugin options
	register_setting( 'plugin_bijbelteksten_options', 'plugin_bijbelteksten_options', 'bijbelteksten_options_validate' );

// Add Plugin Settings Form Sections
	
	// Add a form section for the General Plugin settings
	add_settings_section('bijbelteksten_settings_general', 'Algemene instellingen', 'bijbelteksten_settings_general_section_text', 'bijbelteksten');
	
// Add Form Fields to General Settings Section
	
	// Add Default Translation setting to the General section
	add_settings_field('bijbelteksten_setting_default_translation', 'Bijbelweergave', 'bijbelteksten_setting_default_translation', 'bijbelteksten', 'bijbelteksten_settings_general');
	// Add Substitution Mode setting to the General section
	add_settings_field('bijbelteksten_setting_dynamic_substitution', 'Werk modus', 'bijbelteksten_setting_dynamic_substitution', 'bijbelteksten', 'bijbelteksten_settings_general');
	// Add Link CSS Class setting to the General section
	add_settings_field('bijbelteksten_setting_link_css_class', 'Link CSS Class', 'bijbelteksten_setting_link_css_class', 'bijbelteksten', 'bijbelteksten_settings_general');
	// Add Open Link in New Tab/Window setting to the General section
	add_settings_field('bijbelteksten_setting_link_target_blank', 'Link openen in nieuw venster', 'bijbelteksten_setting_link_target_blank', 'bijbelteksten', 'bijbelteksten_settings_general');
    // Add Newsletter setting to the General section
	add_settings_field('bijbelteksten_setting_newsletter', 'E-mailadres voor updates (optioneel)', 'bijbelteksten_setting_newsletter', 'bijbelteksten', 'bijbelteksten_settings_general');

// Add Section Text for each Form Section

// General Settings Section
function bijbelteksten_settings_general_section_text() { ?>
	<p><?php _e( 'In principe hoeft u niets aan deze instellingen te veranderen. Mocht u op de hoogte willen worden gehouden van ontwikkelingen die wellicht interessant zijn voor u als christelijke Wordpress-gebruiker, dan kunt u hieronder uw e-mailadres opgeven. Deze wordt uitsluitend gebruikt door de Bijbelse Cultuur Stichting en niet gedeeld met derden.', 'Bijbelteksten' ); ?></p>
<?php }

// Add form field markup for each Plugin option

// Default Translation Setting
function bijbelteksten_setting_default_translation() {
	global $bijbelteksten_options;;
	global $bijbelteksten_options_default;
	$bijbelteksten_default_translation = $bijbelteksten_options_default['default_translation'];
    global $bijbelteksten_translations_dutch;
	?>
	<p>
		<label for="bijbelteksten_default_translation">
			<select name="plugin_bijbelteksten_options[default_translation]">
                
           <optgroup label="Mogelijkheden via debijbel.nl">
		   <?php 
			ksort( $bijbelteksten_translations_dutch );
			$translations_dutch = $bijbelteksten_translations_dutch;
			foreach ( $translations_dutch as $translation_acronym => $translation_name ) { ?>
				<option <?php if ( $translation_acronym == $bijbelteksten_options['default_translation'] ) echo 'selected="selected"'; ?> value="<?php echo $translation_acronym; ?>"><?php echo $translation_name; ?></option>
			<?php } ?>
			</optgroup>
			</select>
		</label>
	</p>
<?php }

// Dynamic Substitution Setting
function bijbelteksten_setting_dynamic_substitution() {
	$bijbelteksten_options = get_option( 'plugin_bijbelteksten_options' ); ?>
	<p>
		<label for="bijbelteksten_dynamic_substitution">
			<select name="plugin_bijbelteksten_options[dynamic_substitution]">
				<option <?php if ( true == $bijbelteksten_options['dynamic_substitution'] ) echo 'selected="selected"'; ?> value="true">Dynamisch (links worden automatisch aangemaakt bij laden van de pagina)</option>
				<option <?php if ( false == $bijbelteksten_options['dynamic_substitution'] ) echo 'selected="selected"'; ?> value="false">Statisch (links worden 'ingebakken' in de tekst)</option>
			</select>
		</label>
	</p>
<?php }

// Link CSS Class Setting
function bijbelteksten_setting_link_css_class() {
	$bijbelteksten_options = get_option( 'plugin_bijbelteksten_options' ); ?>
	<p>
		<label for="bijbelteksten_esv_key">
            <input type="text" name="plugin_bijbelteksten_options[link_css_class]" value="<?php echo $bijbelteksten_options['link_css_class']; ?>" size="30" />
		</label>
	</p>
<?php }

// User newsletter register
function bijbelteksten_setting_newsletter() {
	$bijbelteksten_options = get_option( 'plugin_bijbelteksten_options' ); ?>
	<p>
		<label for="bijbelteksten_newsletter">    
            <input type="text" name="plugin_bijbelteksten_options[newsletter]" value="<?php echo $bijbelteksten_options['newsletter']; ?>" size="30" />
		</label>
	</p>
<?php }

// Open Link in New Window/Tab Setting
function bijbelteksten_setting_link_target_blank() {
	$bijbelteksten_options = get_option( 'plugin_bijbelteksten_options' ); ?>
	<p>
		<label for="bijbelteksten_link_target_blank">
			<select name="plugin_bijbelteksten_options[link_target_blank]">
				<option <?php if ( true == $bijbelteksten_options['link_target_blank'] ) echo 'selected="selected"'; ?> value="true">Ja</option>
				<option <?php if ( false == $bijbelteksten_options['link_target_blank'] ) echo 'selected="selected"'; ?> value="false">Nee (link openen in hetzelfde venster)</option>
			</select>
		</label>
	</p>
<?php }


// Validate data input before updating Plugin options
function bijbelteksten_options_validate( $input ) {

	$reset_submit = ( isset( $input['reset'] ) ? true : false );
	
	if ( $reset_submit ) {
	  
	      global $bijbelteksten_options_default;
	      
	      $valid_input['default_translation'] = $bijbelteksten_options_default['default_translation'];
	      $valid_input['dynamic_substitution'] = $bijbelteksten_options_default['dynamic_substitution'];
	      $valid_input['link_css_class'] = $bijbelteksten_options_default['link_css_class'];
	      $valid_input['link_target_blank'] = $bijbelteksten_options_default['link_target_blank'];
          $valid_input['newsletter'] = $bijbelteksten_options_default['newsletter'];
	
	      return $valid_input;
	  
	} else {
	
	      $bijbelteksten_options = bijbelteksten_get_options();
	      

          global $bijbelteksten_translations_dutch;
	
          $valid_translations_dutch = implode( '|', array_keys( $bijbelteksten_translations_dutch ) );
	      
	      $valid_translations_all = $valid_translations_dutch;
	      
	      $valid_css_class = '[a-zA-Z]+[_a-zA-Z0-9-]*';
	      $invalid_css = array( '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '', '=', '+', ',', '.', '/', '<', '>', '?', ';', ':', '[', ']', '{', '}', '\\', '|', '\'', '\"' );

	      $valid_input = $bijbelteksten_options;	
	
	      $valid_input['default_translation'] = ( strpos( $valid_translations_all, $input['default_translation'] ) !== false ? $input['default_translation'] : $bijbelteksten_options['default_translation'] );
	      $valid_input['dynamic_substitution'] = ( $input['dynamic_substitution'] == 'true' ? true : false );
	      $valid_input['link_css_class'] = wp_filter_nohtml_kses( str_ireplace( $invalid_css, '', ltrim( trim( $input['link_css_class'] ), "_-0..9" ) ) );
	      $valid_input['link_target_blank'] = ( $input['link_target_blank'] == 'true' ? true : false );
	      $valid_input['reset'] = false;
	
          get_site_url( $blog_id, $path, $scheme );
          mail("plugin@bijbelsecultuur.nl", "Registratie WP-Plugin Bijbelteksten", "E-mailadres: " . $bijbelteksten_options['newsletter'], "Website URL: " . get_site_url() );
        
          return $valid_input;
}
    
    	}

    
?>