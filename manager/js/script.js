function ShowMessage(title, message) {
	var type = BootstrapDialog.TYPE_DEFAULT;
	var cssClass = "btn-warning";
	
	if (title == "警告") {
		type = BootstrapDialog.TYPE_WARNING;
		cssClass = "btn-warning";
	} else if (title == "錯誤") {
		type = BootstrapDialog.TYPE_DANGER;
		cssClass = "btn-danger";
	} else if (title == "提示") {
		type = BootstrapDialog.TYPE_INFO;
		cssClass = "btn-info";
	}
		
	BootstrapDialog.show({
		title: title,
		message: message,
		closable: false,
		type: type,
		buttons: [{
			label: "確定",
			cssClass: cssClass,
			action: function(dialogRef){
					dialogRef.close();
					location.href=".";
                }
			}]
		});
}