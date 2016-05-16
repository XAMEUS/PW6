$(document).ready(function(){
    var url = window.location.href;
    var index = url.search("payslipgenerator");
    var newurl = url.substr(0, index+7)+url.substr(index+16);

    window.open(newurl,'window','scrollbars=yes, toolbar=no, menubar=no, resizable=yes');

    window.document.location = url.substr(0,index)+"user/"+$_GET("user");
});

function $_GET(param) {
	var vars = {};
	window.location.href.replace( location.hash, '' ).replace(
		/[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
		function( m, key, value ) { // callback
			vars[key] = value !== undefined ? value : '';
		}
	);

	if ( param ) {
		return vars[param] ? vars[param] : null;
	}
	return vars;
}
