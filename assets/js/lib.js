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

function set_versions(){
	
	var val = config.version;
	var buttons = $('button:not(#new):not(#dump)');
	var el = $('#select-versions');
	change_version(el);
	
	el.change(function(){
		change_version();
	});

	function change_version()
	{
		buttons.hide();
		$("#title span").text(el.val());
		switch(el.val())
		{
		case "1":
			buttons.filter("#copy, #cut, #paste").show();
			break;
		
		case "2":
			buttons.filter("#copy, #cut, #paste, #undo, #redo").show();			
			break;
		case "3":
			buttons.filter("#copy, #cut, #paste, #undo, #redo, #replay").show();			
			break;
		}
	};
	
}

/**
 * 
 * @param evt
 * @return
 */
function getChar(evt) {
	
	var char = '#';
	
	var char, charCode;
	if (evt.which == null)
	{
		charCode = evt.keyCode;
		char = String.fromCharCode(charCode);    // IE
	}
	else if (evt.which != 0 && evt.charCode != 0)
	{
		charCode = evt.which;
		char = String.fromCharCode(charCode);	  // All others
	}
	// special keys
	else {
		
	}
	
	return {
		char : char,
		code : charCode
	};
}

/**
 * 
 * @return
 */
function debug() {
	if ( config.debug.dump ) $('#pre').load('index.php?debug');
}

/**
 * 
 * @return
 */
function reset() {
	$.ajax( {
		url : config.ajax.target,
		type : config.ajax.type,
		data : {
			function_name : 'reset'
		},
		dataType : config.ajax.dataType,
		beforeSend : function() {
		},
		success : function(response) {
			update(config.html.textareaId, response.Ihm);
			debug();
		},
		error : function(e) {
		},
		complete : function() {
		}

	});
}

/**
 * 
 * @return
 */
function enableButtons() {
	$('button:not(#replay)').attr('disabled', false);
	$('#' + config.html.textareaId).attr('disabled', false);
}

/**
 * 
 * @return
 */
function disableButtons() {
	$('button:not(#replay)').attr('disabled', true);
	$('#' + config.html.textareaId).attr('disabled', true);
}


/**
 * 
 * @return
 */
function enableRedo(){
	$('button#redo').attr('disabled', false);
}

/**
 * 
 * @return
 */
function disableRedo(){
	$('button#redo').attr('disabled', true);
} 

/**
 * 
 * @return
 */
function enableUndo(){
	$('button#undo').attr('disabled', false);
}

/**
 * 
 * @return
 */
function disableUndo(){
	$('button#undo').attr('disabled', true);
}

/**
 * 
 * @return
 */
function enablePaste(){
	$('#paste').attr('disabled', false);
}

/**
 * 
 * @return
 */
function disablePaste(){
	$('#paste').attr('disabled', false);
}


/**
 * 
 * @return
 */
function redo() {

	$.ajax( {
		url : config.ajax.target,
		type : config.ajax.type,
		data : {
			function_name : 'redo'
		},
		dataType : config.ajax.dataType,
		beforeSend : function() {
		},
		success : function(response) {
			enableUndo();
			update(config.html.textareaId, response.Ihm);
			if (response != null && response.Ihm.redo_last) {
				disableRedo();
			} else {
				debug();

			}

		},
		error : function(e) {
		},
		complete : function() {
		}

	});
}

/**
 * 
 * @return
 */
function undo() {

	$.ajax( {
		url : config.ajax.target,
		type : config.ajax.type,
		data : {
			function_name : 'undo'
		},
		dataType : config.ajax.dataType,
		beforeSend : function() {
		},
		success : function(response) {
			enableRedo();
			update(config.html.textareaId, response.Ihm);
			if (response != null && response.Ihm.undo_last) {
				disableUndo();
			} else {
				debug();

			}

		},
		error : function(e) {
		},
		complete : function() {
		}

	});
}


/**
 * 
 * @return
 */
function replay() {

	$.ajax( {
		url : config.ajax.target,
		type : config.ajax.type,
		data : {
			function_name : 'replay'
		},
		dataType : config.ajax.dataType,
		beforeSend : function() {
		},
		success : function(response) {

			if (response != null && response.Ihm.replay_done) {

				stopReplay();

			} else {

				update(config.html.textareaId, response.Ihm);
				debug();

			}

		},
		error : function(e) {
		},
		complete : function() {
		}

	});
}

function runReplay() {
	reset();
	disableButtons();
	$('#replay').text('Stop').addClass('playing');
	config.replay.timer = setInterval(function() {

		replay();

	}, config.replay.duration);
}

/**
 * 
 * @return
 */
function stopReplay() {
	clearInterval(config.replay.timer);
	config.replay.timer = null; // important to start an other replay 
	enableButtons();
	$('#replay').text('Replay').removeClass('playing');
}

/**
 * 
 * @return
 */
function init() {
	$.ajax( {
		url : config.ajax.target,
		type : config.ajax.type,
		data : {
			function_name : 'init'
		},
		dataType : config.ajax.dataType,
		beforeSend : function() {
		},
		success : function(response) {
			update(config.html.textareaId, response.Ihm);
			
			if (response.Ihm.text === "") {
				$('#replay, #paste, #undo, #redo').attr('disabled', true);
				
			} else {
				$('#replay, #paste, #undo, #redo').attr('disabled', false);
				
				if ( response.Ihm.selStart != response.Ihm.selEnd )
				{
					$('#copy, #cut').attr('disabled', false);
				}
			}

		},
		error : function(e) {
		},
		complete : function() {
		}

	});
};

/**
 * 
 * @return
 */
function copy() {

	var prev_selection = {};

	$.ajax( {
				url : config.ajax.target,
				type : config.ajax.type,
				data : {
					// paramètres envoyés
				function_name : 'copy'
			},
				dataType : config.ajax.dataType, // type de données recues

				beforeSend : function() {
					if (config.debug.all && config.debug.copy) {
						console.log("__________________________");
						console.log("copy");
						console.log("out --->");
						console.log("nothing");
					}

					prev_selection.selStart = getSelectionStart(config.html.textareaId);
					prev_selection.selEnd = getSelectionEnd(config.html.textareaId);
					setSelection(config.html.textareaId, prev_selection);

				},
				success : function(response) {
					disableRedo(); 
					enableUndo(); 
					enablePaste();
					
					if (response != undefined && response.ErrorMsg) {
						console.log(response.ErrorMsg);
						console.log(response.ErrorData);
					}

					if (config.debug.all && response.debug) {
						console.log("_PHP DEBUG_");
						console.log("selStart:" + response.debug.Ihm.selStart);
						console.log("selEnd:" + response.debug.Ihm.selEnd);
						console.log("text:" + response.debug.Ihm.text);
						console.log("ihm_hash:" + response.debug.Ihm.ihm_hash);
						console.log("_PHP DEBUG_");
					}

					if (config.debug.all && config.debug.copy) {
						console.log("<--- in");
					}

				},

				error : function(e) {
					console.log("copy error" + e);
				},

				complete : function() {
					if (config.debug.all && config.debug.copy)
						console.log("copy complete");

					setSelection(config.html.textareaId, prev_selection);
				}

			});
};

/**
 * 
 * @return
 */
function paste() {
	$.ajax( {
		url : config.ajax.target,
		type : config.ajax.type,
		data : {
			// paramètres envoyés
		function_name : 'paste'
	},
	dataType : config.ajax.dataType, // type de données recues

		beforeSend : function() {
			if (config.debug.all && config.debug.paste) {
				console.log("__________________________");
				console.log("paste");
				console.log("out --->");
				console.log("nothing");
			}
		},
		success : function(response) {
			disableRedo(); 
			enableUndo();

			if (response != undefined && response.ErrorMsg) {
				console.log(response.ErrorMsg);
				console.log(response.ErrorData);
			}
			if (config.debug.all && response.debug) {
				console.log("_PHP DEBUG_");
				console.log("selStart:" + response.debug.Ihm.selStart);
				console.log("selEnd:" + response.debug.Ihm.selEnd);
				console.log("text:" + response.debug.Ihm.text);
				console.log("ihm_hash:" + response.debug.Ihm.ihm_hash);
				console.log("_PHP DEBUG_");
			}
			if (config.debug.all && config.debug.paste) {
				console.log("<--- in");
				console.log("selStart:" + response.Ihm.selStart);
				console.log("selEnd:" + response.Ihm.selEnd);
				console.log("text:" + response.Ihm.text);
			}
			if (response != undefined && response.ErrorMsg) {
				console.log(response.Error);
				console.log(response.ErrorData);
			}
			update(config.html.textareaId, response.Ihm);
		},
		error : function(e) {
			console.log("paste error" + e);
		},
		complete : function() {
		}
	});
};

/**
 * 
 * @param char
 * @return
 */
function insert(char) {
	$.ajax( {
		url : config.ajax.target,
		type : config.ajax.type,
		data : {
			// paramètres envoyés
			function_name : 'insert',
			char : char
		},
		dataType : config.ajax.dataType, // type de données recues

		beforeSend : function() {
			if (config.debug.all && config.debug.char) {
				console.log("__________________________");
				console.log("insert");
				console.log("out --->");
				console.log("char:" + char);
			}
		},
		success : function(response) {
			disableRedo(); 
			enableUndo();
			
			if (response != undefined && response.ErrorMsg) {
				console.log(response.ErrorMsg);
				console.log(response.ErrorData);
			}
			if (config.debug.all && response.debug) {
				console.log("_PHP DEBUG_");
				console.log("selStart:" + response.debug.Ihm.selStart);
				console.log("selEnd:" + response.debug.Ihm.selEnd);
				console.log("text:" + response.debug.Ihm.text);
				console.log("ihm_hash:" + response.debug.Ihm.ihm_hash);
				console.log("_PHP DEBUG_");
			}
			if (config.debug.all && config.debug.char) {
				console.log("<--- in");
				console.log("selStart:" + response.Ihm.selStart);
				console.log("selEnd:" + response.Ihm.selEnd);
				console.log("text:" + response.Ihm.text);
			}
			update(config.html.textareaId, response.Ihm);

		},
		error : function(e) {
			console.log("insert error" + e);
		},
		complete : function() {
		}
	});
};

/**
 * 
 * @return
 */
function cut() {
	$.ajax( {
		url : config.ajax.target,
		type : config.ajax.type,
		data : {
			// paramètres envoyés
		function_name : 'cut'
	},
	dataType : config.ajax.dataType, // type de données recues

		beforeSend : function() {
			if (config.debug.all && config.debug.cut) {
				console.log("__________________________");
				console.log("cut");
				console.log("out --->");
				console.log("nothing");
			}
		},
		success : function(response) {
			disableRedo(); 
			enableUndo();
			enablePaste();
			
			if (response != undefined && response.ErrorMsg) {
				console.log(response.ErrorMsg);
				console.log(response.ErrorData);
			}
			if (config.debug.all && response.debug) {
				console.log("_PHP DEBUG_");
				console.log("selStart:" + response.debug.Ihm.selStart);
				console.log("selEnd:" + response.debug.Ihm.selEnd);
				console.log("text:" + response.debug.Ihm.text);
				console.log("ihm_hash:" + response.debug.Ihm.ihm_hash);
				console.log("_PHP DEBUG_");
			}
			if (config.debug.all && config.debug.cut) {
				console.log("<--- in");
				console.log("selStart:" + response.Ihm.selStart);
				console.log("selEnd:" + response.Ihm.selEnd);
				console.log("text:" + response.Ihm.text);
			}
			update(config.html.textareaId, response.Ihm);
		},
		error : function(e) {
			console.log("cut error" + e);
		},
		complete : function() {
		}
	});
};

/**
 * 
 * @return
 */
function updateSelection() {

	var textarea = config.html.textareaId;

	$.ajax( {
		url : config.ajax.target,
		type : config.ajax.type,
		data : {
			// paramètres envoyés
			function_name : 'updateSelection',
			selStart : getSelectionStart(textarea),
			selEnd : getSelectionEnd(textarea)
		},

		dataType : config.ajax.dataType, // type de données recues

		beforeSend : function() {
			if (config.debug.all && config.debug.selection) {
				console.log("__________________________");
				console.log("updateSelection");
				console.log("out --->");
				console.log("selStart:" + getSelectionStart(textarea));
				console.log("selEnd:" + getSelectionEnd(textarea));
			}
		},

		success : function(response) {
			if (response != undefined && response.ErrorMsg) {
				console.log(response.ErrorMsg);
				console.log(response.ErrorData);
			}
			if (config.debug.all && response.debug) {
				console.log("_PHP DEBUG_");
				console.log("selStart:" + response.debug.Ihm.selStart);
				console.log("selEnd:" + response.debug.Ihm.selEnd);
				console.log("text:" + response.debug.Ihm.text);
				console.log("ihm_hash:" + response.debug.Ihm.ihm_hash);
				console.log("_PHP DEBUG_");
			}

			update(textarea, response.Ihm);

			if (config.debug.all && config.debug.selection) {
				console.log("<--- in");
				console.log("nothing");
			}
		},

		error : function(e) {
			if (config.debug.all && config.debug.selection)
				console.log("updateSelection error" + e);
		},

		complete : function() {
		}

	});
};

/**
 * 
 * @param id
 * @return
 */
function getCursor(id) {
	var el = document.getElementById(id);

	if (typeof el.selectionStart != 'undefined')
		return el.selectionStart;
	// IE Support
	el.focus();
	var range = el.createTextRange();
	range.moveToBookmark(document.selection.createRange().getBookmark());
	range.moveEnd('character', el.value.length);
	return el.value.length - range.text.length;

}

/**
 * 
 * @param obj_id
 * @return
 */
function getSelectionStart(obj_id) {
	var obj = document.getElementById(obj_id);

	var CaretPos = 0;
	// IE Support
	if (document.selection) {

		obj.focus();
		var Sel = document.selection.createRange();

		Sel.moveStart('character', -obj.value.length);

		CaretPos = Sel.text.length;
	}
	// Firefox support
	else if (obj.selectionStart || obj.selectionStart == '0')
		CaretPos = obj.selectionStart;

	return (CaretPos);
}

/**
 * 
 * @param obj_id
 * @return
 */
function getSelectionEnd(obj_id) {
	var obj = document.getElementById(obj_id);

	var caretPos = getSelectionStart(obj_id);
	var text = getSelText(obj_id);
	if (text.length > 0 || isNaN(text.length)) {
		caretPos += text.length;
	}

	return caretPos;
}

/**
 * 
 * @param obj_id
 * @return
 */
function getSelectedText(obj_id) {
	var obj = document.getElementById(obj_id);

	if (window.getSelection) {
		var str = window.getSelection();
	} else if (document.getSelection) {
		var str = document.getSelection();
	} else {
		var str = document.selection.createRange().text;

	}
	return str;
}

/**
 * 
 * @param obj_id
 * @return
 */
function getSelText(obj_id) {
	var txtarea = document.getElementById(obj_id);

	var txt = '';
	if (window.getSelection) {
		txt = (txtarea.value).substring(txtarea.selectionStart,
				txtarea.selectionEnd);
	} else if (document.getSelection) {
		txt = document.getSelection().toString();
	} else if (document.selection) {
		txt = document.selection.createRange().text;
	}
	return txt;
}

/**
 * 
 * @param obj_id
 * @param Ihm
 * @return
 */
function update(obj_id, Ihm) {

	var obj = document.getElementById(obj_id);

	obj.value = Ihm.text;

	setSelection(obj_id, Ihm);

}

/**
 * 
 * @param obj_id
 * @param Ihm
 * @return
 */
function setSelection(obj_id, Ihm) {
	var obj = document.getElementById(obj_id);

	if (obj.setSelectionRange) {
		obj.focus();
		obj.setSelectionRange(Ihm.selStart, Ihm.selEnd);
	} else if (obj.createTextRange) {
		var range = obj.createTextRange();
		range.collapse(true);
		range.moveStart('character', Ihm.selStart);
		range.moveEnd('character', Ihm.selEnd);
		range.select();
	}

}

/**
 * 
 * @param pos
 * @param obj_id
 * @return
 */
function setCursor(pos, obj_id) {
	var obj = document.getElementById(obj_id);

	if (obj.setSelectionRange) {
		obj.focus();
		obj.setSelectionRange(pos, pos);
	} else if (obj.createTextRange) {
		var range = obj.createTextRange();
		range.collapse(true);
		range.moveEnd('character', pos);
		range.moveStart('character', pos);
		range.select();
	}
}

/**
 * 
 * @param str
 * @param obj_id
 * @return
 */
function setText(str, obj_id) {
	var obj = document.getElementById(obj_id);

	// Get the selection bounds
	var start = obj.selectionStart;
	var end = obj.selectionEnd;

	// Break up the text by selection
	var text = obj.value;
	var pre = text.substring(0, start);
	var sel = text.substring(start, end);
	var post = text.substring(end, text.length);

	// Insert the text at the beginning of the selection
	text = pre + str + sel + post;

	// Put the text in the textarea
	obj.value = text;

	// Re-establish the selection, adjusted for the added characters.
	obj.selectionStart = end;
	obj.selectionEnd = end;
}

/**
 * 
 * @param obj_id
 * @return
 */
function removeSelection(obj_id) {
	var obj = document.getElementById(obj_id);

	// Get the selection bounds
	var start = obj.selectionStart;
	var end = obj.selectionEnd;

	// Break up the text by selection
	var text = obj.value;
	var pre = text.substring(0, start);
	var sel = text.substring(start, end);
	var post = text.substring(end, text.length);

	// Insert the text at the beginning of the selection
	text = pre + post;

	// Put the text in the textarea
	obj.value = text;

	// Re-establish the selection, adjusted for the added characters.
	obj.selectionStart = start;
	obj.selectionEnd = end;
}
