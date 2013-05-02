<?php
/*
 * Agregar MultipleSidebarsWidget.
 */
class MultipleSidebarsWidget extends WP_Widget {
	var $fin = false; // Preveenir que coloquen el widget en un sidebar creado con el plugin
	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct('multiple_sidebars_widget', //  ID
		'Multiple Sidebars Widget', // Nombre
		array('description' => __('Colocar en el sidebar predeterminado del tema para poder visualizar los sidebars elegidos. No en DEFAULT', MULTIPLESIDEBARS_LANG), ) // Args
		);
	}

	/**
	 * Vista del WIDGET.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args Widget argumentos.
	 * @param array $instance Valores para guardar en la base de datos.
	 */
	public function widget($args, $instance) {
		global $post, $wp_registered_sidebars, $wp_registered_widgets, $MultipleSidebars;
		if(!$this->sinfin){
			if ($MultipleSidebars -> dynamic_sidebar()) {
				$this->fin = true;
			}
		}

	}
	
	/**
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Valores para ser guardados.
	 * @param array $old_instance Valores previamente guardados en la base de datos.
	 *
	 * @return array Valores a ser guardados.
	 */
	public function update($new_instance, $old_instannce) {
	}

	/**
	 * Back-end widget.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Valores previamente guardados en la base de datos.
	 */
	public function form($instance) {
		echo '<p>' . __('Colocar en el sidebar predeterminado del tema para poder visualizar los sidebars elegidos.', MULTIPLESIDEBARS_LANG) . '</p>';
	}

} // class Foo_Widget

add_action( 'widgets_init', create_function( '', 'register_widget( "MultipleSidebarsWidget" );' ) );
?>