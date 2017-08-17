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
// function imageprocess()
//  {
//  	# code...
//  }
//         foreach ( $data1['albums'] as $key ) {
          
//           echo '
//             <div class="profile-albums">
//               <h2> '.$key['name'].'</h2>
//               <hr>
//               <div class="row">
                

//                  ';
//                	$id=$key['id'];
//                   $response = $fb->get('/'.$id.'/photos/?fields=source,name,image',$token);
//                   $data2=$response->getGraphEdge();
//                   foreach ($data2 as $key) {

//                   	$imageurl[$i]=$key['source'];
//                   	$i++;
//                     // echo '<div class="col-md-4"> <img src="'.$key['source'].'">
//                     // </div>';
//                 }
//                 echo ";
//               </div>
//             </div>";
//	          }
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
	}else{
		creatdir($album);
	}

	echo "Saving images";
	saveImages($data);
	// chdir(__.DIR.__);


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

 ?>