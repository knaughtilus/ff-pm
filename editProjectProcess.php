<?php
include('spyc/Spyc.php');
$allowedFiletypes = [
    "jpg" => "jpg", 
    "jpeg" => "jpeg", 
    "png" => "png", 
    "webp" => "webp"
];

$yamlName = $_POST["schluessel"];
$yamlFilename = $yamlName.".yaml";
$yamlDir = "_projectdata/";
$yamlPath = $yamlDir.$yamlFilename;
$currYaml = Spyc::YAMLLoad($yamlPath);

echo "<h4>currYaml</h4>"; echo var_dump($currYaml)."<br><br><br><br>";

echo "<h4>POST</h4>";echo var_dump($_POST)."<br><br><br><br>";
echo "<h4>Files</h4>";echo var_dump($_FILES)."<br><br><br><br>";


if(strlen($_FILES["image_upload"]["name"]) > 0) {
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

if(isset($_POST["submit"])) {
    $newfile = $yamlPath;
    unset($_POST['submit']);
    unset($_POST['schluessel']);

    $data_for_yaml = $currYaml;
    $deleteImage = true;

    if(!isset($targetFile) && "_thumbnails/".$_POST["image"] === $data_for_yaml["image"]) {      // if post[image] equals new image do nothing                 // Wenn Bild nicht verändert wurde
        // $data_for_yaml["image"] = "test";  


    } elseif(isset($targetFile)) {                             //If image uploaded
        $oldImage = $data_for_yaml["image"];            
        $data_for_yaml["image"] = $targetFile;
        $YAMLfiles = array_slice(scandir("_projectdata/"), 2);
        foreach($YAMLfiles as $file) {                          // If old image still referenced
            if ($file !== $yamlFilename){
                $YAMLdata = Spyc::YAMLLoad("_projectdata/".$file);
                if ($YAMLdata["image"] === $oldImage || $oldImage === "_thumbnails/_placeholder.jpg") {
                    $deleteImage = false;                      // don't delete
                    echo "\$deleteImage = false; zeile 79";
                }
            }
        }

        $deleteImage ? unlink($currYaml["image"]) : null;

    } elseif(!isset($targetFile)) {                             // If new image selected
        $oldImage = $data_for_yaml["image"];
        $data_for_yaml["image"] = "_thumbnails/".$_POST["image"];
        $YAMLfiles = array_slice(scandir("_projectdata/"), 2);
        foreach($YAMLfiles as $file) {                          // If old image still referenced
            if ($file !== $yamlFilename){
                // echo $file." - ".$yamlFilename."<br>";
                echo "<strong>".$file."</strong><br>";
                echo $YAMLdata["image"]."<br>";
                echo $oldImage."<br><br><br>";
                $YAMLdata = Spyc::YAMLLoad("_projectdata/".$file);
                if ($YAMLdata["image"] === $oldImage || $oldImage === "_thumbnails/_placeholder.jpg") {
                    $deleteImage = false;                      // don't delete
                    echo "\$deleteImage = false; zeile 94; datei: ".$file."<br>";
                }
            }
        }

        $deleteImage ? unlink($currYaml["image"]) : null;

    } else {                                                        // Fallback: Platzhalter Bild
        $data_for_yaml["image"] = "_thumbnails/_placeholder.jpg";
    }

    if ($data_for_yaml["title"] !== $_POST["title"]) {              // if new title doesnt match old
        $data_for_yaml["title"]  =  $_POST["title"];                // set new title
    }

    if(strlen($_POST["date"]) > 0) {
        $pieces = explode("-", $_POST["date"]);
        $data_for_yaml["date"] = $pieces[1]."/".$pieces[0]."/".$pieces[2]; //2 = year, 1 = month, 0 = day

    } else {
        $data_for_yaml["date"] = date("m/d/Y", filemtime(basename($yamlName, ".yaml")));
    }

    if (isset($_POST["description"]) && $data_for_yaml["description"] !== $_POST["description"]) {      // if description is set and new doesnt match old
        $data_for_yaml["description"]  =  $_POST["description"];                                        // set new description
    }

    echo "<h4>newYaml (".$newfile.")</h4>"; echo var_dump($data_for_yaml)."<br><br><br><br>";

    $yaml = Spyc::YAMLDump($data_for_yaml, 2, 0, true);

    $formOK = true;
    if(isset($uploadOK) && $uploadOK === 0) {
        $formOK = false;
    }

    if($formOK) {

        if($data_for_yaml === $currYaml) {
            setcookie("warning", "There were no new changes detected.", time() + (24*3600));
            
        } else {
            file_put_contents($newfile, $yaml);
            setcookie("warning", "Project '".$yamlFilename."' was edited.", time() + (24*3600));

        }

        header('Location: /'); 
    }
}

?>