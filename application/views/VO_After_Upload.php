<script src="<?php echo base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
<script>
$(document).ready(function(e) {
	var parentBody = window.parent.document.body
	console.log("Upload complete");
	var r = <?=!empty($filelist)?json_encode($filelist):'[]'?>;
	var html = '';
	
	if(typeof r.pu == "undefined" | r.status == "ERROR") {
		
		alert("對不起, 上傳系統出了一點問題, 目前無法上傳圖片, 請洽詢網站管理員: jwtan69@gmail.com");
		
	} else {

		html += '<div id="Pic_' + r.id + '">';
        html += '<div class="panel panel-default">';

        html += '<div class="panel-heading">';
        html += '<i class="fa fa-photo fa-fw"></i> Uploaded picture';
        html += '<div class="pull-right">';
        html += '<div class="btn-group">';

        html += '<button onclick="javascript: deleteAttachment(\'Pic_' + r.id + '\',\'' + r.element + '\',\'' + r.picarea + '\');" type="button" class="btn btn-default btn-xs">';
		html += '<span class="glyphicon glyphicon-trash"></span>';
		html += '</button>';

		html += '</div>';
        html += '</div>';
        html += '</div>';

        html += '<div class="panel-body">';
		html += '<a href="' + r.pu + '" target="_blank"><img src="' + r.pu + '" alt="' + r.filename + '" class="img-responsive" style="width:150px;"/></a>';
		html += '</div>';

		html += '</div>';
		html += '</div>';

		$("#" + r.element, parentBody).val(r.pu);
		$("#" + r.picarea, parentBody).html(html);
	}
	
	$("#myForm", parentBody).attr("action", "<?=base_url($init['langu'].'/vo/upload_handler');?>");
    $("#files1", parentBody).val("");
});
</script>