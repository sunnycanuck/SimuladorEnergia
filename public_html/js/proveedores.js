jQuery(function(){
		jQuery("#nombre").validate({
				expression: "if (VAL) return true; else return false;",
				message: "Favor de llenar el campo"
		});
		jQuery("#ciudad").validate({
				expression: "if (VAL) return true; else return false;",
				message: "Favor de llenar el campo"
		});
		jQuery("#direccion").validate({
				expression: "if (VAL) return true; else return false;",
				message: "Favor de llenar el campo"
		});
		jQuery("#rfc").validate({
				expression: "if (VAL) return true; else return false;",
				message: "Favor de llenar el campo"
		});
		jQuery("#mail").validate({
				expression: "if (VAL.match(/^[^\\W][a-zA-Z0-9\\_\\-\\.]+([a-zA-Z0-9\\_\\-\\.]+)*\\@[a-zA-Z0-9_]+(\\.[a-zA-Z0-9_]+)*\\.[a-zA-Z]{2,4}$/)) return true; else return false;",
				message: "Ingrese una dirección de correo válida"
		});
		jQuery("#tel").validate({
				expression: "if (VAL) return true; else return false;",
				message: "Favor de llenar el campo"
		});
		jQuery("#nombre_pqt").validate({
				expression: "if (VAL) return true; else return false;",
				message: "Favor de llenar el campo"
		});
		jQuery('.AdvancedForm').validated(function(){
			liga(function(url){
				document.location.href=url;
			});
		});
});
<!-- cambiar opciones en forma de alta dispositivos en proveedores -->
$(document).ready(function(){
	$('#dispositivo_tipo').bind('change', function () {
			var tipo = $(this).val();
			if (tipo==1) {
					$("#factores div").show().remove().slideUp(600);
					$("#notas cite").show().remove().slideUp(600);
					var fact = jQuery('<div><input type="text" name="dell" class="general widthsmall" /><input type="text" name="delh" class="general widthsmall" /><input type="text" name="potencia" class="general widthsmall" /><select name="tipoFotovol" class="general tipoFotovol"><option value="1">Monocristalino</option><option value="2">Policristalino</option><option value="3">Pelicula Delgada</option></select></div');
					var notas = jQuery('<cite><a class="tips" rel="qtip_files/proveedores/factores_fotovoltaico.html" data-hasqtip="true"><img class="info-qtip-img" src="images/info.png"></a> DelL, DelH, Potencia, Tipo Fotovoltaico</cite>');
					$('#factores').hide().append(fact).slideDown(600);
					$('#notas').hide().append(notas).slideDown(600);					
			}
			if(tipo==2){
					$("#factores div").remove().slideUp(600);
					$("#notas cite").remove();
					var fact = jQuery('<div><input type="text" name="factores" class="general" /></div>');
					var notas = jQuery('<cite><a class="tips" rel="qtip_files/proveedores/factores_lampara.html" data-hasqtip="true"><img class="info-qtip-img" src="images/info.png"></a> Favor de separar los valores con ";".</cite>');
					$('#factores').hide().append(fact).slideDown(600);
					$('#notas').hide().append(notas).slideDown(600);
			}
			if(tipo==3){
					$("#factores div").remove();
					$("#notas cite").remove();
					var fact = jQuery('<div><input type="text" name="factores" class="general" /></div>');
					var notas = jQuery('<cite><a class="tips" rel="qtip_files/proveedores/factores_medidor.html" data-hasqtip="true"><img class="info-qtip-img" src="images/info.png"></a> Favor de separar los valores con ";".</cite>');
					$('#factores').hide().append(fact).slideDown(600);
					$('#notas').hide().append(notas).slideDown(600);
			}
			if(tipo==4){
					$("#factores div").remove();
					$("#notas cite").remove();
					var fact = jQuery('<div><input type="text" name="watts" class="general widthsmall" /><input type="text" name="porcentaje" class="general widthsmall" /></div>');
					var notas = jQuery('<cite><a class="tips" rel="qtip_files/proveedores/factores_gridtie.html" data-hasqtip="true"><img class="info-qtip-img" src="images/info.png"></a> W (Watts), % (Porcentaje).</cite>');
					$('#factores').hide().append(fact).slideDown(600);
					$('#notas').hide().append(notas).slideDown(600);
			}
			$('a.tips[rel]').each(function(){
					// We make use of the .each() loop to gain access to each element via the "this" keyword...
					$(this).qtip(
					{
						content: {
							// Set the text to an image HTML string with the correct src URL to the loading image you want to use
							text: '<img class="throbber" src="http://craigsworks.com/projects/qtip/images/throbber.gif" alt="Loading..." />',
							ajax: {
								url: $(this).attr('rel') // Use the rel attribute of each element for the url to load
							},
							title: {
								//text: 'Titulo de : ' + $(this).text(), // Give the tooltip a title using each elements text
								button: false
							}
						},
						position: {
							at: 'bottom center', // Position the tooltip above the link
							my: 'top center',
							viewport: $(window), // Keep the tooltip on-screen at all times
							effect: false // Disable positioning animation
						},
						show: {
							event: 'click', // Don't specify a show event...
							//ready: true // ... but show the tooltip when ready
						},
						hide:{
							//fixed: true,
							event: 'unfocus'
						},
						style: {
							classes: 'qtip-wiki qtip-plain qtip-shadow normal-width'
						}
					})
				})
				// Make sure it doesn't follow the link when we click it
				.click(function(event) { event.preventDefault(); });
			return false;
	});
});
<!-- cambiar opciones en forma de alta dispositivos en proveedores -->
