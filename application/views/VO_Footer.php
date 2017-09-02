</div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <!--<script src="<?=base_url('assets/vendor/jquery/jquery.min.js')?>"></script>-->

    <!-- jQuery Form Validator -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?=base_url('assets/vendor/bootstrap/js/bootstrap.min.js')?>"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?=base_url('assets/vendor/metisMenu/metisMenu.min.js')?>"></script>

    <!-- Morris Charts JavaScript -->
    <script src="<?=base_url('assets/vendor/raphael/raphael.min.js')?>"></script>
    <script src="<?=base_url('assets/vendor/morrisjs/morris.min.js')?>"></script>
    <script src="<?=base_url('assets/data/morris-data.js')?>"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?=base_url('assets/dist/js/sb-admin-2.js')?>"></script>

    <!-- Msg Box -->
    <script src="<?=base_url('assets/js/plugins/msgbox/jquery.msgbox.min.js')?>"></script>

</body>

<script>

var langu = '<?=$init['langu']?>';

function logout(){
    setCookie("token", "", -1);     
    location.href="/"+langu+"/vo/login";
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

// UPLOADFILE: STEP 3 
function triggerUpload(id,element, picarea, width, height) {

    //不同的照片對應不同的大小   
    $("#id").val(id);         
    $("#element").val(element);
    $("#picarea").val(picarea);
    $("#width").val(width);
    $("#height").val(height);
    $("#files1").trigger("click");

}

// UPLOADFILE: STEP 4
function submitImg() {
    
    if ($("#files1").val() !== "") {
        $("#"+$("#picarea").val()).html('<img src="/assets/vo/img/ajax-loader.gif" /> Uploading,Please dont submit...');
        $("#myForm").submit();
    }
}

function deleteAttachment(id, element, picarea) {

    var tid = id.replace("file", "");

    $("#" + picarea).html("");
    $("#" + element).val("");
}

//Jquery Form Validator
$.validate({
    modules : 'security,logic',
});

</script>

</html>
