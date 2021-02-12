<?php
function addEditorJS($id){
		?>
    var TextArea<?php echo $id;?> = document.getElementById("pycode<?php echo $id;?>");
	var editor<?php echo $id;?> = CodeMirror.fromTextArea(TextArea<?php echo $id;?>,{
		    lineNumbers: true,
		  mode:  "python"
		});
		var editor = editor<?php echo $id;?>;
		var output = "output<?php echo $id;?>";
		editor.on('change', (editor, change) => {
            localStorage.setItem(codeStoreKey, editor.getDoc().getValue())
        })
        const runner<?php echo $id;?> = new BrythonRunner({
            stdout: {
                write(content) {
                    var el = document.createElement('code')
                    var text = document.createTextNode(content)
                    el.appendChild(text)
                    document.getElementById(output).appendChild(el)  
                },
                flush() { }
            },
            stderr: {
                write(content) {
                    var el = document.createElement('code')
                    var text = document.createTextNode(content)
                    el.appendChild(text)
                    el.setAttribute('class', 'error')
                    document.getElementById(output).appendChild(el)
                },
                flush() { }
            },
            stdin: {
                async readline() {
                    var data = prompt()
                    
                    var el = document.createElement('code')
                    var text = document.createTextNode(data + '\n')
                    el.appendChild(text)
                    document.getElementById(output).appendChild(el)  

                    return data
                },
            }
        })
        
        function clearOutput() {
            document.getElementById(output).innerHTML = ''
        }
        function getCode() {
            return editor.getDoc().getValue()
        }
        function run() {
            clearOutput()
            const code = getCode()
            runner<?php echo $id;?>.runCode(code)
        }
		
	<?php
	}
