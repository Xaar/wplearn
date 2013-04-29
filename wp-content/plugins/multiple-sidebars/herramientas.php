<?php
class MultipleSidebars extends MultipleSidebarsCore{
	public function MultipleSidebars(){
		return new MultipleSidebarsCore(); 
	}
	/*
	 * post / pages / custom post type - Mostrar sidebars dinámicamente. Dependiendo de cuales estén seleccionados
	 */
	public function dynamic_sidebar() {
		global $post, $term, $cat;
		if (is_tax() or is_category()) {
			$cat_ID = get_query_var('cat');
			$sidebars = get_option("MultipleSidebars_taxonomy_sidebars_" . $cat_ID);
		} elseif (is_home() or is_front_page()) {
			$sidebars = get_option("MultipleSidebarsHome");
		} elseif (is_search()) {
			$sidebars = get_option("MultipleSidebarsSearch");
		} else {
			$sidebars = get_post_meta($post -> ID, "MultipleSidebars", true);
		}
		$success = false;
		$sidebars = empty($sidebars) ? array('multiple-sidebars-default') : explode(',', $sidebars);
		foreach ($sidebars as $sidebar) {
			if (is_active_sidebar($sidebar)) {
				$success = dynamic_sidebar($sidebar) ? true : $success;
			}
		}
		return $success;
	}
	
	
	/*
	 * post / pages / custom post type - Comprobar que existen uno o más sidebars y están en uso. Dependiendo de cuales estén seleccionados
	 */
	public function is_active_sidebar() {
		global $post, $term, $cat;
		if (is_tax() or is_category()) {
			$cat_ID = get_query_var('cat');
			$sidebars = get_option("MultipleSidebars_taxonomy_sidebars_" . $cat_ID);
		} elseif (is_home() or is_front_page()) {
			$sidebars = get_option("MultipleSidebarsHome");
		} elseif (is_search()) {
			$sidebars = get_option("MultipleSidebarsSearch");
		} else {
			$sidebars = get_post_meta($post -> ID, "MultipleSidebars", true);
		}
		$success = false;
		$sidebars = empty($sidebars) ? array('multiple-sidebars-default') : explode(',', $sidebars);
		foreach ($sidebars as $sidebar) {
			if (is_active_sidebar($sidebar)) {
				$success = $success || (is_active_sidebar($sidebar) ? true : false);
			}
		}
		return $success;
	}
}

function MS_dynamic_sidebar(){
	MultipleSidebars_dynamic_sidebar();
}

function MultipleSidebars_dynamic_sidebar(){
	global $MultipleSidebars;
	return $MultipleSidebars->dynamic_sidebar();
}

$MultipleSidebars = new MultipleSidebars();

class MultipleSidebarsAlerta{
	private $alertas = "";
	public function __construct(){}
	public function mostrar($echo = false){
		if(!$echo){
			return $this->alertas;
		}else{
			echo $this->alertas;
		}
	}
	public function agregar($alerta){
		$this->alertas .= "<p>".$alerta."</p>";
	}
}
$MultipleSidebarsAlerta = new MultipleSidebarsAlerta();

?>