<?php 

    require_once "spyc/Spyc.php";

    if(isset($_GET["project"])) {

        $project = $_GET["project"];

        $projectYAML = Spyc::YAMLLoad("_projectdata/".$project);

        
        $dir = basename($project, ".yaml");

        if(is_dir(basename($project, ".yaml"))) {

            $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new RecursiveIteratorIterator($it,
                        RecursiveIteratorIterator::CHILD_FIRST);
            foreach($files as $file) {
                if ($file->isDir()){
                    chmod($file, 0777);
                    rmdir($file->getRealPath());
                } else {
                    chmod($file, 0777);
                    unlink($file->getRealPath());
                }
            }
            rmdir($dir);

        }
        
        $deleteImage = true;
        $YAMLfiles = array_slice(scandir("_projectdata/"), 2);
        foreach($YAMLfiles as $file) {
            if ($file !== $project){
                $YAMLdata = Spyc::YAMLLoad("_projectdata/".$file);
                if ($YAMLdata["image"] === $projectYAML["image"] && $projectYAML["image"] !== "_thumbnails/placeholder.jpg") {
                    $deleteImage = false;
                }
            }
        }

        $deleteImage ? unlink($projectYAML["image"]) : null;
        unlink("_projectdata/".$project);
    
        setcookie("deletedProject", $project, time() + (24*3600));
    
    }
    
    header('Location: /'); 

?>