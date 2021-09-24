<pre>
<?php
#
### If in FTP you get "Output truncated to 2000 matches", use this code
### It will create several folders and move each file into them,
### dividing the number of files equally (current = 200 files per folder)
#

set_time_limit(0);

$files=scandir('.');
$total_files=count($files);

$fh=opendir('.');
$i=0;
$j=0;
$k=0;


while($file=readdir($fh))
{
	if(($file!='.') && ($file!='..'))
	{
		if($i==$k)
		{
			$j++;
			$k+=200;

			mkdir("0{$j}", 0777);
			chmod("0{$j}", 0777);
			//break;
		}

		$res=copy($file, "0{$j}/{$file}");
		var_dump($res);

		$i++;
	}//end if...
}//end while..

echo "Total Files = {$total_files}<br>Total folders = {$j}";
?>