<?php
    require_once "spyc/Spyc.php";

    $imgDir = "_thumbnails/";
    $YAMLdir = "_projectdata/";
    if(isset($_GET["editProject"])) {
        $project = Spyc::YAMLLoad("_projectdata/".$_GET["editProject"]);

        $projectdate = explode("/", $project["date"]);
    }

    $imgs = array_slice(scandir($imgDir), 2);
    // unset($imgs[array_search("_placeholder.jpg", $imgs)]);
    

    $YAMLfiles = array_slice(scandir($YAMLdir), 2);

    if(count($YAMLfiles) === 0) {
        setcookie("warning", "Keine Projekte verfügbar.", time() + (24*3600));
        header('Location: /');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="https://use.typekit.net/spx4xrd.css">
    <link rel="stylesheet" href="style.css">

    <title>Neues Projekt</title>
</head>
<body>
    <div class="body-bg"></div>

    <div class="container">

        <div class="header">
            <h1>Edit project</h1>
            <a href="/" class="btn btn-secondary">Go back</a>
        </div>

        <form action="editProjectProcess.php" method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-col">
                    <h2>Info</h2>
                    <div class="form-group select <?php if(isset($project)) { echo "fixed"; } ?>">
                        <select name="schluessel" id="schluessel" <?php if(isset($project)) { echo "class=\"active\""; } ?>>
                            <option value="" disabled <?php if(!isset($project)) { echo "selected"; } ?>>Key (foldername) *</option>
                            <?php 
                                foreach($YAMLfiles as $dir) {
                                    $dirname = basename($dir, ".yaml");
                                    $selected = "";
                                    if(isset($project) && $_GET["editProject"] === $dir) { $selected = "selected"; }
                                    echo "<option value=\"". $dirname ."\" ".$selected.">". $dirname ."</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group floating-label">
                        <label for="title">Title *</label>
                        <input type="text" name="title" id="title" placeholder="Titel" required <?php if(isset($project)) { echo "value=\"".$project["title"]."\""; } ?>>
                    </div>
        
                    <div class="form-group date <?php if(isset($project)) { echo "active"; } ?>">
                        <label for="date">Creation date</label>
                        <input type="date" name="date" id="date" <?php if(isset($project)) { echo "value=\"".$projectdate[2]."-".$projectdate[0]."-".$projectdate[1]."\""; } ?>> <!-- 0=month, 1=day, 2=year -->
                        <small>Uses today's date if left empty.</small>
                    </div>
        
                    <div class="form-group floating-label descr">
                        <label for="description">Description (HTML)</label>
                        <textarea name="description" id="description" placeholder="Beschreibung"><?php 
                                if(isset($project)) { 
                                    echo $project["description"]; 
                                } 
                            ?></textarea>
                    </div>
                </div>
                <div class="form-col">
                    <h2>File</h2>
                    <p>Select an existing file oder upload an image of your choice. Here the uploaded image takes priority over the selected.</p>
                    <div class="form-group select">
                        <select name="image" id="image" <?php if(isset($project)) { echo "class=\"active\""; } ?>>
                            <option value="" disabled <?php if(!isset($project)) { echo "selected"; } ?>>Select file.</option>
                            <?php 
                                foreach($imgs as $img) {
                                    $selected = "";
                                    if(isset($project) && $project["image"] === "_thumbnails/".$img) { $selected = "selected"; }
                                    echo "<option value=\"". $img ."\" ".$selected.">". $img ."</option>";    
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group file">
                        <input type="file" name="image_upload" id="image_upload" onchange="getFileData(this)">
                        <label for="image_upload">
                            <svg class="upload" xmlns="http://www.w3.org/2000/svg" width="48" height="39.73" viewBox="0 0 48 39.73">
                                <path id="noun-upload-6107741" d="M89.553,201.694a1.173,1.173,0,0,1-1.173-1.173V180.187a3.572,3.572,0,0,1,3.572-3.572H104.96a3.583,3.583,0,0,1,3.2,1.994l1.994,4.059a1.232,1.232,0,0,0,1.1.686h16.628a1.173,1.173,0,1,1,0,2.346H111.248a3.6,3.6,0,0,1-3.2-1.994l-1.994-4.059a1.22,1.22,0,0,0-1.091-.686H91.952a1.232,1.232,0,0,0-1.226,1.226v20.334a1.173,1.173,0,0,1-1.173,1.173Zm23.3-5.918a.4.4,0,0,0-.364.211l-4.381,7.589h1.507a1.173,1.173,0,0,1,1.173,1.173v4.733a.422.422,0,0,0,.422.422h3.29a.422.422,0,0,0,.422-.422V204.75a1.173,1.173,0,0,1,1.173-1.173H117.6l-4.381-7.589a.4.4,0,0,0-.369-.211Zm23.349-1.76-6.663,19.894a3.572,3.572,0,0,1-3.384,2.434H92.884a3.572,3.572,0,0,1-3.384-4.692l6.668-19.865a3.566,3.566,0,0,1,3.384-2.434h33.267a3.566,3.566,0,0,1,3.378,4.692Zm-16.24,8.95-4.692-8.152a2.768,2.768,0,0,0-4.8,0l-4.692,8.152a1.971,1.971,0,0,0,1.707,2.933h.985v3.56a2.768,2.768,0,0,0,2.745,2.792H114.5a2.774,2.774,0,0,0,2.768-2.768v-3.56h.985a1.971,1.971,0,0,0,1.707-2.933Z" transform="translate(-88.38 -176.615)"/>
                            </svg>
                            <svg class="success" xmlns="http://www.w3.org/2000/svg" width="48" height="35.98" viewBox="0 0 48 35.98">
                                <g id="noun-success-2772122" transform="translate(0.285 -149.997)">
                                    <path id="Pfad_2" data-name="Pfad 2" d="M405.8,561.8a2,2,0,0,1-1.419-.58l-4-4a2.007,2.007,0,0,1,2.838-2.838l2.579,2.6,6.576-6.6a2.007,2.007,0,0,1,2.838,2.838l-8,8A2,2,0,0,1,405.8,561.8Z" transform="translate(-384.085 -383.814)"/>
                                    <path id="Pfad_3" data-name="Pfad 3" d="M37.705,185.977H11.72A11.993,11.993,0,0,1-.274,174.484a2.725,2.725,0,0,1,0-.5A11.993,11.993,0,0,1,9.9,162.13a15.994,15.994,0,0,1,1.219-4.138c0-.22.22-.46.34-.68A13.992,13.992,0,0,1,23.113,150h.6a13.992,13.992,0,0,1,13.992,13.992v2a9.994,9.994,0,0,1,9.995,9.995,4.857,4.857,0,0,1,0,.76,9.995,9.995,0,0,1-9.994,9.235ZM23.713,153.995h-.38a9.995,9.995,0,0,0-8.355,5.157,3.992,3.992,0,0,0-.22.4,11.992,11.992,0,0,0-1.039,4.438,2,2,0,0,1-2,2,8,8,0,0,0-8,8v.34a8,8,0,0,0,8,7.656H37.706a6,6,0,0,0,4.258-1.759,6.117,6.117,0,0,0,1.739-3.838,3.284,3.284,0,0,0,0-.4,6,6,0,0,0-6-6h-2a2,2,0,0,1-2-2v-4a9.994,9.994,0,0,0-9.995-9.995Z" transform="translate(0)"/>
                                </g>
                            </svg>
                            <span class="file-name">Upload file</span>
                        </label>
                    </div>
                    <input type="submit" name="submit" value="Save" class="btn btn-primary ml-auto">
                </div>
            </div>




    
        </form>
    </div>

    <script src="form.js"></script>
</body>
</html>