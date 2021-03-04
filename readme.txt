=== Wp_py_az ===
Donate link: https://yoomoney.ru/to/410014506439988
Tags: WP, Python
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Плагин реализует интеграцию независимой Python среды в WordPress
Плагин использует следующие проекты.
- https://brython.info
- https://pyodide.org
== Description ==
После установки плагина перейти в пункт Админ панели "Scripts"
Данный произвольный тип записи, обладает полем для ввода python кода с подстветкой(codeMirror),для сохранения скриптов в postmeta.

Вывод результатов выполнения кода возможен штатными средствами в цикле WordPress. 
Для компиляции python средствами brython значение мета поля "script" следует выводить в теге <script type="text/python">

Так же для вывода результатов выполнения кода возможна работа с DOM элементами напрямую из python подробней в документации  brython(https://brython.info/static_doc/en/intro.html)

Для возможности иморта библиотек в том числе и DataScience следует выполнять скрипт средствами pyodide из js функцией 
pyodide.runPythonAsync(code, messageCallback, errorCallback)
подробнее см документацию pyodide https://pyodide.org/en/latest/index.html
 
Или же можно вывести редактор кода с областью вывода с помощью шорткодов
Шорткоды:
[py id="id поста script"]-  выведет ведактор кода и консоль куда выводится результат по нажатию кнопки средствами brython
[pyodide id="id поста script"]- выведет ведактор кода и консоль и область результатов средствами pyodide
