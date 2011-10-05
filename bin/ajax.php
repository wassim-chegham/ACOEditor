<?php

/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

$session =& getInstance();

function ajax_handle(){
	$debug = true;
	global $session;

	$function_name_allowed = array('redo', 'undo', 'reset', 'init', 'replay', 'updateSelection', 'insert' ,'paste', 'copy', 'cut');
	$function_name = 'default'; // function name
	$function_valid = false; // status of function validation
	$params_valid = false; // status of params validation
	$output = array(); // output data
	$output_type = 'default'; //output type

	$function_valid = validPostArg('function_name') && validPostFunction($function_name_allowed);

	if($function_valid){
		$function_name = $_REQUEST['function_name'];

		switch($function_name){

			case 'reset':

				$params_valid = true;
				$session->ihm->reset();

				// output define
				$output_type = 'json';
				$output['Ihm'] = getIhmAttributes();

				break;

			case 'redo':

				$params_valid = true;
				$session->ihm->redo();
				// output define
				$output_type = 'json';
				$output['Ihm'] = getIhmAttributes();
				if ( $session->ihm->canReplay() )
				{
					
					$output['Ihm'] = array_merge($output['Ihm'], array('redo_last' => false) );
				}
				else  {
					$output['Ihm'] = array_merge($output['Ihm'], array('redo_last' => true) );
				}
				break;

			case 'undo':

				$params_valid = true;
				$session->ihm->undo();
				// output define
				$output_type = 'json';
				$output['Ihm'] = getIhmAttributes();
				if ( $session->ihm->canUndo() )
				{
					$output['Ihm'] = array_merge($output['Ihm'], array('undo_last' => false) );
				}
				else  {
					$output['Ihm'] = array_merge($output['Ihm'], array('undo_last' => true) );
				}
				break;
					
					
			case 'init':

				$params_valid = true;
				// output define
				$output_type = 'json';
				$output['Ihm'] = getIhmAttributes();
				break;


			case 'replay':

				$params_valid = true;
				// output define
				$output_type = 'json';

				if ( $session->ihm->canReplay() )
				{
					$session->ihm->replay();
					$output['Ihm'] = array_merge( getIhmAttributes(), array('replay_done' => false) );
				}
				else  {
					$output['Ihm'] = array('replay_done' => true);
				}

				break;

				// the end of the selection
			case 'updateSelection':
				// check args

				$params_valid = validPostArg('selStart') && validPostArg('selEnd');
				if($params_valid){
					$output['debug'] = debug_me();
					$selStart = $_REQUEST['selStart'];
					$selEnd = $_REQUEST['selEnd'];

				 // work with IHM and Buffer
					$session->ihm->updateSelection($selStart, $selEnd);

					// output define
					$output_type = 'json';
					$output['Ihm'] = getIhmAttributes();
				}
				break;

				// the character to be stored
			case 'insert':
				// check args
				$params_valid = validPostArg('char');
				if($params_valid){
					$output['debug'] = debug_me();
					$char = $_REQUEST['char'];

					// work with IHM and Buffer
					$session->ihm->setChar($char);
					$session->ihm->insert();
					// output define
					$output_type = 'json';
					$output['Ihm'] = getIhmAttributes();
				}
				break;

			case 'cut':
				$output['debug'] = debug_me();
				/* We don't need to get any args because the real IHM user (Web browser)
				 * and its image(Ihm.php) are syncronized at every command
				 * Ihm.php must have the same positionStart & positionEnd as the IHM (Web browser)
				 */
				$params_valid = true;

				// work with IHM and Buffer
				$session->ihm->cut();

				// output define
				$output_type = 'json';
				$output['Ihm'] = getIhmAttributes();
				$output['debug'] = debug_me();
				break;

			case 'copy':
				/* We don't need to get any args because the real IHM user (Web browser)
				 * and its image(Ihm.php) are syncronized at every command
				 * Ihm.php must have the same positionStart & positionEnd as the IHM (Web browser)
				 */
				$params_valid = true;

				// work with IHM and Buffer
				$session->ihm->copy();

				// output define
				$output_type = 'json';

				break;

			case 'paste':
				$output['debug'] = debug_me();
				/* We don't need to get any args because the real user IHM (Web browser)
				 * and its image(Ihm.php) are syncronized at every command
				 * Ihm.php must have the same positionStart & positionEnd as the IHM (Web browser)
				 */
				$params_valid = true;

				// work with IHM and Buffer
				$session->ihm->paste();

				// output define
				$output_type = 'json';
				$output['Ihm'] = getIhmAttributes();

				break;
		}

		if($params_valid){
			switch($output_type){
				case 'json':
					outputJson($output);
					break;

					/*
					 * add another output type
					 */

				default :
					outputJson($output);
					break;
			}
		}
		else{
			outputJsonError('All or one of the parameter is not well formed!', $output);
		}
	}
	else{
		outputJsonError('Function name "'.$function_name.'" could not be recognized!', $output);
	}

}

function validPostFunction($function_name_allowed){
	return is_array($function_name_allowed) && in_array($_REQUEST['function_name'], $function_name_allowed);
}

function validPostArg($arg_label){
	return isset($_REQUEST[$arg_label]) && (!empty($_REQUEST[$arg_label]) || $_REQUEST[$arg_label]==0 || $_REQUEST[$arg_label]=='0');
}

function validGetArg($arg_label){
	return isset($_GET[$arg_label]) && (!empty($_GET[$arg_label]) || $_GET[$arg_label]==0 || $_GET[$arg_label]=='0');
}

function outputJson($json){

	// output filter
	if( is_array($json) ) {
		header('Content-Type: text/javascript');
		echo json_encode( $json );
	}
	else{
		outputJsonError('Can not output with an array not initialized');
	}

}

function outputJsonError($msg, $json_error){
	$output_json = array();

	// output filter
	if( isset($msg) ){
		$output_json['ErrorMsg'] = $msg;
		$output_json['ErrorData'] = $json_error;
	}else{
		$output_json['Error'] = 'Error without feedback';
	}
	header('Content-Type: text/javascript');
	echo json_encode( $output_json );
}

function getIhmAttributes(){
	global $session;

	return array(
      'text' => $session->ihm->getText(),
      'selStart' => $session->ihm->getSelectionStart(),
      'selEnd' => $session->ihm->getSelectionEnd()
	);
}

function debug_me(){
	global $session;

	//   var_dump( spl_object_hash($session->ihm) );
	//   var_dump( $session->ihm->_ihm_hash );
	//   var_dump( spl_object_hash($session->ihm) === $session->ihm->_ihm_hash );

	return array(
    'Ihm' => array_merge(array(),array('ihm_hash' =>$session->ihm->ihm_hash) )
	);
}

ajax_handle();

?>