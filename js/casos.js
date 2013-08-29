jQuery(function(){
		jQuery("#alt").validate({
				expression: "if (VAL) return true; else return false;",
				message: "Campo vacio"
		});
		jQuery("#az").validate({
				expression: "if (VAL) return true; else return false;",
				message: "Campo vacio"
		});
		jQuery("#equis").validate({
				expression: "if (VAL) return true; else return false;",
				message: "Campo vacio"
		});
		jQuery("#ye").validate({
				expression: "if (VAL) return true; else return false;",
				message: "Campo vacio"
		});
		jQuery("#zeta").validate({
				expression: "if (VAL) return true; else return false;",
				message: "Campo vacio"
		});
		jQuery('.AdvancedForm').validated(function(){
			liga(function(url){
				document.location.href=url;
			});
		});
});