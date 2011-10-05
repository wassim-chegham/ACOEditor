/*!
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

$(function(){

	// triggered on load
	init();
	
	set_versions();
	
	$('#'+config.html.textareaId).bind("select", function(e){
		
		config.copycut.active = true;
		$('#copy, #cut').attr('disabled', false);
		updateSelection(e);
		debug();
		
	}).bind("click", function(e){

		updateSelection(e);		
		debug();

	}).keypress(function(e){

		var o = getChar(e);
		var char = o.char;
		e.preventDefault();

		$('#replay').attr('disabled', false);
		
		if( (o.code >= 33 && o.code <= 126) 
				|| o.code == 13 
				|| o.code == 32 ){
			insert(o.char);
			debug();
		}
	
	});

	$('#new').click(
			function(e){
				e.preventDefault();
				var is_empty = $('#'+config.html.textareaId).val() == "";
				
				if ( !is_empty )
				{
					var c = confirm("Warning! All unsaved changes will be lost! Do you want to continue ?");
					if ( c )
						window.location = "?clear"
				}
			} 
	);
	
	$('#cut').click(
			function(e){
				e.preventDefault();
				if ( getSelText(config.html.textareaId) != "" )
				{
					cut();
					debug();
				}
				
			}
	);

	$('#copy').click(
			function(e){
				e.preventDefault();
				if ( getSelText(config.html.textareaId) != "" )
				{
					copy();
					debug();
				}
			}
	);

	$('#paste').click(
			function(e){
				e.preventDefault();
				paste();
				debug();
			}
	);

    $('#redo').click(
			function(e){
				e.preventDefault();
				redo();
				debug();
			}
	);

    $('#undo').click(
			function(e){
				e.preventDefault();
				undo();
				debug();
			}
	);

	$('#replay').click(function(e){
		
		e.preventDefault();
		
		// start replaying if timer is not set
		if ( config.replay.timer === null )
		{
			runReplay();
		}
		
		// on stop
		else {
			stopReplay();
		}
		
	});
	
	$('#dump').click(function(){
		config.debug.dump = true;
		debug();
		$('#console').dialog('open');
	});
	
	$('#console').dialog({
		autoOpen:false,
		title: $('#content h1').html() + " - Object Memory Dump",
		width:800,
		position: 'center',
		close: function(event, ui){
			config.debug.dump = false;	
		}
	});
	
	
	$('#content').draggable().resizable();
	setTimeout(function(){ $('#content').fadeIn().animate({top:10}); }, 1000);
	
	
});
