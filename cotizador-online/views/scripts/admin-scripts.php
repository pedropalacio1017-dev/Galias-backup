<script>
$(document).ready(function(){
	
	/*--------------------------------------------------*/
	/* VARIABLES */
	/*--------------------------------------------------*/
	
	var width = $(window).width();
	var height = $(window).height();
	var height_doc = $(document).height();
	
	
	/*--------------------------------------------------*/
	/* SUBIR */
	/*--------------------------------------------------*/
	
	/* Scroll */
	$(document).scroll(function() {
		var pixeles_down = $(this).scrollTop();
		if (pixeles_down > 100) {
			$('#subir').fadeIn();	
		} else {
			$('#subir').fadeOut();
		}
	});
	
	/* Boton subir*/	
	$('#subir').click(function(){
		$('html, body').animate({scrollTop:0}, 'fast');
		return false;
	});
	
	
	/*--------------------------------------------------*/
	/* MENU */
	/*--------------------------------------------------*/
	
	/* Menu oculto */
	$('#btn_admin_menu').on('click', function() {
		$(this).toggleClass('cerrar');
		$('#menu').toggle('fast');
		return false;
	});
	
	/* Submenus */
	$('.parent .separator').click(function() {
		$(this).toggleClass('activo');
		$(this).parent().find('ul').toggleClass('visible');
		return false;
	});
	
	
	
	/*--------------------------------------------------*/
	/* BOTON FORMULARIO FIXED (si la pagina es más alta que la ventana) */
	/*--------------------------------------------------*/
	
 	$(window).scroll(function(){
        if($(window).scrollTop() + height < height_doc - 100) {
            $('.boton_formulario').addClass('fixed');
        } else{
            $('.boton_formulario').removeClass('fixed');
        }
    });
	
	
	
	/*--------------------------------------------------*/
	/* BOTON ELIMINAR ITEM */
	/*--------------------------------------------------*/
	
	/* Boton eliminar */
	$('.warning_eliminar').click(function(e) {
		var url = $(this).attr('href');
		var textAlerta = $(this).data('alerta');
		e.preventDefault();
		Swal.fire({
			title: '¿Está seguro?',
			text: textAlerta,
			type: 'warning',
			confirmButtonText: '¡Confirmar!',
			showCancelButton: true,
			cancelButtonText: 'Cancelar',
			reverseButtons: true
		})
		.then( function(result) {
			if (result.value) {
				window.location = url;
			}
			else {
				console.log('Cancelado');
			}
		});	
	});
	
	
	/*--------------------------------------------------*/
	/* ZEBRA TABLA */
	/*--------------------------------------------------*/
	
	$('.tabla_filtrable tbody tr:odd').addClass('zebra');
	
	
	
	
});
</script>