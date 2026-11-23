<script>
$(document).ready(function() {

	/*--------------------------------------------------*/
	/* SUBIR */
	/*--------------------------------------------------*/
	
	/* Scroll */
	$(document).scroll(function() {
		let pixeles_down = $(this).scrollTop();
		//console.log(pixeles_down);
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
	/* ARBOL LINKS */
	/*--------------------------------------------------*/

	$('.toggler').click(function(e) {
		e.preventDefault();
		if ($(this).parent().hasClass('activo')) {
			$(this).parent().removeClass('activo open');
			$(this).removeClass('activo open');
			$(this).next('.fila').hide().removeClass('open');
		} else {
			$(this).addClass('activo');
			$(this).parent().addClass('activo');
			$(this).next('.fila, .fila.branch').show();
		}
		return false;
	});


	/*--------------------------------------------------*/
	/* MENU RSPNSV */
	/*--------------------------------------------------*/

	$('#pull').on('click', function() {
		$('.menu .desplegable').toggle();
		$(this).toggleClass('active');
		return false;
	});
	


	/*--------------------------------------------------*/
	/* ADMIN BOTON ELIMINAR ITEM */
	/*--------------------------------------------------*/
	
	/* Boton Eliminar*/
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

	/* Select ID_categoria define ID_seccion */
	$('select[name="ID_categoria"]').change( function() {
		var ID_seccion = $(this).find(':selected').data('id-seccion');	
		$('input[name="ID_seccion"]').val(ID_seccion);
	});

	/* Select ID_carpeta define ID_seccion y ID_categoria */
	$('select[name="ID_carpeta"]').change( function() {
		var ID_seccion = $(this).find(':selected').data('id-seccion');
		var ID_categoria = $(this).find(':selected').data('id-categoria');	
		$('input[name="ID_seccion"]').val(ID_seccion);
		$('input[name="ID_categoria"]').val(ID_categoria);
	});

	/* Btn Cargar CSV */
	$('#cargar_csv').on('click', function(event) {
		event.preventDefault();
		$(this).val('Cargando...').prop('disabled', true);
		$(this).parent().submit();
	});




	/*--------------------------------------------------*/
	/* Tabla */
	/*--------------------------------------------------*/

	$('table tr:even').addClass('alt');


		
});
</script>