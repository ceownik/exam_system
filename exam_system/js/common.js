
function bindTinyMce(fieldList, width, height) {
	
	width = width ? width : "500px";
	height = height ? height : "200px";
	
	
	tinyMCE.init({
			theme : "advanced",
			mode: "exact",
			elements : fieldList,
			
			theme_advanced_buttons1 : "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,|,sub,sup,|,charmap,fullscreen,cleanup,code",
			plugins : "fullscreen",

			language : "pl",
			
			height: height,
			width: width,
			
			'paste_text_sticky': true,
			'paste_text_sticky_default': true,
			
			content_css : "/css/tiny_mce_content.css"
		});
}