jQuery(function(){
		jQuery("#nombre").validate({
				expression: "if (VAL) return true; else return false;",
				message: "Favor de llenar el campo"
		});
		jQuery("#latitude").validate({
				expression: "if (VAL) return true; else return false;",
				message: "Favor de llenar el campo"
		});
		jQuery("#longitude").validate({
				expression: "if (VAL) return true; else return false;",
				message: "Favor de llenar el campo"
		});
		jQuery("#dx").validate({
				expression: "if (VAL) return true; else return false;",
				message: "Favor de llenar el campo"
		});
		jQuery("#dy").validate({
				expression: "if (VAL) return true; else return false;",
				message: "Favor de llenar el campo"
		});
		jQuery("#phi").validate({
				expression: "if (VAL) return true; else return false;",
				message: "Favor de llenar el campo"
		});
		jQuery("#estacion").validate({
				expression: "if (VAL) return true; else return false;",
				message: "Favor de llenar el campo"
		});
		jQuery('.altaTerreno').validated(function(){
			liga(function(url){
				document.location.href=url;
			});
		});
});