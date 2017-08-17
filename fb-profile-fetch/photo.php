<?php 

function creatdir($name){
	if (file_exists($name)){
		
		chdir($name);

	}
	else{
			mkdir($name);
				chdir($name);
			
		}

}

function downloader($access,$id,$data)
{

	$dir=$access;

	$album=$id;
	if (file_exists($dir)){
		chdir($dir);
	}else{
	creatdir($dir);
		
	}
	if (file_exists($album)) {
		echo $album .'is there';
	chdir("../");
	}else{
		creatdir($album);
	saveImages($data);
	chdir("../../");
	}
	
	// mkdir("test");
	zipfolder($access);



	$url="Zipfiles/".$access.".zip";
	echo"<a href='". $url."' download>Download Zip</a>";


}
 function saveImages($data)
{


	// var_dump($data);
	
		foreach ($data as $key) {
		# code...

		// echo $key;
	$image = file_get_contents($key);
    file_put_contents(uniqid().".jpg",$image);
	}
}


class FlxZipArchive extends ZipArchive 
{
 public function addDir($location, $name) 
 {
       $this->addEmptyDir($name);
       $this->addDirDo($location, $name);
 } 
 private function addDirDo($location, $name) 
 {
    $name .= '/';
    $location .= '/';
    $dir = opendir ($location);
    $i=0;
    while ($file = readdir($dir))
    {
        if ($file == '.' || $file == '..') continue;
        $do = (filetype( $location . $file) == 'dir') ? 'addDir' : 'addFile';
        $this->$do($location . $file, $name . $file);
    	// echo "adding dir".$i++;

    }

 } 
}
?>

<?php


function zipfolder($dir)
{
$the_folder = $dir;
$zip_file_name = 'Zipfiles/'.$dir.'.zip';
$za = new FlxZipArchive;
$res = $za->open($zip_file_name, ZipArchive::CREATE);
if($res === TRUE) 
{
    $za->addDir($the_folder, basename($the_folder));
    $za->close();

}
else{
echo 'Could not create a zip archive';
}





}

 ?>


