
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<html>
 <head>
  <title>Shared Music Space</title>

	<link href="/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="/uploadify/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="/uploadify/swfobject.js"></script>
<script type="text/javascript" src="/uploadify/jquery.uploadify.v2.1.4.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  $('#file_upload')
  .uploadify({
    'uploader'  : '/uploadify/uploadify.swf',
    'script'    : '/uploadify/uploadify.php',
    'cancelImg' : '/uploadify/cancel.png',
    'multi'		: true,
	'displayData': 'percentage',
	'buttonText' : 'Select Files',
	'sizeLimit'   : 6442450944,
	'onAllComplete' : function(){ location.reload("script.php"); },
	'fileExt' 	: '*.mp3; *.wav; *.3gp; *.aac; *.flac; *.wav; *.wma; *.alac',
	'fileDesc'  : 'Music Files'
  });
  alert(notes.user);
});
</script>
 </head>
 <body>
<h1 align='center'>Shared Music Space</h1>

<?php

$pos = strripos(getcwd(), "\\Music");
echo "<br><h2> You are now in : ".substr(getcwd(),$pos+1). " Folder</h2>";

?>

<form action="../" method="post" name="back">
	<input name="back" type="submit" value="Back">
</form>
<br>

<h2> Select Files to Upload </h2>
<p> Multiple Files can be selected and uploaded now </p>
<input id="file_upload" name="file_upload" type="file" />
<p />
<a href="javascript:$('#file_upload').uploadifyUpload();">Upload Files</a>
<div>

<br>
<br>

<form method="post">
<input type="text" name="foldername" placeholder="New Folder" />
<input type="submit" name="createfolder" value="Create Folder" />
</form>

<?php


if(isset($_POST['createfolder'])){
	$thisdir = getcwd();
	$folder = $_POST['foldername'];
	if(file_exists($thisdir."/".$folder)){
		echo "ERROR : The file $folder already exists";
	}
	else{
		if(mkdir($thisdir . "/".$folder,0700)){
			$file = 'index.php';
			$newfile = $folder."/index.php";
			copy($file, $newfile);
			echo $folder." : Directory has been created successfully...";
		}
		else{
			echo "ERROR : Failed to create directory...";
		}
	}
}

?>

<?php

$nameslist = array();
$sizelist = array();
$count = 0;

 if ($handle = opendir('.')) {
   while (false !== ($file = readdir($handle)))
      {
          if ($file != "." && $file != ".." && $file != "index.php")
	  {
          	$nameslist[$count] = '| <a href="'.$file.'">'.$file.'</a>';
			if(filesize($file) == "0" && ((substr($file,-4,-3)!=".") && (substr($file,-5,-4)!="."))){
				$sizelist[$count] = "Folder";
			}
			else{
				$sizelist[$count] = round(((filesize($file)/1000)/1024),2).' mb';
			}
			$count = $count+1;
          }
       }
  closedir($handle);
  }

if($count!=0){
	echo "<P>List of files:</p>";
	
	echo '<table border="1" width = "850 px">';
	echo '<tr><th>File Name</th> <th>File Size</th> <th>Delete</th> </tr>';
	
	for ($i = 0; $i<$count; $i++){
		echo "<tr><td>$nameslist[$i]</td> <td align='center'>$sizelist[$i]</td>
		<th>
		<form method='POST'>
		<input type='submit' name='Delete$i' value='Delete' />
		</form>
		</th>
		</tr>";
	}
	
	echo '<tr><th>File Name</th> <th>File Size</th> <th>Delete</th> </tr>';
	echo "</table>";
}

?>

<?php

$cell = "";
for ($i = 0; $i < 100; $i++){
	$cell = "Delete".$i;
	if(isset($_POST[$cell])){
		$nameslist = array();
		$count = 0;

		 if ($handle = opendir('.')) {
		   while (false !== ($file = readdir($handle)) && $count<=$i)
			  {
				  if ($file != "." && $file != ".." && $file != "index.php")
			  		{
						$nameslist[$count] = $file;
						if($count == $i){
							if(filesize($file) != 0 || substr($file,-4,-3)=="." || substr($file,-5,-4)=="."){
								unlink($nameslist[$i]);
								echo "<script> window.location.href='index.php'</script>";
								echo "File Deleted Successfully...";
							}
							else{
								deleteDir($nameslist[$count]);
								echo "<script> window.location.href='index.php'</script>";
								echo "$nameslist[$count] Delete Successfully";	
							}
						}
						$count = $count+1;
				  }
			   }
		  closedir($handle);
		  }
	}

}

function deleteDir($dir){
	if(substr($dir, strlen($dir)-1,1)!='/')
		$dir .= '/';
		
	if ($handle = opendir($dir)){
		while($obj = readdir($handle)){
			if($obj != '.' && $obj != ".."){
				if(is_dir($dir.$obj)){
					if(!deleteDir($dir.$obj))
						return false;
				}
				elseif(is_file($dir.$obj))
				{
					if(!unlink($dir.$obj))
						return false;
				}
			}
		}
		closedir($handle);
		
		if(!@rmdir($dir))
			return false;
		return true;
	}
	return false;
}
				
	

?>

<br>
<p />
<form action="../" method="post" name="back">
	<input name="back" type="submit" value="Back">
</form>

</body></html>
