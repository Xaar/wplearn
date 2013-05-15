<?php

class MultipleSidebarsCore {
	public function MultipleSidebarsCore(){
		add_action("init", array(&$this,"MS_load_textdomain"));
		add_action("admin_enqueue_scripts", array(&$this, "MS_load_script"));
			
		add_action("init",array(&$this,"MS_register_post_type"));
		add_action("init",array(&$this,"MS_register_sidebars"));
		
		//register_activation_hook( __FILE__, array(&$this,"MS_install"));
		//register_desactivation_hook( __FILE__, array(&$this,"MS_uninstall"));
		
		add_action('wp_ajax_crear_sidebar',array(&$this,'MS_ajax_new_sidebar'));

		add_action('add_meta_boxes', array(&$this, 'MS_add_metabox'));
		add_action('save_post', array(&$this, 'MS_save_metabox'));
		
		add_action('category_add_form_fields', array(&$this, 'MS_add_metabox_taxonomy'));
		add_action('category_edit_form_fields', array(&$this, 'MS_add_metabox_taxonomy'));

		add_action('created_term', array(&$this, 'MS_save_metabox_taxonomy'));
		add_action('edit_term', array(&$this, 'MS_save_metabox_taxonomy'));
		add_action('edited_category', array(&$this, 'MS_save_metabox_taxonomy'));
		
		add_action('MS_save',array(&$this, 'MS_save'));
		
	}
	public function MS_install(){
		
	}
	public function MS_uninstall(){
		
	}
	public function MS_save($type){
		switch($type){
			case "post":
				break;
			case "page":
				break;
			case "category":
				break;
			case "taxonomy":
				break;
		}
	}
	public function MS_load_textdomain() {
		if(!load_plugin_textdomain(MULTIPLESIDEBARS_LANG, false, "multiple-sidebars/languages")){
		}
	}
	public function MS_load_script() {
		wp_enqueue_script("jquery");
		wp_enqueue_script("jquery-ui-core");
		wp_enqueue_script("jquery-ui-sortable");
		
		wp_enqueue_script("msjs", plugins_url() . "/multiple-sidebars/js/msjs.js");
		wp_enqueue_style("MS_style", plugins_url() . "/multiple-sidebars/style.css");
	}
	public function MS_register_sidebars (){
		global $wpdb;
		$multiplesidebars = $wpdb -> get_results("SELECT ID,post_content,post_title FROM $wpdb->posts WHERE post_status = 'publish'	AND post_type = 'multiple-sidebars'");
		register_sidebar(array('name' => "Default", 'id' => "multiple-sidebars-default", 'description' => "Sidebar Default", 'before_widget' => '<div id="%1$s" class="widget-container multiple-sidebars widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h2 class="widget-title">', 'after_title' => '</h2>', ));
		if (!empty($multiplesidebars)) {
			foreach ((array) $multiplesidebars as $ms) {
				register_sidebar(array('name' => get_the_title($ms -> ID), 'id' => "multiplesidebars" . $ms -> ID, 'description' => apply_filters("the_content", $ms -> post_content), 'before_widget' => '<div id="%1$s" class="widget-container multiple-sidebars widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h2 class="widget-title">', 'after_title' => '</h2>', ));
			}
		}
	}
	public function MS_register_post_type(){
		if (function_exists("register_post_type")) {
			$labels = array('name' => __('Sidebars', MULTIPLESIDEBARS_LANG), 'singular_name' => __('Sidebar', MULTIPLESIDEBARS_LANG), 'add_new' => __('Agregar sidebar', MULTIPLESIDEBARS_LANG), 'add_new_item' => __('Agregar nuevo sidebar', MULTIPLESIDEBARS_LANG), 'edit_item' => __('Editar sidebar', MULTIPLESIDEBARS_LANG), 'new_item' => __('Nuevo sidebar', MULTIPLESIDEBARS_LANG), 'all_items' => __('Todos los sidebars', MULTIPLESIDEBARS_LANG), 'view_item' => __('Ver sidebar', MULTIPLESIDEBARS_LANG), 'search_items' => __('Buscar sidebars', MULTIPLESIDEBARS_LANG), 'not_found' => __('No se encontraron sidebar', MULTIPLESIDEBARS_LANG), 'not_found_in_trash' => __('No se encontraron sidebars en la papelera', MULTIPLESIDEBARS_LANG), 'parent_item_colon' => '', 'menu_name' => __('Sidebars', MULTIPLESIDEBARS_LANG));
			$args = array('labels' => $labels,'menu_icon'=>plugins_url()."/multiple-sidebars/images/sidebar.png", 'public' => true, 'publicly_queryable' => true, 'show_ui' => true, 'show_in_menu' => true, 'query_var' => true, 'rewrite' => true, 'capability_type' => 'post', 'has_archive' => false, 'hierarchical' => false, 'menu_position' => 83, 'supports' => array('title'));
			register_post_type('multiple-sidebars', $args);
			add_action('admin_menu', array(&$this, 'MS_view_options'));
		}
	}
	public function MS_ajax_new_sidebar(){
		if (isset($_POST['nuevo_sidebar']) && $_POST['nuevo_sidebar'] != "") {
			if (wp_verify_nonce($_POST['MultipleSidebars_crear_sidebar'], "multiple-sidebars")) {
				$post = array('post_content' => "", 'post_status' => 'publish', 'post_title' => $_POST['nuevo_sidebar'], 'post_type' => "multiple-sidebars", );
				$id_post = wp_insert_post($post);
				if($id_post){
					$dev = array("ID"=>$id_post,"post_title"=>$_POST['nuevo_sidebar']);
					echo json_encode($dev);
				}else{
					echo false;
				}
				exit();
				die();
			}
		}
		echo false;
		exit();
		die();
	}
	public function MS_add_metabox(){
		$post_types = get_post_types('');
		foreach ($post_types as $post_type) {
			if ($post_type != "multiple-sidebars") {
				add_meta_box('mostrar_metabox', __('Seleccionar sidebars', MULTIPLESIDEBARS_LANG), array(&$this, 'MS_view_metabox'), $post_type, 'side', 'high');
			}
		}
	}
	public function MS_view_metabox(){
		wp_nonce_field(plugin_basename(__FILE__), 'MultipleSidebars_noncename');
		if(isset($_GET['post'])){
			$sidebars = get_post_meta($_GET['post'], "MultipleSidebars", true);
		}else{
			$sidebars = "";
		}
		$this -> MS_view_sidebars($sidebars);
	}
	public function MS_save_metabox($post_id){
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return;

		if (!isset($_POST['MultipleSidebars_noncename']) || !wp_verify_nonce($_POST['MultipleSidebars_noncename'], plugin_basename(__FILE__)))
			return;

		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id))
				return;
			$sidebarsDefault = $this->MS_default_sidebars("PageDefault");
		} else {
			if (!current_user_can('edit_post', $post_id))
				return;
			$sidebarsDefault = $this->MS_default_sidebars("PostDefault");
		}
		//var_dump($_POST);
		//echo $this->MS_default_sidebars("PostDefault");
		//exit();
		if ($_POST['mssidebars'] == "" && $sidebarsDefault == "") {
			$sidebars = "multiple-sidebars-default,";
		} elseif($_POST['mssidebars'] == "" && $sidebarsDefault != ""){
			$sidebars = $sidebarsDefault;
		} else {
			$sidebars = $_POST['mssidebars'];
		}
		update_post_meta($post_id, "MultipleSidebars", $sidebars);
	}
	public function MS_save_post(){
		
	}
	public function MS_add_metabox_taxonomy($tag){
		wp_nonce_field(plugin_basename(__FILE__), 'MultipleSidebars_noncename');
		$t_id = $tag -> term_id;
		if($t_id){
			$sidebars = get_option("MultipleSidebars_taxonomy_sidebars_$t_id");
		}else{
			$sidebars = "";
		}
		$this -> MS_view_sidebars($sidebars);
	}
	public function MS_save_metabox_taxonomy($term_id){
		if (!isset($_POST['MultipleSidebars_noncename']) || !wp_verify_nonce($_POST['MultipleSidebars_noncename'], plugin_basename(__FILE__)))
			return;
		$sidebarsDefault = $this->MS_default_sidebars("CategoryDefault");
		if($sidebarsDefault=="" && $_POST['mssidebars'] == ""){
			$sidebars = "multiple-sidebars-default,";
		}else if ($sidebarsDefault!="" && $_POST['mssidebars'] == "") {
			$sidebars = $sidebarsDefault;
		} else {
			$sidebars = $_POST['mssidebars'];
		}
		update_option("MultipleSidebars_taxonomy_sidebars_$term_id", $sidebars);
	}
	public function MS_view_options (){
		add_submenu_page('edit.php?post_type=multiple-sidebars', __('Opciones',MULTIPLESIDEBARS_LANG), __('Opciones',MULTIPLESIDEBARS_LANG), 'manage_options', 'opciones', array(&$this, 'MS_render_options'));
		add_submenu_page('edit.php?post_type=multiple-sidebars', __('Ayuda',MULTIPLESIDEBARS_LANG), __('Ayuda',MULTIPLESIDEBARS_LANG), 'manage_options', 'ayuda', array(&$this, 'MS_render_help'));
	}
	public function MS_render_options (){
		if (!current_user_can('manage_options')) {
			wp_die(__('No tienes permisos para acceder a esta página.'));
		}
		include_once (dirname(__FILE__) . "/opciones.php");
	}
	
	public function MS_render_help (){
		if (!current_user_can('manage_options')) {
			wp_die(__('No tienes permisos para acceder a esta página.'));
		}
		include_once (dirname(__FILE__) . "/ayuda.php");
	}
	
	public function MS_default_sidebars($type){
		if(!empty($type)){
			$sidebarsDefault = get_option("MultipleSidebars".$type);
			return (!$sidebarsDefault || $sidebarsDefault=="")?"multiple-sidebars-default":$sidebarsDefault;
		}
		return false;
	}
	
	function MS_view_sidebars($sidebars,$opcion=""){
		$inactivos = $activos = $todos = "";

		$id_sidebar = "multiple-sidebars-default";
		if (preg_match("#^" . $id_sidebar . "|\," . $id_sidebar . "\,|" . $id_sidebar . "$#", $sidebars)) {
			$a = explode(",", $sidebars);
			for ($i = 0; $i < count($a); $i++) {
				if ($a[$i] == $id_sidebar) {
					$activos[$i] = '<div id="multiple-sidebars-default">Default<a href="javascript:return false;" class="agregar"></a><a href="javascript:return false;" class="borrar"></a><a href="javascript:return false;" class="arriba"><a href="javascript:return false;" class="abajo"></a></div>';
				}
			}
			//$activos .= ;
			//$todos .= "<input checked='checked' type='checkbox' name='sidebars[]' value='multiple-sidebars-default' />";
		} else {
			$inactivos .= '<div id="multiple-sidebars-default">Default<a href="javascript:return false;" class="agregar"></a><a href="javascript:return false;" class="borrar"></a><a href="javascript:return false;" class="arriba"><a href="javascript:return false;" class="abajo"></a></div>';
			//$todos .= "<input type='checkbox' name='sidebars[]' value='multiple-sidebars-default' />";
		}
		global $wpdb;

		$multiplesidebars = $wpdb -> get_results("SELECT ID,post_content,post_title FROM $wpdb->posts WHERE post_status = 'publish'	AND post_type = 'multiple-sidebars'");
		if (!empty($multiplesidebars)) {
			foreach ($multiplesidebars as $ms) {
				$id_sidebar = "multiplesidebars" . $ms -> ID;
				$nombre_sidebar = $ms -> post_title;
				if (preg_match("#^" . $id_sidebar . "|\," . $id_sidebar . "\,|" . $id_sidebar . "$#", $sidebars)) {
					$a = explode(",", $sidebars);
					for ($i = 0; $i < count($a); $i++) {
						if ($a[$i] == $id_sidebar) {
							$activos[$i] = '<div id="' . $id_sidebar . '">' . $nombre_sidebar . '<a href="javascript:return false;" class="agregar"></a><a href="javascript:return false;" class="borrar"></a><a href="javascript:return false;" class="arriba"><a href="javascript:return false;" class="abajo"></a></div>';
						}
					}
					//$activos .= '<div id="'.$id_sidebar.'">'.$nombre_sidebar.'</div>';
					//$todos .= "<input checked='checked' type='checkbox' name='sidebars[]' value='" . $id_sidebar . "' />";
				} else {
					$inactivos .= '<div id="' . $id_sidebar . '">' . $nombre_sidebar . '<a href="javascript:return false;" class="agregar"></a><a href="javascript:return false;" class="borrar"></a><a href="javascript:return false;" class="arriba"><a href="javascript:return false;" class="abajo"></a></div>';
					//$todos .= "<input type='checkbox' name='sidebars[]' value='" . $id_sidebar . "' />";
				}

			}
		}
		echo "<div class='MS_block multiplesidebars".$opcion."' id='multiplesidebars".$opcion."'>";
		echo '<h5>' . __('Arrastre desde "inactivos" al sector de "activos" para seleccionar los sidebars para esta sección', MULTIPLESIDEBARS_LANG) . '</h5>';
		echo '<input type="hidden" class="mssidebars" name="mssidebars'.$opcion.'" value="' . $sidebars . '" id="mssidebars"/>';
		echo __('Inactivos', MULTIPLESIDEBARS_LANG) . '<br/><div id="inactivos" class="'.$opcion.' sidebars-sortable inactivos">' . $inactivos . '</div>';
		echo __('Activos', MULTIPLESIDEBARS_LANG) . '<br/><div id="activos" class="'.$opcion.' sidebars-sortable activos">';
		$sidebars = preg_replace("/,$/","",$sidebars);
		$a = explode(",", $sidebars);
		for ($i = 0; $i < count($a); $i++) {
			if(isset($activos[$i])){
				echo $activos[$i];
			}
		}
		echo '</div>';
		
		?>
		<script>
		
		</script>
		<?php
		echo '<a class="botones btagregar" href="javascript:return false;" title="'.__("Crear nuevo sidebar",MULTIPLESIDEBARS_LANG).'"><img src="'.plugins_url().'/multiple-sidebars/images/mas2.png" /></a>';
		echo '<a class="botones btmodificar" href="'.admin_url().'/widgets.php" target="_blank" title="'.__("Editar Widgets de las barras laterales",MULTIPLESIDEBARS_LANG).'"><img src="'.plugins_url().'/multiple-sidebars/images/editar.png" /></a>';
		wp_nonce_field("multiple-sidebars", 'MultipleSidebars_crear_sidebar');
		echo '<div class="clear"></div>';
		echo '<div class="nuevo_sidebar"><b>'.__("Crear nuevo sidebar",MULTIPLESIDEBARS_LANG).'</b><br/><input placeholder="'.__("Nombre del sidebar",MULTIPLESIDEBARS_LANG).'" type="text" name="nuevo_sidebar" /><input onclick="javascript:return false;" type="submit" class="crear_sidebar" value="'.__("Crear",MULTIPLESIDEBARS_LANG).'" class="button-primary" /><img src="'.plugins_url().'/multiple-sidebars/images/cargando.gif" class="cargando" /></div>';
		echo '<div class="clear"></div></div>';
	}
}
?>