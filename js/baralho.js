/* Este arquivo foi criado para uso único e particular no site mestredoselementos
 * Author: Diego Mousine
 */

/* Funções para edição do deck não interfere no combate */
function decker(id, name, val) {
	var i = 0;
	while($('#'+i).text() != '' && i < 15) {
		i++;
	}
	if($('#'+i).text() == '' && i < 15) {
		$('#'+i).html(name+"<input id='sel_"+i+"' name='sel_"+i+"' value='"+val+"' hidden/>");
		$('#'+i).show();
		$('#'+id).hide();
	}
	while($('#'+i).text() != '' && i < 15) {
		if(i==14){
			$("#deck-btn-submit").removeAttr("disabled");
		}
		i++;
	}
}
function undecker(id) {
	$('#carta_'+$('#sel_'+id).val()).show();
	$('#'+id).html('');
	$('#'+id).hide();
	$("#deck-btn-submit").attr("disabled", "disabled");
}