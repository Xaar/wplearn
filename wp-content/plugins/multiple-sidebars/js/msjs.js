jQuery(function(){
	jQuery('.MS_block input[name=nuevo_sidebar]').keypress(function(e){
		if(e==13){
			jQuery(this).parent().find('input.crear_sidebar').click();
			return false;
		}
	});
	jQuery('.crear_sidebar').click(function(){
		var data = {
			action: 'crear_sidebar',
			nuevo_sidebar: jQuery(this).parent().find("input[name=nuevo_sidebar]").val(),
			MultipleSidebars_crear_sidebar: jQuery(this).parent().parent().find('#MultipleSidebars_crear_sidebar').val(),
		};
		
		jQuery.ajax({
			url:ajaxurl,
			type:'POST',
			dataType:'json', 
			data:data, 
			beforeSend: function(){
				jQuery('.nuevo_sidebar > img.cargando').css("display","block");
			},
			success:function(response) {
				if(response){
					id_sidebar = "multiplesidebars"+response.ID;
					nombre_sidebar = response.post_title;
					jQuery('.inactivos').append('<div id="' + id_sidebar + '">' + nombre_sidebar + '<a href="javascript:return false;" class="agregar"></a><a href="javascript:return false;" class="borrar"></a><a href="javascript:return false;" class="arriba"><a href="javascript:return false;" class="abajo"></a></div>');
					jQuery('.nuevo_sidebar > img.cargando').css("display","none");
					jQuery('input[name=nuevo_sidebar]').val("");
					actualizar_botones();
				}else{
					alert("Error");
				}
			}
		});
		return false;
	});
	
	jQuery(".MS_block").each(function(){
		MS_id = jQuery(this).attr("id"); 
		jQuery("#"+MS_id+" .inactivos, #"+MS_id+" .activos").sortable({
			connectWith:"#"+MS_id+" .sidebars-sortable",
			scroll: false,
			tolerance: 'pointer',
			axis: 'y',
			stop: function(event,ui){
				actualizar();
			}
		});
	});
	
	actualizar_botones();
	
	jQuery(".btagregar").click(function(){
		jQuery(this).parent().find("div.nuevo_sidebar").toggle();
		if(jQuery(this).is(".activo")){
			jQuery(this).removeClass("activo");
		}else{
			jQuery(this).addClass("activo");
		}
		return false;
	})
})
function actualizar(){
	jQuery(".MS_block").each(function(){
		var a = "";
		jQuery(this).find(".activos").children("div").each(function(){
			a += jQuery(this).attr("id")+",";
		})
		jQuery(this).find(".mssidebars").val(a);
	})
}
function actualizar_botones(){
	jQuery(".activos .borrar, .inactivos .borrar").click(function(){
		jQuery(this).parent().appendTo(jQuery(this).parent().parent().parent().find(".inactivos.sidebars-sortable"));
		actualizar();
		return false;
	})
	jQuery(".activos .agregar, .inactivos .agregar").click(function(){
		jQuery(this).parent().appendTo(jQuery(this).parent().parent().parent().find(".activos.sidebars-sortable"));
		actualizar();
		return false;
	})
	jQuery(".activos .abajo, .inactivos .abajo").click(function(){
		jQuery(this).parent().insertAfter(jQuery(this).parent().next());
		actualizar();
		return false;
	})
	jQuery(".activos .arriba, .inactivos .arriba").click(function(){
		jQuery(this).parent().insertBefore(jQuery(this).parent().prev());
		actualizar();
		return false;
	})
}
