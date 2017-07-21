<div>
    <p><?php echo '<img src="' . plugins_url( 'logo.png', __FILE__ ) . '" > '; ?></p> 
    <p>Deze plugin verandert verwijzingen naar Bijbelteksten (bijv. Gen. 1:3) automatisch in links naar de betreffende bijbeltekst op de website van het Nederlands Bijbelgenootschap (<a href="http://www.debijbel.nl">www.debijbel.nl</a>).<br>Gemaakt door <b><a href="mailto:plugin@bijbelsecultuur.nl">Ruben Hadders</a></b>. Gratis aangeboden door de <b><a href="http://www.bijbelsecultuur.nl">Bijbelse Cultuurstichting</a></b> <br><br><font color="red">Belangrijk:</font><br>Alleen de Nieuwe Bijbelvertaling (NBV) kan door gebruikers vrij worden geraadpleegd. Kies je voor een andere Nederlandse vertaling dan zal de gebruiker een (gratis) account moeten aanmaken op www.debijbel.nl</p>
    <br><hr>
	<form action="options.php" method="post">
		<?php 
		settings_fields('plugin_bijbelteksten_options');
		do_settings_sections('bijbelteksten');
		?><br>
		<?php submit_button( __( 'Bewaar instellingen', 'bijbelteksten' ), 'primary', 'plugin_bijbelteksten_options[submit]', false ); ?>
		<?php submit_button( __( 'Standaardinstellingen herstellen', 'bijbelteksten' ), 'secondary', 'plugin_bijbelteksten_options[reset]', false ); ?>
	</form>
<p><br><br> <hr><br><?php
echo '<img src="' . plugins_url( 'bcs.png', __FILE__ ) . '" > '; ?><?php echo '<img src="' . plugins_url( 'nbg.png', __FILE__ ) . '" style="margin:0px 0px 0px 50px" > '; ?>
        </p>
</div>