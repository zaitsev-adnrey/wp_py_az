document.addEventListener("DOMContentLoaded", function(event) { 
    var myTextArea = document.getElementById("pycode");
	var myCodeMirror = CodeMirror.fromTextArea(myTextArea,{
		  lineNumbers: true,
		  mode:  "python"
		});
});

