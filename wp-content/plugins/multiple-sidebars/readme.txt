=== Plugin Name ===
Contributors: Andrico
Author: Andrico
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JVLH5WYXFM7D2
Tags: widget, sidebar, sidebars, multiple sidebars, multiple, select sidebar, aside, seleccionar sidebar, multiples sidebars, widget multiple sidebars
Requires at least: 3.0
Tested up to: 3.4.2
Stable tag: 1.2.8

== Description ==

A wonderful plugin to easily create many custom sidebars. These sidebars can then select in the creation of a post / page / custom post type / category / taxonomy. We give an order, and select several at once.
You will see all active sidebars instead of seeing just one.

**Español:**

Un maravilloso plugin para poder crear fácilmente muchos sidebars. Estos sidebars luego los podremos seleccionar en la creacián de un post / page / custom post type / category / taxonomy. Podremos darle un orden, y seleccionar varios a la vez. 
Se verán todos los sidebars activos.

== Installation ==

1. Download, install and activate the Plugin.
1. Go to the new Sidebars menu and DEFINE one or more new custom sidebars. Enter their name and add a brief COMMENT to describe them. (I say DEFINE because you actually create the content for the sidebars in Step 3.)
1. Go to Appearance > Widgets. You will now see the sidebars you created on the right. (a) Add the new Multiple Sidebars Widget to the Default item on the right. (We did NOT add it to the Sidebar item, we added it to Default.) (b) Add your content into each of the new Sidebar items on the right. This is where you actually get to create your sidebar items with the relevant text, graphics, etc.
1. Create or Edit the page, post, or item where you want to use the new sidebars. A new section on the right should allow you to activate or deactivate the new Sidebars to add them to the page, post, etc. You can order them vertically (top and bottom).
1. Insert into default sidebar of theme, the MultipleSidebars Widget. Or go to Appearance > Editor and replace the following code to Sidebar.php
`<?php
dynamic_sidebar();
?>`
for this
`<?php
MS_dynamic_sidebar();
?>`

That is it. The pages should now display the sidebar that you activated for each of them with the content that you gave them in the Widget's page. There is also a sub-menu called OPTIONS inside the Sidebars menu where you can activate specific Sidebars as default for new pages, posts, etc.

Frosado, Thanks for the guide!


== Frequently Asked Questions ==
 


== Screenshots ==

1. Arrastrar para seleccionar el/los sidebar correspondiente
2. Colocar el widget MULTIPLE SIDEBARS WIDGET en el sidebar principal del tema elegido
3. Colocar en el theme la función para hacer funcionar el plugin

== Changelog ==
= 1.2.8 =
* Carga de scripts solamente en el área del administrador

= 1.2.7 =
* Corrección al instalar plugin

= 1.2.6 =
* Se agregó la función pública $MultipleSidebars->is_active_sidebar(); 

= 1.2.5 =
* Corrección de bugs de desarrollo

= 1.2.4 =
* Agregar sidebar para los template `Search`
* Corrección de bugs de desarrollo

= 1.2.3 =
* Correción de incompatibilidad con plugin `Advanced Custom Fields`

= 1.2.2 =
* Corrección de `widget.php`.

= 1.2.1 =
* Corrección de `dynamic_sidebar()` y `is_active_sidebar()`.

= 1.2.0 =
* Correción de carga de scripts

= 1.1.9 =
* Correción de función `dynamic_sidebar` gracias a JochenT

= 1.1.8 =
* Widget para themes que ya tienen sidebars. De esta forma, no hará falta cambiar código, sólo intrudicir un widget en la sección de WIDGETS
* Corrección botones en creación de sidebar con AJAX en opciones y post / page / custom post type / taxonomy / category

= 1.1.7 =
* Corrección botones en creación de sidebar con AJAX en opciones y post / page / custom post type / taxonomy / category

= 1.1.6 =
* Creación de sidebar con AJAX en opciones y post / page / custom post type / taxonomy / category
* Seguridad en AJAX
* Completar campos de idioma, para traducción
* Iconos gestuales para diferentes acciones
* Corrección de bugs en selector de sidebar y en creación de sidebar

= 1.1.5 =
* Corrección de creación sidebar en opciones y post / page / custom post type / taxonomy / category

= 1.1.4 =
* Incorporación de traducciones.

= 1.1.3 =
* Correción creación de sidebars en opciones.
* Agregado de botones para tablets y celulares. Borrar, Abajo, Arriba, Agregar
* Seguridad en creación de sidebars desde selector de sidebars

= 1.1.2 =
* Corrección guardado de opciones

= 1.1.1 =
* Creación de la funcion `$MultipleSidebars->is_active_sidebar()` para detectar si están uno o mas sidebars en uso.
* Seguridad a la hora de crear un nuevo Sidebar desde el selector de sidebars
* Creación de selector de sidebars para la Home en opciones de Sidebars

= 1.1.0 =
* Actualización para crear sidebars desde el selector de sidebars

= 1.0.0 =
* Actualización del plugin

== Upgrade Notice ==
= 1.2.3 =
* Correción de incompatibilidad con plugin `Advanced Custom Fields`

= 1.2.2 =
* Corrección de `widget.php`.

= 1.2.1 =
* Corrección de `dynamic_sidebar()` y `is_active_sidebar()`.

= 1.2.0 =
* Correción de carga de scripts

= 1.1.9 =
* Correción de función `dynamic_sidebar` gracias a JochenT

= 1.1.8 =
* Widget para themes que ya tienen sidebars. De esta forma, no hará falta cambiar código, sólo intrudicir un widget en la sección de WIDGETS
* Corrección botones en creación de sidebar con AJAX en opciones y post / page / custom post type / taxonomy / category

= 1.1.7 =
* Corrección botones en creación de sidebar con AJAX en opciones y post / page / custom post type / taxonomy / category

= 1.1.6 =
* Creación de sidebar con AJAX en opciones y post / page / custom post type / taxonomy / category
* Seguridad en AJAX
* Completar campos de idioma, para traducción
* Iconos gestuales para diferentes acciones
* Corrección de bugs en selector de sidebar y en creación de sidebar

= 1.1.5 =
* Corrección de creación sidebar en opciones y post / page / custom post type / taxonomy / category

= 1.1.4 =
* Incorporación de traducciones.

= 1.1.1 =
* Creación de la funcion `$MultipleSidebars->is_active_sidebar()` para detectar si están uno o mas sidebars en uso.
* Seguridad a la hora de crear un nuevo Sidebar desde el selector de sidebars
* Creación de selector de sidebars para la Home en opciones de Sidebars

= 1.1.0 =
* Actualización para crear sidebars desde el selector de sidebars

= 1.0.0 =
* Actualización del plugin

