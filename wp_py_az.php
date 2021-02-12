<?php
/**
 * Plugin Name:     Wp_py_az
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          YOUR NAME HERE
 * Author URI:      YOUR SITE HERE
 * Text Domain:     wp_py_az
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Wp_py_az
 */
 
if ( ! defined( 'RC_TC_BASE_FILE' ) )
    define( 'RC_TC_BASE_FILE', __FILE__ );
if ( ! defined( 'RC_TC_BASE_DIR' ) )
    define( 'RC_TC_BASE_DIR', dirname( RC_TC_BASE_FILE ) );
if ( ! defined( 'RC_TC_PLUGIN_URL' ) )
    define( 'RC_TC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
   
include "post-types/pyscrpt.php";
include "metaboxes/textarea.php";
include "lib/functions.php";

function true_include_myuploadscript() {
	wp_enqueue_style( 'code-mirror-css', RC_TC_PLUGIN_URL .'/vendor/lib/codemirror.css');
	wp_enqueue_script( 'code-mirror-js', RC_TC_PLUGIN_URL .'/vendor/lib/codemirror.js');
	wp_enqueue_script( 'code-mirror-init', RC_TC_PLUGIN_URL .'/js/codemirror-init.js');
	wp_enqueue_script( 'code-mirror-py', RC_TC_PLUGIN_URL .'/vendor/mode/python/python.js');
	wp_enqueue_script( 'pyodide','https://cdn.jsdelivr.net/pyodide/v0.16.1/full/pyodide.js');
	
}
 
add_action( 'admin_enqueue_scripts', 'true_include_myuploadscript' ); 
function include_myuploadscript(){
		wp_register_script('jquery', '//cdnjs.cloudflare.com/ajax/libs/jquery/3.3.2/jquery.min.js', false, null); // TODO Check for several inst
		wp_enqueue_script('jquery');
		wp_enqueue_style( 'interpreter-css', RC_TC_PLUGIN_URL .'css/style.css');
		wp_enqueue_style( 'code-mirror-css-front', RC_TC_PLUGIN_URL .'vendor/lib/codemirror.css');
		wp_enqueue_script( 'code-mirror-js-front', RC_TC_PLUGIN_URL .'vendor/lib/codemirror.js');
		wp_enqueue_script( 'code-mirror-py-front', RC_TC_PLUGIN_URL .'vendor/mode/python/python.js');
		wp_enqueue_script( 'brython-min', RC_TC_PLUGIN_URL.'src/brython.js');
		wp_enqueue_script( 'brython-lib', RC_TC_PLUGIN_URL.'src/brython_stdlib.js');
		wp_enqueue_script( 'brython-init', RC_TC_PLUGIN_URL .'js/init-brython.js');
		wp_enqueue_script( 'brython-runner', 'https://cdn.jsdelivr.net/gh/pythonpad/brython-runner/lib/brython-runner.bundle.js');
		wp_enqueue_script( 'pyodide','https://cdn.jsdelivr.net/pyodide/v0.16.1/full/pyodide.js');
		
	}
add_action( 'wp_enqueue_scripts', 'include_myuploadscript' );
add_shortcode( 'py', 'py_func' );

function py_func( $atts ){
	    $id = $atts['id'];
	    $script=get_post_meta($atts['id'], 'script', 1);
		$out='<div class="interpreter-container">';
		$out.='<div class="editor"><textarea  id="pycode'.$atts['id'].'" rows="8" 
		cols="50" name="script">'.$script.'</textarea></div>';
		$out.='<button class="toolbar-button" onclick="run'.$id.'()">
                Run
            </button>
            <div class="console"><div  id="output'.$atts['id'].'"></div>';
		$out.='</div></div>';
		$out.= "<script>var TextArea$id = document.getElementById('pycode$id');
	var editor$id = CodeMirror.fromTextArea(TextArea$id,{
		    lineNumbers: true,
		  mode:  'python'
		});
		
        const runner$id = new BrythonRunner({
            stdout: {
                write(content) {
                    var el = document.createElement('code')
                    var br = document.createElement('br')
                    var text = document.createTextNode(content)
                    el.appendChild(text)
                    document.getElementById('output$id').appendChild(el)  
                    document.getElementById('output$id').appendChild(br)
                },
                flush() { }
            },
            stderr: {
                write(content) {
                    var el = document.createElement('code')
                    var br = document.createElement('br')
                    var text = document.createTextNode(content)
                    el.appendChild(text)
                    el.setAttribute('class', 'error')
                    document.getElementById('output$id').appendChild(el)
                    
                },
                flush() { }
            },
            stdin: {
                async readline() {
                    var data = prompt()
                    var br = document.createElement('br')
                    var el = document.createElement('code')
                    var text = document.createTextNode(data)
                    el.appendChild(text)
                    document.getElementById('output$id').appendChild(el) 
                    document.getElementById('output$id').appendChild(br) 
                    return data
                },
            }
        })
        
        function clearOutput$id() {
            document.getElementById('output$id').innerHTML = ''
        }
        function getCode$id() {
            return editor$id.getDoc().getValue()
        }
        function run$id() {
            clearOutput$id()
            const code = getCode$id()
            runner$id.runCode(code)
        }</script>";
	return $out;
}
add_shortcode( 'pyodid', 'pyodid_func' );

function pyodid_func( $atts ){
	    $id = $atts['id'];
	    $script=get_post_meta($atts['id'], 'script', 1);
		$out='<div class="interpreter-container">';
		$out.='<div class="editor"><textarea  id="pyodidcode'.$atts['id'].'" rows="8" 
		cols="50" name="script">'.$script.'</textarea></div>';
		$out.='<button class="toolbar-button" onclick="evaluatePython'.$atts['id'].'()">
                Run
            </button>
            <div class="console"><textarea  id="output'.$atts['id'].'"></textarea>';
		$out.='</div></div>';
		$out.= "<script>var TextArea$id = document.getElementById('pyodidcode$id');
	var editor$id = CodeMirror.fromTextArea(TextArea$id,{
		    lineNumbers: true,
		  mode:  'python'
		});
	var output = document.getElementById('output$id');
    var code = document.getElementById('pyodidcode$id');

    function addToOutput$id(s) {
      output.value += '>>>' + code.value;
    }

    output.value = 'Initializing....';
    // init pyodide
    languagePluginLoader.then(() => { output.value += 'Ready!!'; });

    function evaluatePython$id() {
      pyodide.runPythonAsync(code.value)
        .then(output => addToOutput$id(output))
        .catch((err) => { addToOutput$id(err) });
    }
		
		</script>";
	return $out;
}
//function slider_footer_js(){
		//$sliders = get_posts( array('numberposts' => -1,'post_type'   => 'pyscrpt',) );
		//foreach( $sliders as $slider ){
			//$ID= $slider->ID;
			//addEditorJS($ID);
		//}
	//}
//add_action('wp_footer','slider_footer_js');

