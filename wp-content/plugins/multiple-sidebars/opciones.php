<?php
global $MultipleSidebarsAlerta;
if (isset($_POST['opciones']) && $_POST['opciones'] == "multiple-sidebars-grabar") {
	if (!wp_verify_nonce($_POST['MultipleSidebars_opciones'], 'multiple-sidebars-opciones')){
		$MultipleSidebarsAlerta->agregar(__('Error al guardar los datos. Problema de verificación.',MULTIPLESIDEBARS_LANG));
		return;
	}
	$MultipleSidebarsAlerta->agregar(__('Opciones guardadas',MULTIPLESIDEBARS_LANG));
	
	$s = $_POST['mssidebarsHome']==""?"multiple-sidebars-default":$_POST['mssidebarsHome'];;
	if(update_option("MultipleSidebarsHome", $s)){
		$MultipleSidebarsAlerta->agregar(__('Home',MULTIPLESIDEBARS_LANG));
	}
	
	$s = $_POST['mssidebarsSearch']==""?"multiple-sidebars-default":$_POST['mssidebarsSearch'];;
	if(update_option("MultipleSidebarsSearch", $s)){
		$MultipleSidebarsAlerta->agregar(__('Buscador',MULTIPLESIDEBARS_LANG));
	}
	
	$s = $_POST['mssidebarsPostDefault']==""?"multiple-sidebars-default":$_POST['mssidebarsPostDefault'];;
	if(update_option("MultipleSidebarsPostDefault", $s)){
		$MultipleSidebarsAlerta->agregar(__('Entradas',MULTIPLESIDEBARS_LANG));
	}

	$s = $_POST['mssidebarsPageDefault']==""?"multiple-sidebars-default":$_POST['mssidebarsPageDefault'];;
	if(update_option("MultipleSidebarsPageDefault", $s)){
		$MultipleSidebarsAlerta->agregar(__('Páginas',MULTIPLESIDEBARS_LANG));
	}
	
	$s = $_POST['mssidebarsCategoryDefault']==""?"multiple-sidebars-default":$_POST['mssidebarsCategoryDefault'];;
	if(update_option("MultipleSidebarsCategoryDefault", $s)){
		$MultipleSidebarsAlerta->agregar(__('Categorías',MULTIPLESIDEBARS_LANG));
	}
	
}
//print_r($_POST);
?>
<form method="post" action="">
	<div class="wrap">
		<div id="icon-options-general" class="icon32 icon32-posts-post"></div>
		<h2><?php _e('Opciones de MultipleSidebars',MULTIPLESIDEBARS_LANG);?></h2>
		<br/>
		<?php
		if (isset($_POST['opciones']) && $_POST['opciones'] == "multiple-sidebars-grabar") {
			echo '<div id="message" class="updated below-h2">'.$MultipleSidebarsAlerta->mostrar().'</div>';
		}
		?>
		<style>
			table.opciones td {
				padding: 5px;
				vertical-align: top;
			}
		</style>
		<div id="poststuff">
			<table class="opciones">
				<tr>
					<td colspan="2"><?php _e('Asignar las barras laterales por defecto para:',MULTIPLESIDEBARS_LANG);?></td>
				</tr>
				<tr>
					<td width="300px">
						<div class="postbox">
							<h3><?php _e('Página de inicio',MULTIPLESIDEBARS_LANG);?></h3>
							<div class="inside">
								<?php
								$sidebarsHome = get_option("MultipleSidebarsHome");
								if (!$sidebarsHome) {
									$sidebarsHome = "";
								}
								$MultipleSidebarHome = new MultipleSidebars();
								$MultipleSidebarHome -> MS_view_sidebars($sidebarsHome,"Home");
								?>
							</div>
						</div>
					</td>
					<td width="300px">
						<div class="postbox">
							<h3><?php _e('Pantalla de búsqueda',MULTIPLESIDEBARS_LANG);?></h3>
							<div class="inside">
								<?php
								$sidebarsSearch = get_option("MultipleSidebarsSearch");
								if (!$sidebarsSearch) {
									$sidebarsSearch = "";
								}
								$MultipleSidebarSearch = new MultipleSidebars();
								$MultipleSidebarSearch -> MS_view_sidebars($sidebarsSearch,"Search");
								?>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td width="300px">
						<div class="postbox">
							<h3><?php _e('Entradas',MULTIPLESIDEBARS_LANG);?></h3>
							<div class="inside">
								<?php
								$ss = get_option("MultipleSidebarsPostDefault");
								if (!$ss) {
									$ss = "";
								}
								$MultipleSidebarPost = new MultipleSidebars();
								$MultipleSidebarPost -> MS_view_sidebars($ss,"PostDefault");
								?>
							</div>
						</div>
					</td>
					<td width="300px">
						<div class="postbox">
							<h3><?php _e('Páginas',MULTIPLESIDEBARS_LANG);?></h3>
							<div class="inside">
								<?php
								$ss = get_option("MultipleSidebarsPageDefault");
								if (!$ss) {
									$ss = "";
								}
								$MultipleSidebarPage = new MultipleSidebars();
								$MultipleSidebarPage -> MS_view_sidebars($ss,"PageDefault");
								?>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td width="300px">
						<div class="postbox">
							<h3><?php _e('Categorías',MULTIPLESIDEBARS_LANG);?></h3>
							<div class="inside">
								<?php
								$ss = get_option("MultipleSidebarsCategoryDefault");
								if (!$ss) {
									$ss = "";
								}
								$MultipleSidebarCat = new MultipleSidebars();
								$MultipleSidebarCat -> MS_view_sidebars($ss,"CategoryDefault");
								?>
							</div>
						</div>
					</td>
					<td></td>
				</tr>
			</table>
		</div>
		<div class="clear"></div>
		<?php wp_nonce_field('multiple-sidebars-opciones', 'MultipleSidebars_opciones');?>
		<input type="hidden" name="opciones" value="multiple-sidebars-grabar" />
		<input type="submit" name="grabar" value="<?php _e('Grabar',MULTIPLESIDEBARS_LANG);?>" class="button-primary" />
	</div>
</form>
