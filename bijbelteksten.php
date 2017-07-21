<?php
/*
 * Plugin Name: Bijbelteksten
 * Version: 1.3
 * Plugin URI: http://www.bijbelsecultuur.nl
 * Description: Verandert bijbelverwijzingen (automatisch) in links naar debijbel.nl.
 * Author: Ruben Hadders
 * Author URI: http://www.bijbelsecultuur.nl
 * License:       GNU General Public License, v2 (or newer)
 * License URI:  http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * 
 * Original PERL MovableType Plugin Copyright 2002-2004 Dean Peters
 * Port to PHP WordPress Plugin Copyright Glen Davis
 * Dutch Bijbeltesten Wordpress Plugin Copright 2015 Ruben Hadders
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *  
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
*/

/**
 * Get DeBijbel.nl translations
 */
function bijbelteksten_get_dutch_translations() {
	$translations_dutch = array(
		'NBV'=>'Nieuwe Bijbelvertaling - gratis account noodzakelijk', // NBG
        'NBG51'=>'NBG-1951 Vertaling - gratis account noodzakelijk', // NBG
        'SVJ'=>'Statenvertaling editie 1977 - gratis account noodzakelijk', // NBG
        'HSV'=>'Herziene Statenvertaling - alleen voor NBG leden', // NBG
        'BGT'=>'Bijbel in Gewone Taal - alleen voor NBG leden', // NBG
        'GNB'=>'Groot Nieuws Bijbel - alleen voor NBG leden', // NBG
        'NFB'=>'Nije Fryske Bibeloersetting - alleen voor NBG leden', // NBG
        'GRB'=>'Biebel in t Grunnegers - alleen voor NBG leden', // NBG
        'GRB'=>'Biebel in t Grunnegers - alleen voor NBG leden', // NBG
        'WV2012'=>'Willibrordvertaling editie 2012 - alleen voor NBG leden', //NBG
        'AAN,NBV'=>'NBV + studieaantekeningen - alleen voor NBG leden', // NBG
        'NBV,NBG51'=>'NBV + NBG51 naast elkaar - alleen voor NBG leden', // NBG
        'NBV,HSV'=>'NBV + HSV naast elkaar - alleen voor NBG leden', // NBG
        'NBV,NBG51,SVJ'=>'NBV + NBG51 + SV naast elkaar - alleen voor NBG leden', // NBG
        'NBV,NBG51,SVJ,HSV'=>'NBV + NBG51 + SV + HSV naast elkaar - alleen voor NBG leden', // NBG
        'HSV,AAN'=>'HSV + NBV studieaantekeningen - alleen voor NBG leden', // NBG
        'NBV,NBG51,BGT'=>'NBV + NBG51 + BGT naast elkaar - alleen voor NBG leden' // NBG
	);
	return apply_filters( 'bijbelteksten_translations_dutch', $translations_dutch );
};
global $bijbelteksten_translations_dutch;
$bijbelteksten_translations_dutch = bijbelteksten_get_dutch_translations();


/**
 * Get default options
 */
function bijbelteksten_get_options_default() {
	$default_options = array(
		'default_translation' => 'NBV',
		'dynamic_substitution' => true,
		'link_css_class' => 'bijbelteksten',
		'link_target_blank' => false,
        'newsletter' => 'uw@emailadres.nl'
	);
	return apply_filters( 'bijbelteksten_default_options', $default_options );
};
global $bijbelteksten_options_default;
$bijbelteksten_options_default = bijbelteksten_get_options_default();


/**
 * Add Plugin options to variable array
 */	
function bijbelteksten_get_options() {
	// Get the option defaults
	$option_defaults = bijbelteksten_get_options_default();
	// Globalize the variable that holds the Theme options
	global $bijbelteksten_options;
	// Parse the stored options with the defaults
	$bijbelteksten_options = wp_parse_args( get_option( 'plugin_bijbelteksten_options', array() ), $option_defaults );
	// Return the parsed array
	return $bijbelteksten_options;
}
global $bijbelteksten_options;
$bijbelteksten_options = bijbelteksten_get_options();


/**
 * Bijbelteksten admin options hook
 */
global $bijbelteksten_admin_options_hook;

/**
 * Plugin initialization function
 * Defines default options as an array

 */	
function bijbelteksten_init() {

	// set options equal to defaults
	global $bijbelteksten_options_default;
	global $bijbelteksten_options;
	$bijbelteksten_options = get_option( 'plugin_bijbelteksten_options' );
	
	$bijbelteksten_options_initial = ( ! $bijbelteksten_options ? $bijbelteksten_options_default : $bijbelteksten_options );
	
	// if options exist from previous Plugin version, update options array with old option settings
	// and delete old database options
	foreach( $bijbelteksten_options_initial as $key => $value ) {
		if( $existing = get_option( 'bijbelteksten_' . $key ) ) {
			$bijbelteksten_options_initial[$key] = $existing;
			delete_option( 'bijbelteksten_' . $key );
		}
	}
	
	// Add/update the database options array
	update_option( 'plugin_bijbelteksten_options', $bijbelteksten_options_initial );
}

/**
 * Plugin admin options page
 */	
// Function to add admin options page
function bijbelteksten_menu() {
	global $bijbelteksten_admin_options_hook;
	$bijbelteksten_admin_options_hook = add_options_page('Options', 'Bijbelteksten', 'manage_options', 'bijbelteksten', 'bijbelteksten_admin_options_page');
}
// Admin options page markup 
function bijbelteksten_admin_options_page() {
	include_once( 'bijbelteksten_admin_options_page.php' );
}

// Codex Reference: http://codex.wordpress.org/Settings_API
// Codex Reference: http://codex.wordpress.org/Data_Validation
// Reference: http://ottopress.com/2009/wordpress-settings-api-tutorial/
// Reference: http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/
function bijbelteksten_admin_init(){
	include_once( 'bijbelteksten_admin_options_init.php' );
}

// Admin options page contextual help markup
function bijbelteksten_contextual_help( $contextual_help, $screen_id, $screen ) {		
	global $bijbelteksten_admin_options_hook;
	include_once( 'bijbelteksten_admin_options_help.php' );
	if ( $screen_id == $bijbelteksten_admin_options_hook ) {
		$contextual_help = $text;
	}
	
return $contextual_help;
}

/**
 * Link to admin options page in Plugin Action links on Manage Plugins page
 */	
function bijbelteksten_actlinks( $links ) {
	$bijbelteksten_settings_link = '<a href="options-general.php?page=bijbelteksten">Instellingen</a>'; 
	$links[] = $bijbelteksten_settings_link;
	return $links; 
}



/**
 * function bijbeltekst()
 */	
function bijbeltekst( $text = '', $bible = NULL ) {
	
	global $bijbelteksten_options;
	
	if ( ! isset( $bible ) ) {
		$bible = $bijbelteksten_options['default_translation'];
	}
    // skip everything within a hyperlink, a <pre> block, a <code> block, or a tag
    // we skip inside tags because something like <img src="nicodemus.jpg" alt="John 3:16"> should not be messed with
	$anchor_regex = '<a\s+href.*?<\/a>';
	$pre_regex = '<pre>.*<\/pre>';
	$code_regex = '<code>.*<\/code>';
	$other_plugin_regex= '\[bible\].*\[\/bible\]'; // for the ESV Wordpress plugin (out of courtesy)
	$other_plugin_block_regex='\[bibleblock\].*\[\/bibleblock\]'; // ditto
	$tag_regex = '<(?:[^<>\s]*)(?:\s[^<>]*){0,1}>'; // $tag_regex='<[^>]+>';
	$split_regex = "/((?:$anchor_regex)|(?:$pre_regex)|(?:$code_regex)|(?:$other_plugin_regex)|(?:$other_plugin_block_regex)|(?:$tag_regex))/i";
	$parsed_text = preg_split( $split_regex, $text, -1, PREG_SPLIT_DELIM_CAPTURE );
	$linked_text = '';

	while ( list( $key, $value ) = each( $parsed_text ) ) {
      if ( preg_match( $split_regex, $value ) ) {
         $linked_text .= $value; // if it is an HTML element or within a link, just leave it as is
      } else {
        $linked_text .= bijbeltekstAddLinks( $value, $bible ); // if it's text, parse it for Bible references
      }
  }
  return $linked_text;
}

/**
 * function bijbeltekstAddLinks()
 */	
function bijbeltekstAddLinks( $text = '', $bible = NULL ) {

	global $bijbelteksten_translations_dutch;
	global $bijbelteksten_options;

	if ( ! isset( $bible ) ) {
		$bible = $bijbelteksten_options['default_translation'];
	}
	// Regular Expression for Book Volume strings
    $volume_regex = '1|2|3|I|II|III|1st|2nd|3rd|First|Second|Third';
	// Regular Expression for OT Book full name strings
    $book_ot_regex  = 'Genesis|Exodus|Leviticus|Numeri|Deuteronomium|Jozua|Richteren|Ruth|Samuel|Koningen|Kronieken|Ezra|Nehemiah|Esther';
    $book_ot_regex .= '|Job|Psalmen|Spreuken|Prediker|Hooglied|Jesaja|Jeremia|Klaagliederen|Ezechiël|Daniël|Hosea|Joël|Amos|Obadja|Jona|Micha|Nahum|Habakkuk|Sefenja|Haggaï|Zacharia|Maleachi';
	// Regular Expression for NT Book full name strings
    $book_nt_regex = '|Mat+theüs|Markus|Lukas|Johannes|Handelingen|Romeinen|Korinthe|Korinthiërs|Korinthe|Galaten|Efeziërs|Efeze|Phillipenzen|Filippiërs|Fillipenzen|Kolossers|Kolossenzen|Thessalonikers|Thessalonicenzen|Timotheüs|Titus|Filemon|Hebreeën|Jacobus|Jakobus|Petrus|Judas|Openbaring|Openbaringen';
	// Separate abbreviations from full names in order to find abbreviated book names followed by a period
	// Regular Expression for OT Book abbreviation strings
    $abbrev_ot_regex  = 'G(?:e?)n|Ex?|L(?:e?)v|N(?:u?)m|D(?:e?)(?:u?)t|J(?:o?)z|R(?:i?)(?:e?)?|Ru(?:t?)|S(?:a?)m|Ko(?:n?)|Kr(?:on?)?|Ezr|Neh|Est';
    $abbrev_ot_regex .= '|Jb|Ps|Sp(?:r?)?|Pr(?:ed?)?|H(?:oog?)?l|J(?:e?)s|J(?:e?)r|K(?:laag?)?l|Eze(?:ch?)|D(?:a?)n|Hos|Joe|Am(?:o?)|Ob(?:a?)|J(?:o?)n|Mi(?:c?)|Na(?:h?)|Hab|Z(?:e?)f|Hag|Zach|Mal';
	// Regular Expression for NT Book abbreviation strings
    $abbrev_nt_regex = '|Mat+|Mar?|Lu(?:k?)?|Joh|H(?:a?)nd|R(?:o)?m|Kor|Gal|Ef(?:e?)|Kol|Col|Fil?|Phil?|Thess?|Tim|Tit|Filem|Heb|Ja?|Pet(?:r?)|Ju(?:d?)|Op(?:enb?)?';
	
	// Combine Regular Expressions for OT/NT Book full name strings
	$book_regex = $book_ot_regex . $book_nt_regex;	
	// Combine Regular Expressions for OT/NT Book abbreviation strings
	$abbrev_regex = $abbrev_ot_regex . $abbrev_nt_regex;
	// Combine Regular Expressions for OT/NT Book full-name and abbreviation strings
    $book_regex='(?:'.$book_regex.')|(?:'.$abbrev_regex.')\.?';
	// Regular Expression for Chapter/Verse references
    $verse_regex="\d{1,3}(?::\d{1,3})?(?:\s?(?:[-&,]\s?\d+))*";

	// non Bible Gateway translations are all together at the end to make it easier to maintain the list
	$translation_regex = implode('|',array_keys($bijbelteksten_translations_dutch)); // makes it look like 'NIV|KJV|ESV' etc

	// note that this will be executed as PHP code after substitution thanks to the /e at the end!
    /*
     * Comment this out for now. I'll figure it out later.
     * 
    $passage_ot_regex = '/(?:('.$volume_regex.')\s)?('.$book_ot_regex.')\s('.$verse_regex.')(?:\s?[,-]?\s?((?:'.$translation_regex.')|\s?\((?:'.$translation_regex.')\)))?/e';
    $passage_regex = '/(?:('.$volume_regex.')\s)?('.$book_nt_regex.')\s('.$verse_regex.')(?:\s?[,-]?\s?((?:'.$translation_regex.')|\s?\((?:'.$translation_nt_regex.')\)))?/e';
    */
    $passage_regex = '/(?:('.$volume_regex.')\s)?('.$book_regex.')\s('.$verse_regex.')(?:\s?[,-]?\s?((?:'.$translation_regex.')|\s?\((?:'.$translation_regex.')\)))?/e';

    $replacement_regex = "bijbeltekstLinkReference('\\0','\\1','\\2','\\3','\\4','$bible')";

    $text = preg_replace( $passage_regex, $replacement_regex, $text );

    return $text; // TODO: make this an array, to return text, plus OT/NT (for original languages)
}

function bijbeltekstLinkReference( $reference='', $volume='', $book='', $verse='', $translation='', $user_translation='' ) {
	global $bijbelteksten_options;
	
	$link_target = ( $bijbelteksten_options['link_target_blank'] ? ' target="_blank"' : '' );
	
    if ( $volume ) {
       $volume = str_replace('III','3',$volume);
	   $volume = str_replace('Derde','3',$volume);   
       $volume = str_replace('II','2',$volume);
	   $volume = str_replace('Tweede','2',$volume);      
       $volume = str_replace('I','1',$volume);
	   $volume = str_replace('Eerste','1',$volume);      
       $volume = $volume{0}; // will remove st,nd,and rd (presupposes regex is correct)
    }
	
	//catch an obscure bug where a sentence like "The 3 of us went downtown" triggers a link to 1 Thess 3
	if ( ! strcmp( strtolower( $book ), "the" ) && $volume=='' ) {
		return $reference;
	}

   if( ! $translation ) {
         if ( ! $user_translation ) {
             $translation = $bijbelteksten_options['default_translation'];
         } else {
             $translation = $user_translation;
         }
   } else {
       $translation = trim( $translation, ' ()' ); // strip out any parentheses that might have made it this far
   }

   // if necessary, just choose part of the verse reference to pass to the web interfaces
   // they wouldn't know what to do with John 5:1-2, 5, 10-13 so I just give them John 5:1-2
   // this doesn't work quite right with something like 1:5,6 - it gets chopped to 1:5 instead of converted to 1:5-6
   if ( $verse ) {
       $verse = strtok( $verse, ',& ' );
   }


   switch ($translation) {
        default:
             $link = "https://www.debijbel.nl/bijbel/zoeken/$translation/";
             $title = 'Bijbeltekst lezen via debijbel.nl';
             $link = sprintf( '<a class="%s" %s href="%s%s" title="%s">%s</a>', $bijbelteksten_options['link_css_class'],  $link_target, $link, htmlentities( urlencode( trim( "$volume $book $verse" ) ) ), $title, trim( $reference ) );
             break;
    }
	
	
return $link;
}

function bijbeltekstPost($post_ID) {
    global $wpdb;
	
	$tableposts=$wpdb->posts;

    $postdata=$wpdb->get_row("SELECT * FROM $tableposts WHERE ID = '$post_ID'");

    $content = bijbeltekst($postdata->post_content);

    $wpdb->query("UPDATE $tableposts SET post_content = '$content' WHERE ID = '$post_ID'");
    
    return $post_ID;
}

function bijbeltekstComment($comment_ID) {
    global $wpdb;
    
	$tablecomments=$wpdb->comments;

    $postdata=$wpdb->get_row("SELECT * FROM $tablecomments WHERE ID = '$comment_ID'");

    $content = bijbeltekst($postdata->comment_content);

    $wpdb->query("UPDATE $tablecomments SET comment_content = '$content' WHERE ID = '$comment_ID'");
    
    return $comment_ID;
}


##### ADD ACTIONS AND FILTERS
// Initialize Plugin options
add_action('activate_bijbelteksten/bijbelteksten.php', 'bijbelteksten_init' );
// add the admin settings and such
add_action('admin_init', 'bijbelteksten_admin_init');
// Load the Admin Options page
add_action('admin_menu', 'bijbelteksten_menu');
// Add contextual help to Admin Options page
add_action('contextual_help', 'bijbelteksten_contextual_help', 10, 3);
// Add a Settings link to Plugin Action Links on Manage Plugins page
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'bijbelteksten_actlinks', 10, 1 );

// Update content per Dynamic/Static mode setting
if ( isset( $bijbelteksten_options['dynamic_substitution'] ) && $bijbelteksten_options['dynamic_substitution'] ) {
	add_filter('the_content','bijbeltekst');
	add_filter('comment_text','bijbeltekst');
} else {
	add_action('publish_post','bijbeltekstPost');
	add_action('comment_post','bijbeltekstComment');
	add_action('edit_post','bijbeltekstPost');
	add_action('edit_comment','bijbeltekstComment');
	// note, adding the edit_post action guarantees that if you add or change a scripture reference the link will be inserted
	// HOWEVER, it will prevent you from removing a link you don't want!
}
?>