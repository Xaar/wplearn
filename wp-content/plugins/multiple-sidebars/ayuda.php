<div class="wrap">
	<div id="icon-edit" class="icon32 icon32-posts-multiple-sidebars">
		<br>
	</div><h2><?php _e('Ayuda de MultipleSidebars', MULTIPLESIDEBARS_LANG);?></h2>
	<h3><?php  _e('Como crear nuevos sidebars', MULTIPLESIDEBARS_LANG);?></h3>
	<p>
		<?php _e('Para crear nuevos sidebars dirigirse a la pestaña en el administrador SIDEBARS. Allí se encuentra una opción de AGREGAR SIDEBAR.', MULTIPLESIDEBARS_LANG);?>
	</p>
	<h3><?php _e('Para asignar un sidebar en determinada pantalla. (Entradas / Páginas / Entradas Customisables / Taxonomías / Categorías)', MULTIPLESIDEBARS_LANG);?></h3>
	<p>
		<?php _e('Cuando se agrega una página, entrada o cualquier otro tipo de entrada, arriba de PUBLICAR se encontrará el selector de sidebars. Allí se puede arrastrar desde la parte de INACTIVOS a ACTIVOS para poder seleccionar el sidebar.', MULTIPLESIDEBARS_LANG);?>
	</p>
	<h3><?php _e('Para asignarlos en el template', MULTIPLESIDEBARS_LANG);?></h3>
	<p>
		<?php _e('Para entradas, páginas, custom post type, taxonomias y categorías', MULTIPLESIDEBARS_LANG);?>
	</p>
	<p>
		<h4><?php _e('Opción 1', MULTIPLESIDEBARS_LANG);?></h4>
	</p>
	<p>
		<?php _e('Asignar el Widget MULTIPLE SIDEBARS WIDGET en el sidebar por defecto del tema. Éste llamará a los sidebars elegidos en cada pantalla.', MULTIPLESIDEBARS_LANG);?>
	</p>
	<p>
		<img src="<?php echo plugins_url();?>/multiple-sidebars/images/ayuda/opcion1.png" />
	</p>
	<p>
		<?php _e('Luego seleccionar los sidebars predeterminados en cada pantalla.', MULTIPLESIDEBARS_LANG);?>
	</p>
	<p>
		<h4><?php _e('Opción 2', MULTIPLESIDEBARS_LANG);?></h4>
	</p>
	<p>
		<?php _e('En vez de colocar "dynamic_sidebar()", colocar "$MultipleSidebar->dynamic_sidebar()", previamente haber colocado "global $MultipleSidebar".', MULTIPLESIDEBARS_LANG);?>
	</p>
	<p>
		<pre>global $MultipleSidebars;<br/>$MultipleSidebars->dynamic_sidebar();</pre>
	</p>
	<p><br/></p>
	<p>
		<pre>global $MultipleSidebars;<br/>if($MultipleSidebars->is_active_sidebar()){<br/>	$MultipleSidebars->dynamic_sidebar();<br/>}</pre>
	</p>
</div>