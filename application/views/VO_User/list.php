            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Manage <?=$display_name?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?=$display_name?> List
                        </div>

                        <!-- /.panel-heading -->
                        <div class="panel-body">

                            <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="dataTables_length" id="dataTables-example_length">
                                            <label>

                                                Search by Role
                                                <select  name="role" id="role" class="form-control input-sm">
                                                    <option value="ALL" <?=$role==='ALL'?'':'selected'?>>ALL</option>
                                                    <?php foreach($rolelist as $k => $v){?>
                                                        <option value="<?=$k?>" <?=$role===(string)$k?'selected':''?>><?=$v?></option>
                                                    <?php }?>
                                                </select> 

                                                Column
                                                <select onchange="showKeyword()" name="search_columns" id="search_columns" class="form-control input-sm">
                                                    <?php foreach($search_columns as $k => $v){?>
                                                        <option value="<?=$k?>" <?=$search_column==$k?'selected':''?>><?=$v?></option>
                                                    <?php }?>
                                                </select> 
                                                <input name="keyword" id="keyword" type="search" class="form-control input-sm" placeholder="keyword" value="<?=$keyword!='ALL'?$keyword:''?>">
                                                
                                                Activated
                                                <select name="activated" id="activated" class="form-control input-sm">
                                                    <?php foreach($activated_list as $k => $v){?>
                                                        <option value="<?=$k?>" <?=$activated===(string)$k?'selected':''?>><?=$v?></option>
                                                    <?php }?>
                                                </select> 


                                                <button onclick="toSearch()" type="button" class="btn btn-primary btn-sm">
                                                    Submit
                                                </button>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div id="dataTables-example_filter" class="dataTables_filter">
                                            <button onclick="exportDoc('PDF')" type="button" class="btn btn-primary btn-sm">
                                                Export PDF
                                            </button>
                                            <button onclick="exportDoc('EXCEL')" type="button" class="btn btn-primary btn-sm">
                                                Export EXcel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.row -->
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                            <thead>
                                                <tr>
                                                    <th width="1%">No</th>
                                                    <th width="5%">Avatar</th>
                                                    <th>Name</th>
                                                    <th>Role</th>
                                                    <th>Email</th>
                                                    <th>Mobile</th>
                                                    <th width="1%">Activated</th>
                                                    <th >Created Date<br>Modified Date</th>
                                                    <th>Control</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(empty($results)){?>
                                                <tr class="odd">
                                                    <td colspan="8">No record found</td>
                                                <tr>
                                                <?php }?>

                                                <?php foreach($results as $k => $v){?>
                                                <tr class="odd">
                                                    <td><?=$data_start_no+$k?></td>
                                                    <td><img class="img-responsive" src="<?=$v['avatar']?>"></td>
                                                    <td><?=$v['name']?></td>
                                                    <td><?=$rolelist[$v['role_id']]?></td>
                                                    <td><?=$v['email']?></td>
                                                    <td><?=$v['mobile']?></td>
                                                    <td><i class="fa <?=$v['activated']==1?'fa-check':'fa-times'?>"></i></td>
                                                    <td><?=$v['created_date']?><br><?=$v['modified_date']?></td>
                                                    <td>
                                                        <a href="<?=base_url($init['langu'].'/vo/users/edit/'.$v['user_id'])?>" type="button" class="btn btn-warning btn-circle" title="Edit">
                                                            <i class="glyphicon glyphicon-edit"></i>
                                                        </a>
                                                        <a href="javascript:Delete('<?=$v['user_id']?>')" type="button" class="btn btn-danger btn-circle" title="Delete">
                                                            <i class="fa fa-times"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php }?>
                                            </tbody>
                                        </table>
                                        <!-- /.table-responsive -->
                                    </div>
                                </div>
                                <!-- /.row -->
                              
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing <?=$data_start_no?> to <?=$data_end_no?> of <?=$total?> entries</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="dataTables_paginate paging_simple_numbers" id="dataTables-example_paginate">
                                            <?=$paging?>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.row -->
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <?php echo $this->benchmark->elapsed_time();?>
            <?php echo $this->benchmark->memory_usage();?>

<script>  
$(function() {
    showKeyword();
});

function Delete(id){
    $.msgbox("Are you sure that you want to permanently delete the selected element?", {
      type: "confirm",
      buttons : [
        {type: "submit", value: "Yes"},
        {type: "submit", value: "No"}
      ]
    }, function(result) {
        if(result == 'Yes'){
            location.href = '<?=  base_url($init['langu'].'/vo/users/delete')?>/'+id;
        }
    });
} 

function toSearch(){

    var role = $("#role").val();
    var search_columns = $("#search_columns").val();
    var keyword = $("#keyword").val();
    var activated = $("#activated").val();

    if(keyword == "" || search_columns == 'ALL') {
        keyword = "ALL";
    }

    location.href="<?=base_url($init['langu'].'/vo/users/list')?>"+"/"+role+"/"+search_columns+"/"+keyword+"/"+activated;

}

function showKeyword(){

    var val = $('#search_columns').val();

    if(val=='ALL'){
        $('#keyword').hide();
    }else{
        $('#keyword').show();
    }
}

function exportDoc(type){

    var role = $("#role").val();
    var search_columns = $("#search_columns").val();
    var keyword = $("#keyword").val();
    var activated = $("#activated").val();
    var page = '<?=$page?>';
    var pdf = 0;
    var excel = 0;

    if(keyword == "" || search_columns == 'ALL') {
        keyword = "ALL";
    }

    if(type == 'PDF'){
        pdf = 1;
    }else if(type == 'EXCEL'){
        excel = 1;
    }

    window.open("<?=base_url($init['langu'].'/vo/users/list')?>"+"/"+role+"/"+search_columns+"/"+keyword+"/"+activated+"/"+page+"/"+pdf+"/"+excel,"_blank");
}

</script>
