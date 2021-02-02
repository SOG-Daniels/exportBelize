<?php
    // helper.php defines functions that can be used throughout the application 
    // for example encrpt will be used to encrpt sensative data in the class.interface.php
    // by which decrypt will be used in the index.php to decrpt $_GET encrypted Data

    //Encrypts data
    function encrypt($string){ 
        
        // Use OpenSSl Encryption method 
        $iv_length = openssl_cipher_iv_length(CIPHER_TYPE); 
        
        // Use openssl_encrypt() function to encrypt the data 
        $encryption = openssl_encrypt($string, CIPHER_TYPE, CIPHER_KEY, CIPHER_OPTIONS, ENCRYPTION_IV); 

        return base64_encode($encryption);
    } 
    //Decrypts encrypted data
    function decrypt($hash){ 

        // Use openssl_decrypt() function to decrypt the data 
        $data = openssl_decrypt (base64_decode($hash), CIPHER_TYPE, CIPHER_KEY, CIPHER_OPTIONS, DECRYPTION_IV); 

        return $data;

    }
 
    //validates a social media url
    function validateURL($url, $type = null) {
	    if (!filter_var($url, FILTER_VALIDATE_URL)) {
		return false;
	    }

	    $parts = parse_url($url);

	    switch ($type) {
		case 'facebook' :
		    return $parts['host'] == 'facebook.com';

		case 'linkedin':
		    return $parts['host'] == 'linkedin.com';

		case 'twitter':
		    return $parts['host'] == 'twitter.com';

		case 'instagram':
		    return $parts['host'] == 'instagram.com';
		
		default:
			return true;
	    }
    }

    //compresses an image and uploads it to the destination specified
    function compressImage($source, $destination, $quality) {

	  $info = getimagesize($source);

	  if ($info['mime'] == 'image/jpeg'){
 	
 		$image = imagecreatefromjpeg($source);

	  }elseif ($info['mime'] == 'image/gif'){
 
	  	$image = imagecreatefromgif($source);

	  }elseif ($info['mime'] == 'image/png'){
 
		$image = imagecreatefrompng($source);

	  }else{
	 
		return false;
	  }

	  return imagejpeg($image, $destination, $quality);
	  
    }

    //Uploades an image to respective directory
    function uploadImage($data = null, $uploadDir = 'upload/'){

        if(!empty($data['files'])){

            $fileNames = array();
            $newFilePath = '';

            // Count total files
            $fileCount = count($data['files']['name']);

            // Looping all files
            for($i = 0; $i < $fileCount; $i++){

                $file = pathinfo($data['files']['name'][$i]);

                $newFilePath = $uploadDir.$file['filename'].'_'.time().'.'.$file['extension'];

                // Upload file
                if (move_uploaded_file($data['files']['tmp_name'][$i], $newFilePath)){
                    $type = explode('/', $data['files']['type'][$i]);
                    $fileNames[$i] = array(
                        'file_name' => $data['files']['name'][$i],
                        'file_path' => $newFilePath,
                        'size' => $data['files']['size'][$i],
                        'type' => $type[0]
                    );
                }else{

                    $this->log->error('uploadImages, '.$newFilePath.' was not uploaded.');
                    return false;
                }

            }

            return $fileNames;
        }else{
            $this->log->info('uploadImages $files variable is empty, no images set.');
            return false;
        }


    }


   
?>
