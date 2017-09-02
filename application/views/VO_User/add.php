<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><?=$mode?> <?=$display_name?></h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?=$display_name?> Form
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">

                        <div style="display:none">
                            <iframe id="uploadtarget" name="uploadtarget" height="0" width="0" frameborder="0" scrolling="yes"></iframe>
                            <form id="myForm" method="post" enctype="multipart/form-data" action="<?=base_url($init['langu'].'/vo/upload_handler');?>" target="uploadtarget">
                                <input type="file" id="files1" name="files1" onchange="submitImg()" />
                                <input type="hidden" id="id" name="id" value=""/>
                                <input type="hidden" id="element" name="element" value=""/>
                                <input type="hidden" id="picarea" name="picarea" value=""/>
                                <input type="hidden" id="width" name="width" value=""/>
                                <input type="hidden" id="height" name="height" value=""/>
                            </form>
                        </div>

                        <form role="form" action="<?=base_url($init['langu'].'/vo/users/submit')?>" method="post" id="validation-form" enctype="multipart/form-data" onsubmit="return validate();">
                            <input type="hidden" name="mode" id="mode" value="<?=$mode?>">
                            <input type="hidden" name="id" id="id" value="<?=($mode=='Edit')?$results['user_id']:''?>">
                            <div class="form-group">
                                <label>Role</label>
                                <select class="form-control" name="role_id" id="rolelist">
                                    <?php
                                        if(!empty($rolelist)){
                                            foreach($rolelist as $k=>$v): 
                                    ?>
                                                <option value="<?=$k?>" <?=($mode=='Edit')&&($results['role_id']==$k)?'selected="selected"':''?>><?=$v?></option>
                                    <?php
                                            endforeach;
                                        }
                                    ?>
                                </select>
                                
                            </div>

                            <div class="form-group">
                                <label>Name*</label>
                                <input data-validation="length alphanumeric" data-validation-length="3-12"  class="form-control" name="name" id="name" value="<?=($mode=='Edit')?$results['name']:''?>">
                            </div>

                            <div class="form-group">
                                <label>Email*</label>
                                <input data-validation="email" class="form-control" name="email" id="email" value="<?=($mode=='Edit')?$results['email']:''?>">
                            </div>

                            <?php
                                $default_password = rand(100000,999999);
                            ?>

                            <div class="form-group">
                                <label>Password*</label>
                                <?=($mode=='Edit')?'':'<p style="color:red;">Default password : '.$default_password.'</p>'?>
                                <input <?=($mode=='Edit')?'data-validation-optional="true"':''?> data-validation="length" data-validation-length="min6" type="password" class="form-control" name="password" id="password" value="<?=($mode=='Edit')?'':$default_password?>" placeholder="Please leave blank if password unchange">
                            </div>

                            <div class="form-group">
                                <label>Confirm Password*</label>
                                <input data-validation="confirmation" data-validation-confirm="password"  type="password" class="form-control" name="repassword" id="repassword" value="<?=($mode=='Edit')?'':$default_password?>" placeholder="Please leave blank if password unchange">

                            </div>

                            <div class="form-group">
                                <label>Mobile</label>
                                <input class="form-control" name="mobile" id="mobile" value="<?=($mode=='Edit')?$results['mobile']:''?>">
                            </div>

                            <div class="form-group">
                                <label>Gender</label>
                                <label class="radio-inline">
                                    <input type="radio" name="gender" id="gender-m" value="M" <?=($mode == 'Edit')?($results['gender']=='M')?'checked="checked"':'':'checked="checked"'?>>Male
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="gender" id="gender-f" value="F" <?=($mode == 'Edit')?($results['gender']=='F')?'checked="checked"':'':''?>>Female
                                </label>
                            </div>

                            <div class="form-group">
                                <label>Avatar</label>
                                
                                <div id="avatar_uploaded">
                                  <?php 
                                  if($mode == "Edit"){
                                    if($results['avatar'] != ""){
                                    ?>
                                        <div id="Pic_1">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <i class="fa fa-photo fa-fw"></i> Uploaded picture
                                                    <div class="pull-right">
                                                        <div class="btn-group">
                                                            <button onclick="javascript: deleteAttachment('Pic_1','avatar','avatar_uploaded');" type="button" class="btn btn-default btn-xs">
                                                                <span class="glyphicon glyphicon-trash"></span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel-body">
                                                    <a href="<?=$results['avatar']?>" target="_blank"><img src="<?=$results['avatar']?>" alt="<?=$results['avatar']?>" class="img-responsive" style="width:150px;"/></a>
                                                </div>
                                            </div>
                                        </div>


                                    <?php 
                                    }
                                  }
                                  ?>
                                </div>
                                
                                <input type="button" name="picUpload" onclick="triggerUpload('1','avatar', 'avatar_uploaded', 480, 480)" value="Upload Picture" />
                                <input type="hidden" class="form-control" name="avatar" id="avatar" value="<?=($mode=='Edit')?$results['avatar']:''?>">

                                <p class="help-block" style="color:red;">Recommended resolution: 480x480</p>
                            </div>

                            <div class="form-group">
                                <label>Activated</label>
                                <label class="radio-inline">
                                    <input type="radio" name="activated" id="activated-y" value="1" <?=($mode == 'Edit')?($results['activated']=='1')?'checked="checked"':'':'checked="checked"'?>>Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="activated" id="activated-n" value="0" <?=($mode == 'Edit')?($results['activated']=='0')?'checked="checked"':'':''?>>No
                                </label>
                            </div>

                            <button type="submit" class="btn btn-default">Submit</button>
                            <button onClick="window.location.reload()" type="reset" class="btn btn-default">Reset</button>
                        </form>
                    </div>
                    
                </div>
                <!-- /.row (nested) -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->



