<?php

require_once "php/factories/DatabaseFactory.php";

class File{

    private $database = null;

    public function __construct(){

        $this->database = DatabaseFactory::create();
    }

    public function uploadImage($file){

        // See if there is already an error
        if(0 < $file["file"]["error"]){
            return $file["file"]["error"] . " (error)";
        }else{

            // Set up the image
            $image = $file["file"];
            $imageSizes = getimagesize($image["tmp_name"]);

            // If there are no image sizes, return the not-image error
            if(!$imageSizes){
                return "not-image";
            }

            // SIZE LIMIT HERE SOON (TBI)

            // Set a name for the image
            $username = $_SESSION["user"]->getUsername();
            $time = time();
            $fileName = "images/profile/$username-$time.jpg";

            // Move the image which is guaranteed a unique name (unless it is due to overwrite), to the profile pictures folder
            move_uploaded_file($image["tmp_name"], $fileName);

            // Now delete the old ones
            $oldAvatar = $_SESSION["user"]->getAvatar();
            $oldFullAvatar = $_SESSION["user"]->getFullAvatar();
            if(file_exists($oldAvatar)){
                unlink($oldAvatar);
            }if(file_exists($oldFullAvatar)){
                unlink($oldFullAvatar);
            }

            // Update the full avatar column
            //$this->database->updateFullAvatar($username, $fileName);

            // Return the new filename
            return $fileName;
        }
    }

    public function uploadProfilePicture($coordString, $imgSrc){

        // Target dimensions
        $tarWidth = $tarHeight = 150;

        // Split the coords in to an array (sent by a string that was created by JS)
        $coordsArray = explode(",", $coordString);

        //Set them all from the array
        $x =            $coordsArray[0];
        $y =            $coordsArray[1];
        $width =        $coordsArray[2];
        $height =       $coordsArray[3];
        $newWidth =     $coordsArray[4];
        $newHeight =    $coordsArray[5];

        // Validate the image and decide which image type to create the original resource from
        $imgDetails = getimagesize($imgSrc);
        $imgMime = $imgDetails["mime"];

        $origWidth = $imgDetails[0];
        $origHeight = $imgDetails[1];

        switch($imgMime){
            case "image/jpeg":
                $originalImage = imagecreatefromjpeg($imgSrc);
                break;
            case "image/png":
                $originalImage = imagecreatefrompng($imgSrc);
                break;
            default:
                return "no-work";
        }

        // Target image resource
        $imgTarget = imagecreatetruecolor($tarWidth, $tarHeight);
        $img = imagecreatetruecolor($newWidth, $newHeight);

        // Resize the original image to work with our coords
        imagecopyresampled($img, $originalImage, 0, 0, 0, 0,
            $newWidth, $newHeight, $origWidth, $origHeight);

        // Now copy the CROPPED image in to the TARGET resource
        imagecopyresampled(
            $imgTarget,     // Target resource
            $img,           // Target image
            0, 0,           // X / Y Coords of the target image; this will always be 0, 0 as we do not want any black nothingness
            $x, $y,         // X / Y Coords (top left) of the target area
            $tarWidth,
            $tarHeight,       // width / height of the target
            $width,
            $height         // Width / height of the source image crop
        );

        // Get the Username from the session
        $username = $_SESSION["user"]->getUsername();

        // Set up a new path for the cropped image
        $newPath = "$imgSrc-cropped.jpg";

        // Remove all other cropped profile pictures for this user
        $mask = "$username-*.jpg-cropped.jpg";
        array_map("unlink", glob("images/profile/" . $mask));

        // Create that shit!
        imagejpeg($imgTarget, $newPath);

        // Return the path
        return $imgSrc;
    }
}













