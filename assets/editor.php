<?php



?>
<html>
   <head>
      <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<link rel="stylesheet" type="text/css" media="screen" href="assets/css/cupertino/jquery-ui.css" />
      <link rel="stylesheet" type="text/css" media="screen" href="assets/css/styles.css" />
      <script  type="text/javascript" src="assets/js/jq.js" language="JavaScript" ></script>
      <script  type="text/javascript" src="assets/js/jqui.js" language="JavaScript" ></script>
      <script  type="text/javascript" src="assets/js/config.js" language="JavaScript" ></script>
      <script  type="text/javascript" src="assets/js/lib.js" language="JavaScript" ></script>
      <script  type="text/javascript" src="assets/js/listener.js" language="JavaScript" ></script>
   </head>
   <body>
      
      <div id="content" class="ui-widget-content ui-corner-all">
         <div>
            <h1 id="title" class="ui-dialog-titlebar ui-widget-header ui-corner-all">ACO Editor v<span>1</span><i>Beta</i></h1>
            <form id="formulaire" method="post" action="">
               <fieldset>
               	<legend>Versions</legend>
               	<select id="select-versions" name="select-versions">
               		<option value="1">1</option>
               		<option value="2">2</option>
               		<option value="3">3</option>
               	</select>
               </fieldset>
               
               <fieldset>
                  <legend><b>Menu</b></legend>
                  <button name="new" id="new" value="1" type="button">New</button>
                  <button name="cut" id="cut" value="1" type="button" disabled="disabled">Cut</button>
                  <button name="copy" id="copy" value="1" type="button" disabled="disabled">Copy</button>
                  <button name="paste" id="paste" value="1" type="button" disabled="disabled">Paste</button>
                  <button name="undo" id="undo" value="1" type="button" disabled="disabled">Undo</button>
                  <button name="redo" id="redo" value="1" type="button" disabled="disabled">Redo</button>
                  <button name="replay" id="replay" value="1" type="button" disabled="disabled">Replay</button>
                  <button name="dump" id="dump" value="1" type="button">Memory dump</button>
               </fieldset>

               <fieldset>
                  <textarea id="text" rows="10" cols="40" ></textarea>
               </fieldset>
            </form>
            <a href="bin/tests/PHPUnitTest-coverage/" >Unit Tests</a> | 
            <a href="documentation/phpdoc/" >API Documentation</a> |
            <a href="documentation/bouml/" >UML Documentation</a> |
            <a href="acoeditor_v3.zip" >Download</a>
         </div>
         <div>
            <div id="console">
               <pre id='pre'></pre>
            </div>
         </div>
    </div>
   </body>
   
</html>