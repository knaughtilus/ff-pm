<?php

require_once 'spyc/Spyc.php';

    if(isset($_COOKIE['newProject'])) {
        $cookie_type = "newProject";
        $cookie = $_COOKIE['newProject'];
        unset($_COOKIE['newProject']);
        setcookie('newProject', "", time() - 1000);
    }
    if(isset($_COOKIE['deletedProject'])) {
        $cookie_type = "deletedProject";
        $cookie = $_COOKIE['deletedProject'];
        unset($_COOKIE['deletedProject']);
        setcookie('deletedProject', "", time() - 1000);
    }
    if(isset($_COOKIE['warning'])) {
        $cookie_type = "warning";
        $cookie = $_COOKIE['warning'];
        unset($_COOKIE['warning']);
        setcookie('warning', "", time() - 1000);
    }

    $currDir = getcwd();
    $projectdataDir = $currDir."/_projectdata";
    $thumbnailsDir = "/_thumbnails";

    function getProjectCards($dir) {
        global $projectdataDir;
        $files = array_slice(scandir($dir), 2);
        unset($files[array_search(".gitkeep", $files)]);



        if(count($files) > 0) {
            foreach ($files as $file) {
                $projectRelPath = $projectdataDir."/";

                $project = Spyc::YAMLLoad($projectRelPath.$file);

                // echo var_dump($project);

                echo "<div class=\"project-item\" dialog-container>";
                echo "<button class=\"project-getinfo dialog-open\"><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"32\" height=\"32\" viewBox=\"0 0 32 32\" fill=\"#fff\"><path id=\"noun-info-2398080\" d=\"M154.813,138.848a15.963,15.963,0,0,0,0,31.927l16.037.073-.073-16.037a15.963,15.963,0,0,0-15.964-15.963Zm2.415,23.885c0,1.347,2.611,1.248,2.611,1.248v1.509h-9.515v-1.509s2.611.1,2.611-1.248v-9.882c-.616-1.541-3.074-1.846-3.074-1.874v-.856h7.367v12.612ZM155,144.207a2.421,2.421,0,1,1-2.421,2.421A2.422,2.422,0,0,1,155,144.207Z\" transform=\"translate(-138.85 -138.848)\"/></svg></button>";
                echo "<a class=\"project-link\" href=\"/".basename($file, ".yaml")."\">".$project["title"]."</a>";
                echo "<img class=\"project-image\" src=\"".$project["image"]."\">";
                echo "<dialog class=\"dialog\">";
                echo "<button class=\"dialog-backdrop\"></button>";
                echo "<div class=\"dialog-window\">";
                echo "<button class=\"dialog-close\"><span></span><span></span></button>";
                echo "<div class=\"dialog-content\">";
                echo "<h2>".$project["title"]."</h2>";

                echo "<div class=\"date\" title=\"Created on ";
                if (isset($project["date"])) {
                    echo $project["date"];
                } else {
                    echo date("m/d/Y", filemtime(basename($file, ".yaml")));
                }
                echo "\"><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"26.182\" height=\"32\" viewBox=\"0 0 26.182 32\" fill=\"#f8f8f8\">
                <path id=\"Pfad_1\" data-name=\"Pfad 1\" d=\"M22.182,32H4a4.2,4.2,0,0,1-4-4.364V4.363A4.2,4.2,0,0,1,4,0H22.182a4.2,4.2,0,0,1,4,4.363V27.636A4.2,4.2,0,0,1,22.182,32Zm-7.1-21.1a1.98,1.98,0,0,0-1.4.58,1.932,1.932,0,0,0-.557,1.182,8.292,8.292,0,0,1-2.154.292,11.966,11.966,0,0,0-1.641.152.728.728,0,0,0-.366.177.72.72,0,0,0-.213.346L6.613,21.454A.727.727,0,0,0,7.1,22.533a.715.715,0,0,0,.145.015.621.621,0,0,0,.071,0h11.6a.727.727,0,1,0,0-1.455H11.954l3.534-1.125a.731.731,0,0,0,.5-.569,10.452,10.452,0,0,0,.122-1.677,7.226,7.226,0,0,1,.242-2.085,1.816,1.816,0,0,0,1.045-.523,1.971,1.971,0,0,0,.569-1.465l1.273-.637a.727.727,0,0,0-.306-1.386h-.023a.722.722,0,0,0-.33.08l-1.216.6-.841-.818.489-.989a.727.727,0,0,0-.649-1.057h-.033a.729.729,0,0,0-.613.41l-.523,1.057c-.017,0-.034,0-.051-.006A.432.432,0,0,0,15.08,10.9ZM9.67,20.3l0,0L11.59,18.17h.052a1.052,1.052,0,0,0,.766-.319A1.086,1.086,0,0,0,11.635,16a1.085,1.085,0,0,0-1.079,1.166l-1.8,1.977L10.022,14.5c.235-.022.478-.036.735-.05a11.019,11.019,0,0,0,2.833-.438l.693.682a.527.527,0,0,0,.045.033l.046.034.341.341a1.957,1.957,0,0,0,.238.205,10,10,0,0,0-.354,2.7c-.007.258-.015.5-.032.732l-4.9,1.555Zm6.411-6.074a.436.436,0,0,1-.115-.016l-.66-.557-.045-.034-.523-.534c-.151-.149-.169-.24-.17-.3s.014-.149.148-.283a.541.541,0,0,1,.386-.159.531.531,0,0,1,.375.159l.886.852a.527.527,0,0,1,.17.371.516.516,0,0,1-.158.368A.407.407,0,0,1,16.081,14.221Z\"/>
                </svg><p>";
                if (isset($project["date"])) {
                    echo $project["date"];
                } else {
                    echo date("m/d/Y", filemtime(basename($file, ".yaml")));
                }
                echo"</p></div>";


                if(isset($project["description"])) {
                    echo $project["description"];
                }

                echo "<div class=\"button-wrapper\">";
                echo "<button class=\"btn-delete\" data-title=\"Deletes both project directory and YAML config\">
                    <span>Delete project</span> 
                    <a href=\"deleteProject.php?project=".$file."\">
                        <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"25.735\" height=\"32\" viewBox=\"0 0 25.735 32\">
                            <path id=\"noun-trash-6102770\" d=\"M189.565,68.3V66.535a.885.885,0,0,0-.88-.88H168.472a.884.884,0,0,0-.881.88V68.3Zm-8.587,6.945a.941.941,0,0,1,1.882,0v9.806a.941.941,0,0,1-1.882,0Zm-6.681,0a.941.941,0,0,1,1.882,0v9.806a.941.941,0,0,1-1.882,0Zm-.127-11.475v-.911A2.87,2.87,0,0,1,177.032,60h3.093a2.87,2.87,0,0,1,2.862,2.864v.911h5.7a2.768,2.768,0,0,1,2.762,2.762v2.709h0a.942.942,0,0,1-.941.941H189L187.85,89.214A2.962,2.962,0,0,1,184.9,92H172.261a2.962,2.962,0,0,1-2.954-2.784l-1.155-19.028h-1.5a.942.942,0,0,1-.941-.941V66.535h0a2.768,2.768,0,0,1,2.762-2.762Zm6.938,0v-.911a.987.987,0,0,0-.982-.982h-3.093a.987.987,0,0,0-.982.982v.911Zm6.016,6.413h-17.09L171.182,89.1a1.075,1.075,0,0,0,1.079,1.012h12.634a1.075,1.075,0,0,0,1.079-1.012Z\" transform=\"translate(-165.71 -59.998)\" fill=\"#fff\" fill-rule=\"evenodd\"/>
                        </svg>
                    </a>
                </button>";

                echo "<a class=\"btn-secondary\" href=\"editProject.php?editProject=".$file."\">
                    Edit project
                </a>";
                echo "</div>";

                echo "</div>";
                echo "</div>";
                echo "</dialog>";
                echo "</div>";
            }
        } else {
            echo "<div class=\"error-404\"><h2>No projects found</h2><p>You cann add projects in 2 simple steps:</p><ol><li>Save your project directory to the root.</li><li>Click \"New project\", fill out the form and click \"Add\".<br><small>If you neither choose nor upload an image the default placeholder will be set.<br>You can replace this placeholder, but you have to keep the filename + extension or change the filename throughout the PHP files.</small></li></ol></div>";
        }
    }

    function getProjectList($dir) {
        global $projectdataDir;
        $files = array_slice(scandir($dir), 2);
        unset($files[array_search(".gitkeep", $files)]);

        if(count($files) > 0) {

            foreach ($files as $file) {
                $projectRelPath = $projectdataDir."/";
    
                $project = Spyc::YAMLLoad($projectRelPath.$file);
    
                echo "<div class=\"project-item\">";
                echo "<div class=\"project-row\">";
                echo "<a class=\"project-link\" href=\"/".basename($file, ".yaml")."\"><h2>".$project["title"]."</h2></a>";
                echo "<button class=\"project-getinfo\"><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"32\" height=\"32\" viewBox=\"0 0 32 32\" fill=\"#fff\"><path id=\"noun-info-2398080\" d=\"M154.813,138.848a15.963,15.963,0,0,0,0,31.927l16.037.073-.073-16.037a15.963,15.963,0,0,0-15.964-15.963Zm2.415,23.885c0,1.347,2.611,1.248,2.611,1.248v1.509h-9.515v-1.509s2.611.1,2.611-1.248v-9.882c-.616-1.541-3.074-1.846-3.074-1.874v-.856h7.367v12.612ZM155,144.207a2.421,2.421,0,1,1-2.421,2.421A2.422,2.422,0,0,1,155,144.207Z\" transform=\"translate(-138.85 -138.848)\"/></svg></button>";
                
                echo "</div>";
                echo "<div class=\"project-content\">";
                echo "<div class=\"project-content-wrapper\">";
                echo "<div class=\"project-content-inner\">";
    
    
                echo "<div class=\"date\" title=\"Created on ";
                if (isset($project["date"])) {
                    echo $project["date"];
                } else {
                    echo date("m/d/Y", filemtime(basename($file, ".yaml")));
                }
                echo "\"><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"26.182\" height=\"32\" viewBox=\"0 0 26.182 32\" fill=\"#f8f8f8\">
                <path id=\"Pfad_1\" data-name=\"Pfad 1\" d=\"M22.182,32H4a4.2,4.2,0,0,1-4-4.364V4.363A4.2,4.2,0,0,1,4,0H22.182a4.2,4.2,0,0,1,4,4.363V27.636A4.2,4.2,0,0,1,22.182,32Zm-7.1-21.1a1.98,1.98,0,0,0-1.4.58,1.932,1.932,0,0,0-.557,1.182,8.292,8.292,0,0,1-2.154.292,11.966,11.966,0,0,0-1.641.152.728.728,0,0,0-.366.177.72.72,0,0,0-.213.346L6.613,21.454A.727.727,0,0,0,7.1,22.533a.715.715,0,0,0,.145.015.621.621,0,0,0,.071,0h11.6a.727.727,0,1,0,0-1.455H11.954l3.534-1.125a.731.731,0,0,0,.5-.569,10.452,10.452,0,0,0,.122-1.677,7.226,7.226,0,0,1,.242-2.085,1.816,1.816,0,0,0,1.045-.523,1.971,1.971,0,0,0,.569-1.465l1.273-.637a.727.727,0,0,0-.306-1.386h-.023a.722.722,0,0,0-.33.08l-1.216.6-.841-.818.489-.989a.727.727,0,0,0-.649-1.057h-.033a.729.729,0,0,0-.613.41l-.523,1.057c-.017,0-.034,0-.051-.006A.432.432,0,0,0,15.08,10.9ZM9.67,20.3l0,0L11.59,18.17h.052a1.052,1.052,0,0,0,.766-.319A1.086,1.086,0,0,0,11.635,16a1.085,1.085,0,0,0-1.079,1.166l-1.8,1.977L10.022,14.5c.235-.022.478-.036.735-.05a11.019,11.019,0,0,0,2.833-.438l.693.682a.527.527,0,0,0,.045.033l.046.034.341.341a1.957,1.957,0,0,0,.238.205,10,10,0,0,0-.354,2.7c-.007.258-.015.5-.032.732l-4.9,1.555Zm6.411-6.074a.436.436,0,0,1-.115-.016l-.66-.557-.045-.034-.523-.534c-.151-.149-.169-.24-.17-.3s.014-.149.148-.283a.541.541,0,0,1,.386-.159.531.531,0,0,1,.375.159l.886.852a.527.527,0,0,1,.17.371.516.516,0,0,1-.158.368A.407.407,0,0,1,16.081,14.221Z\"/>
                </svg><p>";
                if (isset($project["date"])) {
                    echo $project["date"];
                } else {
                    echo date("m/d/Y", filemtime(basename($file, ".yaml")));
                }
                echo"</p></div>";
    
    
                if(isset($project["description"])) {
                echo $project["description"];
                }
    
                echo "<div class=\"button-wrapper\">";
                echo "<button class=\"btn-delete\" data-title=\"Deletes both project directory and YAML config\">
                    <span>Delete project</span> 
                    <a href=\"deleteProject.php?project=".$file."\">
                        <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"25.735\" height=\"32\" viewBox=\"0 0 25.735 32\">
                            <path id=\"noun-trash-6102770\" d=\"M189.565,68.3V66.535a.885.885,0,0,0-.88-.88H168.472a.884.884,0,0,0-.881.88V68.3Zm-8.587,6.945a.941.941,0,0,1,1.882,0v9.806a.941.941,0,0,1-1.882,0Zm-6.681,0a.941.941,0,0,1,1.882,0v9.806a.941.941,0,0,1-1.882,0Zm-.127-11.475v-.911A2.87,2.87,0,0,1,177.032,60h3.093a2.87,2.87,0,0,1,2.862,2.864v.911h5.7a2.768,2.768,0,0,1,2.762,2.762v2.709h0a.942.942,0,0,1-.941.941H189L187.85,89.214A2.962,2.962,0,0,1,184.9,92H172.261a2.962,2.962,0,0,1-2.954-2.784l-1.155-19.028h-1.5a.942.942,0,0,1-.941-.941V66.535h0a2.768,2.768,0,0,1,2.762-2.762Zm6.938,0v-.911a.987.987,0,0,0-.982-.982h-3.093a.987.987,0,0,0-.982.982v.911Zm6.016,6.413h-17.09L171.182,89.1a1.075,1.075,0,0,0,1.079,1.012h12.634a1.075,1.075,0,0,0,1.079-1.012Z\" transform=\"translate(-165.71 -59.998)\" fill=\"#fff\" fill-rule=\"evenodd\"/>
                        </svg>
                    </a>
                </button>";
    
                echo "<a class=\"btn-secondary\" href=\"editProject.php?editProject=".$file."\">
                    Edit project
                </a>";
                echo "</div>";
                
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "<hr>";
    
            }
        
        } else {
            echo "<div class=\"error-404\"><h2>No projects found</h2><p>You cann add projects in 2 simple steps:</p><ol><li>Save your project directory to the root.</li><li>Click \"New project\", fill out the form and click \"Add\".<br><small>If you neither choose nor upload an image the default placeholder will be set.<br>You can replace this placeholder, but you have to keep the filename + extension or change the filename throughout the PHP files.</small></li></ol></div>";
        }
    }
    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="https://use.typekit.net/spx4xrd.css">
        <link rel="stylesheet" href="style.css">
    </head>
<body>
    <div class="body-bg"></div>
    <div class="container">
        <div class="header">
            <h1>My <span class="clr-primary">Projects</span></h1>
            <div class="views">
                <a href="editProject.php" class="btn btn-secondary ml-auto">Edit project</a>
                <a href="newProject.php" class="btn btn-primary ml-auto">New project</a>
                <div class="views-group">
                    <input type="radio" name="views" id="project-cards" value="cards" class="active" checked>
                    <label for="project-cards">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                            <path id="grid-view" d="M43.709,64A10.709,10.709,0,0,1,33,53.291V43.709A10.709,10.709,0,0,1,43.709,33h9.581A10.709,10.709,0,0,1,64,43.709v9.581A10.709,10.709,0,0,1,53.291,64ZM41.455,43.709v9.581a2.257,2.257,0,0,0,2.254,2.254h9.581a2.257,2.257,0,0,0,2.254-2.254V43.709a2.257,2.257,0,0,0-2.254-2.254H43.709A2.256,2.256,0,0,0,41.455,43.709ZM10.709,64A10.709,10.709,0,0,1,0,53.291V43.709A10.709,10.709,0,0,1,10.709,33h9.581A10.709,10.709,0,0,1,31,43.709v9.581A10.709,10.709,0,0,1,20.291,64ZM8.455,43.709v9.581a2.257,2.257,0,0,0,2.255,2.254h9.581a2.258,2.258,0,0,0,2.255-2.254V43.709a2.257,2.257,0,0,0-2.255-2.254H10.709A2.257,2.257,0,0,0,8.455,43.709ZM43.709,31A10.809,10.809,0,0,1,33,20.093V10.907A10.809,10.809,0,0,1,43.709,0h9.581A10.809,10.809,0,0,1,64,10.907v9.185A10.809,10.809,0,0,1,53.291,31ZM41.455,10.907v9.185a2.278,2.278,0,0,0,2.254,2.3h9.581a2.279,2.279,0,0,0,2.254-2.3V10.907a2.278,2.278,0,0,0-2.254-2.3H43.709A2.277,2.277,0,0,0,41.455,10.907ZM10.709,31A10.809,10.809,0,0,1,0,20.093V10.907A10.809,10.809,0,0,1,10.709,0h9.581A10.809,10.809,0,0,1,31,10.907v9.185A10.809,10.809,0,0,1,20.291,31ZM8.455,10.907v9.185a2.279,2.279,0,0,0,2.255,2.3h9.581a2.279,2.279,0,0,0,2.255-2.3V10.907a2.278,2.278,0,0,0-2.255-2.3H10.709A2.278,2.278,0,0,0,8.455,10.907Z" />
                        </svg>
                    </label>
                </div>
                <div class="views-group">
                    <input type="radio" name="views" id="project-list" value="list">
                    <label for="project-list">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 77.488 64">
                            <path id="list-view" d="M27.648,64a5.1,5.1,0,1,1,0-10.194H72.392a5.1,5.1,0,0,1,0,10.194ZM5.1,64a5.1,5.1,0,1,1,0-10.194h5.559a5.1,5.1,0,0,1,0,10.194Zm22.55-26.619a5.1,5.1,0,1,1,0-10.2H72.392a5.1,5.1,0,0,1,0,10.2Zm-22.55,0a5.1,5.1,0,1,1,0-10.2h5.559a5.1,5.1,0,0,1,0,10.2Zm22.55-27.186A5.1,5.1,0,1,1,27.648,0H72.392a5.1,5.1,0,0,1,0,10.194Zm-22.55,0A5.1,5.1,0,1,1,5.1,0h5.559a5.1,5.1,0,0,1,0,10.194Z" />
                        </svg>
                    </label>
                </div>
            </div>
        </div>

        <div class="projects">
            <div class="project-cards project-wrapper active">
                <?php echo getProjectCards($projectdataDir); ?>
            </div>
            <div class="project-list project-wrapper">
                <?php echo getProjectList($projectdataDir); ?>
            </div>
        </div>
    </div>

    <?php if(isset($cookie)): ?>

    <div class="banner-outer container <?php echo $cookie_type ?>">
        <div class="banner-inner">
            <?php if($cookie_type == "newProject") {
                echo "New project '".$cookie."' added.";
            
            } elseif($cookie_type == "deletedProject") {
                echo "The project '".basename($cookie, "yaml")."' (".$cookie.") was deleted.";
            } elseif($cookie_type == "warning") {
                echo $cookie;
            }
            ?>
        </div>
    </div>

    <?php endif ?>

    <script src="site.js"></script>
</body>
</html>
