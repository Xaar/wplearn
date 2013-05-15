<div class="wrap">
	<div id="icon-edit" class="icon32 icon32-posts-multiple-sidebars">
		<br>
	</div><h2><?php _e('Ayuda de MultipleSidebars', MULTIPLESIDEBARS_LANG);?></h2>
	<h3><?php  _e('Como crear nuevos contenedores de widgets (sidebars)', MULTIPLESIDEBARS_LANG);?></h3>
	<p>
		<?php _e('En la pestaña SIDEBARS, en el administrador, encontrarás una opción de AGREGAR SIDEBAR.', MULTIPLESIDEBARS_LANG);?>
	</p>
	<h3><?php _e('Para asignar un sidebar en determinada pantalla. (Entradas / Páginas / Entradas Customisables / Taxonomías / Categorías)', MULTIPLESIDEBARS_LANG);?></h3>
	<p>
		<?php _e('Cuando agregues una página, entrada o cualquier otro tipo de entrada, arriba de PUBLICAR encontrarás el selector de sidebars. Allí podrás arrastrar desde la parte de INACTIVOS a ACTIVOS para poder seleccionar el sidebar.', MULTIPLESIDEBARS_LANG);?>
	</p>
	<h3><?php _e('Para asignarlos en el template', MULTIPLESIDEBARS_LANG);?></h3>
	<p>
		<h4><?php _e('Opción 1', MULTIPLESIDEBARS_LANG);?></h4>
	</p>
	<p>
		<?php _e('Agregar el Widget MULTIPLE SIDEBARS en todos los contenedores que trae por defecto del tema.', MULTIPLESIDEBARS_LANG);?>
	</p>
	<p>
		<img src="<?php echo plugins_url();?>/multiple-sidebars/images/ayuda/ayuda.jpg" />
	</p>
	<p>
		<h4><?php _e('Opción 2', MULTIPLESIDEBARS_LANG);?></h4>
	</p>
	<p>
		<?php _e('En el archivo sidebars.php, reemplazar "dynamic_sidebar()" por "MS_dynamic_sidebar();"', MULTIPLESIDEBARS_LANG);?>
	</p>
	<p>
		<code>
			MS_dynamic_sidebar();
		</code>
	</p>
</div>