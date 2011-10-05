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

var config = {};
config.debug = {};
config.ajax = {};
config.html = {};
config.replay = {};
config.copycut = {};
config.version = 2;

config.ajax.target = "index.php?ajax";
config.ajax.type = "POST";
config.ajax.dataType = "json";

config.debug.all = false;
config.debug.selection = true;
config.debug.char = true;
config.debug.paste = true;
config.debug.copy = true;
config.debug.cut = true;
config.debug.dump = false;

config.html.textareaId = 'text';
config.html.logId = 'log';

config.replay.duration = 800; // ms
config.replay.timer = null;

config.copycut.active = false;

// console.log() hack
if ( console )
{
	// nothing
}
else {
	var console = {};
	console.log = function(str){ return str; };
}