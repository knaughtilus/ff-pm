<?php
include('spyc/Spyc.php');
$allowedFiletypes = [
    "jpg" => "jpg", 
    "jpeg" => "jpeg", 
    "png" => "png", 
    "webp" => "webp"
];

echo var_dump($_POST)."<br><br>";
echo var_dump($_FILES)."<br><br>";


if(strlen($_FILES["image_upload"]["name"]) > 0 && !isset($_POST["image"])) {
    $targetDir = "_thumbnails/";
    $targetFile = $targetDir . basename($_FILES["image_upload"]["name"]);
    $uploadOK = 1;
    $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["image_upload"]["tmp_name"]);
        if($check !== false) {
            // echo "File is an image: " . $check["mime"] . ".";
            $uploadOK = 1;
        } else {
            echo "File is no image. (line 23) <br>";
            $uploadOK = 0;
        }
    }

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo "File already exists. (line 30) <br>";
        $alreadyExistingFile = $targetFile;
        $uploadOK = 0;
    }
    
    // Allow certain file formats
    if(!isset($allowedFiletypes[$imageFileType])) {
        echo "Only jpg, jpeg, png und webp files allowed. Filetype is: ".$imageFileType.".  (line 36) <br><br>";
        $uploadOK = 0;
    }
    
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOK == 0) {
        echo "File was not uploaded. (line 42) <br>";
    
    } else {  // if everything is ok, try to upload file
        if (move_uploaded_file($_FILES["image_upload"]["tmp_name"], $targetFile)) {
            // echo "The file ". htmlspecialchars( basename( $_FILES["image_upload"]["name"])). " was uploaded.";
        } else {
            echo "The was a problem with the file upload. (Zeile 48) <br>";
        }
    }
}

if(isset($_POST['submit'])) {
    $schluessel = $_POST['schluessel'];
    $file = "_projectdata/".$schluessel.".yaml";
    unset($_POST['submit']);
    unset($_POST['schluessel']);

    $data_for_yaml = $_POST;

    if(strlen($_POST["date"]) > 0) {
        $pieces = explode("-", $_POST["date"]);
        $data_for_yaml["date"] = $pieces[1]."/".$pieces[0]."/".$pieces[2]; //2 = year, 1 = month, 0 = day

    } else {
        $data_for_yaml["date"] = date("m/d/Y", filemtime($schluessel));
        echo $data_for_yaml["date"];
    }

    if(isset($_POST["image"])) {                                    //If file was selected
        $data_for_yaml["image"] = "_thumbnails/".$_POST["image"];

    } elseif(isset($alreadyExistingFile)) {                      // If uploaded file already existed
        $data_for_yaml["image"] = $alreadyExistingFile;
        
    } elseif(strlen($_FILES["image_upload"]["name"]) > 0) {                      // If no file selected or already existing, but uploaded
        $data_for_yaml["image"] = $targetFile;
        
    } else {                                                        // Fallback: placeholder image
        $data_for_yaml["image"] = "_thumbnails/_placeholder.jpg";

    }

    $yaml = Spyc::YAMLDump($data_for_yaml, 2, 0, true);
    file_put_contents($file, $yaml);
    setcookie("newProject", $data_for_yaml["title"], time() + (24*3600));
    header('Location: /'); 
} 

?>