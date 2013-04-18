<?php
global $MultipleSidebarsAlerta;
if (isset($_POST['opciones']) && $_POST['opciones'] == "multiple-sidebars-grabar") {
	if (!wp_verify_nonce($_POST['MultipleSidebars_opciones'], 'multiple-sidebars-opciones')){
		$MultipleSidebarsAlerta->agregar(__('Error al guardar los datos. Problema de verificación.',MULTIPLESIDEBARS_LANG));
		return;
	}
	if ($_POST['mssidebarsHome'] == "") {
		$sidebarsHome = "multiple-sidebars-default";
	} else {
		$sidebarsHome = $_POST['mssidebarsHome'];
	}
	
	$MultipleSidebarsAlerta->agregar(__('Opciones guardadas',MULTIPLESIDEBARS_LANG));
	
	if(update_option("MultipleSidebarsHome", $sidebarsHome)){
		$MultipleSidebarsAlerta->agregar(__('Home',MULTIPLESIDEBARS_LANG));
	}
	
	if ($_POST['mssidebarsSearch'] == "") {
		$sidebarsSearch = "multiple-sidebars-default";
	} else {
		$sidebarsSearch = $_POST['mssidebarsSearch'];
	}
	if(update_option("MultipleSidebarsSearch", $sidebarsSearch)){
		$MultipleSidebarsAlerta->agregar(__('Buscador',MULTIPLESIDEBARS_LANG));
	}
}
//print_r($_POST);
?>
<form method="post" action="">
	<div class="wrap">
		<div id="icon-options-general" class="icon32">
			<br/>
		</div><h2><?php _e('Opciones de MultipleSidebars',MULTIPLESIDEBARS_LANG);?></h2>
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
		<table class="opciones">
			<tr>
				<td colspan="2"><?php _e('Opciones de la Home',MULTIPLESIDEBARS_LANG);?></td>
			</tr>
			<tr>
				<td width="200px;"><?php _e('Sidebars',MULTIPLESIDEBARS_LANG);?></td>
				<td width="300px"><?php
				$sidebarsHome = get_option("MultipleSidebarsHome");
				if (!$sidebarsHome) {
					$sidebarsHome = "";
				}
				$MultipleSidebarHome = new MultipleSidebars();
				$MultipleSidebarHome -> MS_view_sidebars($sidebarsHome,"Home");
				
				?></td>
			</tr>
			<tr>
				<td colspan="2"><?php _e('Opciones de la búsqueda',MULTIPLESIDEBARS_LANG);?></td>
			</tr>
			<tr>
				<td width="200px;"><?php _e('Sidebars',MULTIPLESIDEBARS_LANG);?></td>
				<td width="300px"><?php
				$sidebarsSearch = get_option("MultipleSidebarsSearch");
				if (!$sidebarsSearch) {
					$sidebarsSearch = "";
				}
				$MultipleSidebarSearch = new MultipleSidebars();
				$MultipleSidebarSearch -> MS_view_sidebars($sidebarsSearch,"Search");
				
				wp_nonce_field('multiple-sidebars-opciones', 'MultipleSidebars_opciones');
				?></td>
			</tr>
		</table>
		<div class="clear"></div>
		<input type="hidden" name="opciones" value="multiple-sidebars-grabar" />
		<input type="submit" name="grabar" value="<?php _e('Grabar',MULTIPLESIDEBARS_LANG);?>" class="button-primary" />
	</div>
</form>
