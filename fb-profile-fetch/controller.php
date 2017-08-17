<?php

if (!session_id()) {
    session_start(); 


}


set_time_limit(0);
require_once __DIR__.'/vendor/autoload.php';
require_once 'photo.php';
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
$access=$_SESSION['fb_access_token'];
	echo "Processing";


    foreach ($_REQUEST['albums'] as $key) {
            downloadAlbum($fb,$access,$key);

    }
		# code...

	//downloader($token,$key,$imageurl);
	}


	// echo "string";
// }

function downloadAlbum($fb,$access,$id){
  $response=$fb->get('/'.$id.'/photos?fields=source,name');
  $graphObject = $response->getGraphEdge();

          $i=0;
          $fbPhotoObj = json_decode($graphObject, true);
          foreach ($fbPhotoObj as $k) {
           
            $imageurl= $k['source'];
            // echo '<img src="'.$imageurl.'"><br>';
            $data1[$i]=$imageurl;
            // saveImages($imageurl);}
            $i++;
          }
            // var_dump($data1);
            downloader($access,$id,$data1);
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





