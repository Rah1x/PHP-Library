<?php
/**
 * Various File Management fucntions
*/


/**
 * echo safe files from a dir (used in template engines)
*/
function disp_files($x_path)
{
    $dir_handle = @opendir($x_path);
    while($file = @readdir($dir_handle))
    {
        if ($file != "." && $file != "..")
        {
            $pivot_fl = $x_path.'/'.$file;
            if(is_dir($pivot_fl)){
            disp_files($pivot_fl);
            }
            else
            {
                $piv_ext = strtolower(pathinfo(basename($pivot_fl), PATHINFO_EXTENSION));
                if(in_array($piv_ext, array('', 'css', 'png', 'jpeg', 'jpg', 'js', 'less')))
                {
                    switch($piv_ext)
                    {
                        case 'css': echo "<link type=\"text/css\" rel=\"stylesheet\" href=\"".SITE_URL."{$pivot_fl}\" />\n"; break;
                        case 'js': echo "<script type=\"text/javascript\" src=\"".SITE_URL."{$pivot_fl}\"></script>\n"; break;
                    }
                    //echo $pivot_fl;
                }
            }
        }
    }
}//end func...


/**
 * Function delete_files
 * Purpose: delete all files & folders within a given directory
*/
function delete_files($dirname)
{
   $dir_handle = false;

   if (is_dir($dirname))
      $dir_handle = opendir($dirname);
   if (!$dir_handle)
      return false;

   #/ Remove files and dirs within
   while($file = readdir($dir_handle))
   {
      if ($file != "." && $file != "..")
      {
         //var_dump($dirname.'/'.$file);
         if (!is_dir($dirname."/".$file))
         {
            @unlink($dirname."/".$file);
         }
         else
         {
            delete_files($dirname."/".$file);
            @rmdir($dirname."/".$file);
         }
      }
   }
   @closedir($dir_handle);

   return true;
}//end func...


/**
 * Check if file name exists, add 'numbers' after it
*/
function file_newname($path, $filename)
{
    if ($pos = strrpos($filename, '.')) {
           $name = substr($filename, 0, $pos);
           $ext = substr($filename, $pos);
    } else {
           $name = $filename;
    }

    $newpath = $path.'/'.$filename;
    $newname = $filename;
    $counter = 0;
    while (file_exists($newpath)) {
           $newname = $name .'_'. $counter . $ext;
           $newpath = $path.'/'.$newname;
           $counter++;
     }

    return $newname;
}
?>