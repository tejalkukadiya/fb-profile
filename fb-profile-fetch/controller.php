<?php

if (!session_id()) {
    session_start(); 


}


set_time_limit(0);
require_once __DIR__.'/vendor/autoload.php';
$fb = new Facebook\Facebook([
  'app_id' => '1622173214524006', 
  'app_secret' => '2a271598a2e13b4b966b9fd36f78630d',
  'default_graph_version' => 'v2.10',
  ]);

$helper = $fb->getRedirectLoginHelper();

if(isset($_GET['state'])){
  $helper->getPersistentDataHandler()->set('state',$_GET['state']);
}
try{
$token=$helper->getAccessToken();
}
catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

$fb->setDefaultAccesstoken($_SESSION['fb_access_token']);
// $token=$_SESSION[]

if (isset($_REQUEST['load'])) {
	albumname($fb);
	// echo "string";
}


if (isset($_REQUEST['down'])) {

	echo "<p>Processing</p>";
	foreach ($_REQUEST['albums'] as $key) {
			// var_dump($_REQUEST)	;
			$response = $fb->get('/'.$key.'/photos/?fields=source,name',$_SESSION['fb_access_token']);

					$i=0;
                  $data2=$response->getGraphEdge();
                    foreach ($data2 as $key1) {

                  	$imageurl[$i]=$key1['source'];
                  	$i++;
                    // echo '<div class="col-md-4"> <img src="'.$key['source'].'">
                    // </div>';
                }
		# code...
                // echo "Processong";
	downloader($_SESSION['fb_access_token'],$key,$imageurl);
	}


	// echo "string";
}





function albumname($fb)
{

		$i=0;
        $response = $fb->get('/me/?fields=albums');
        $data1=$response->getGraphNode();
        echo '<div class="form-inline" id="albumlist">';
        // var_dump($data1);
        foreach ( $data1['albums'] as $key ) {
          
          echo '
          <div class="input-group  checkitem">
            <div class="form-check ">
              <input type="checkbox" class="form-check-input"  name="albums[]" value= "'.$key['id'].'">
            </div>
              <h5> '.$key['name'].' </h5>
          </div>
        ';

        $albumdata[$i]['name']=$key['name'];
        $albumdata[$i]['id']=$key['id'];
        $i++;
        
      }
      echo "</div>";
}

?>
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