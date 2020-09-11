<?php

/**
* This is a class that has helping functions that assist the process class.
* Functions Below do not interact with the database but helps out the process class by providing helpful functionality
* that is needed to be done in order to proceed to inserting/updating/selecting from the database.
*/
class Helper 
{	

    private $log;

	function __construct()
	{	
        $this->log = new iLog();

    }

    //Uploades an image to respective directory
    public function uploadImage($data = null, $uploadDir = 'upload/'){

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
    //Sanitizes incoming inputs, strips spaces, removes htmlspecialchar and removes slashes
    public function sanitize($val = null){

        $value = trim($val);
        $value = htmlspecialchars($value);
        $value = stripcslashes($value);

        return $value;
    }

}
?>