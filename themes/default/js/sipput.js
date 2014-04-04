function formatangka(objek,tanpatitik) {
	a = objek.value;
	b = a.replace(/[^\d]/g,"");
	c = "";
	panjang = b.length;
	j = 0;
	for (i = panjang; i > 0; i--) {
		j = j + 1;
		if (((j % 3) == 1) && (j != 1)) {
            if (tanpatitik)
                c = b.substr(i-1,1) + c;
            else
                c = b.substr(i-1,1) + "." + c;
		} else {
			c = b.substr(i-1,1) + c;
		}
	}
	objek.value = c;
}
function no_photo (object,url) {
	object.src = url;
	object.onerror = "";
	return true;
}
//----------------------- Chosen Select ---------------------//
if (jQuery().chosen) {
    jQuery(".chosen").chosen();

    jQuery(".chosen-with-diselect").chosen({
        allow_single_deselect: true
    });
}
//modify chosen options
var handleDropdown = function () {
    jQuery('#event_priority_chzn .chzn-search').hide(); //hide search box
    jQuery('#event_priority_chzn_o_1').html('<span class="label label-default">' + jQuery('#event_priority_chzn_o_1').text() + '</span>');
    jQuery('#event_priority_chzn_o_2').html('<span class="label label-success">' + jQuery('#event_priority_chzn_o_2').text() + '</span>');
    jQuery('#event_priority_chzn_o_3').html('<span class="label label-info">' + jQuery('#event_priority_chzn_o_3').text() + '</span>');
    jQuery('#event_priority_chzn_o_4').html('<span class="label label-warning">' + jQuery('#event_priority_chzn_o_4').text() + '</span>');
    jQuery('#event_priority_chzn_o_5').html('<span class="label label-important">' + jQuery('#event_priority_chzn_o_5').text() + '</span>');
}

jQuery('#event_priority_chzn').click(handleDropdown);