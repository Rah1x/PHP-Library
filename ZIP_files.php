<?php
###/ CHECK.........
if(@$_POST['title']=='[ROOT]' && (is_uploaded_file(@$_FILES['template_assets']['tmp_name'])))
{
    if($_FILES['template_assets']['type']!='application/zip')
    {
       $fv_errors[] = array('Please select the Asset file in .ZIP format only!');
    }
    else
    {
        $temp_file = @$_FILES['template_assets']['tmp_name'];
        $za = new ZipArchive();
        if($za->open($temp_file) != true)
        {
            $fv_errors[] = array('Error reading the Assets ZIP file!');
        }
        else if($za->numFiles <=0)
        {
            $fv_errors[] = array('The uploaded ZIP file is empty!');
        }
        else{
        for ($i=0; $i<$za->numFiles;$i++)
        {
            //var_dump_p($za->getNameIndex($i), strtolower(pathinfo(basename($za->getNameIndex($i)), PATHINFO_EXTENSION)));
            if(!in_array(strtolower(pathinfo(basename($za->getNameIndex($i)), PATHINFO_EXTENSION)), array('', 'css', 'png', 'jpeg', 'jpg', 'js', 'less')))
            {
                $fv_errors[] = array('The Assets zip file must only contain images/css/js files!');
                break;
            }
        }
        }
        //var_dumpx($_FILES['template_assets'], $za, $za->numFiles, $fv_errors);
        @$za->close();
    }
}

////////////////////////////////////////////
###/ MOVE.........
if(@$_POST['title']=='[ROOT]' && (is_uploaded_file(@$_FILES['template_assets']['tmp_name'])))
{
    $temp_file = @$_FILES['template_assets']['tmp_name'];
    $za = new ZipArchive();
    if($za->open($temp_file) != true){}
    else
    {
        $x_path = "{$up_path}/template/";
        if(!is_dir($x_path)){mkdir($x_path, 0705, true);}

        #/ Delete current ones
        include_once("../../includes/file_mgt.php");
        delete_files($x_path);

        #/ move new
        $za->extractTo($x_path);
    }
    @$za->close();
}//end if zip...
#-
?>