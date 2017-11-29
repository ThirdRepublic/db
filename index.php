<!DOCTYPE html>
<html>
    <head>
        <title>PriCoSha</title>
        <style>
        </style>
        <link rel = "stylesheet" href = "back/header.css">
    </head>
    <body>
        <?php include "back/header.php"?>
        <div id = "main">
            <?php
            session_start();
            $error = "";
            if(isset($_SESSION["userSession"])){  
                if(isset($_SESSION["addContentError"])){
                    $error = "<div class = 'error'>".$_SESSION['addContentError']."</div> <br/>";
                    unset($_SESSION["addContentError"]);
                }   
                echo "$error Hello, $_SESSION[userSession] <br/> <br/>
                    <h1>Feed</h1>
                ";
            }
            else{
                header("Location: login.php");
            }
            if(isset($_SESSION["currentFriendGroup"])){
                unset($_SESSION["currentFriendGroup"]);
            }
            if(isset($_SESSION["contentIdSession"])){
                unset($_SESSION["contentIdSession"]);
            }
            $conn = new PDO("mysql:host=localhost;dbname=databaseproject", "root", "");
            $cmd = "SELECT DISTINCT c.id, c.timest, c.username, c.content_name, c.file_path, p.first_name, p.last_name FROM member m JOIN share s JOIN content c JOIN person p WHERE (m.group_name = s.group_name AND m.username_creator = s.username AND m.username = '$_SESSION[userSession]' AND s.id = c.id AND c.username = p.username) OR (c.public = 1 AND c.username = p.username) OR (c.username = '$_SESSION[userSession]' AND c.username = p.username) ORDER BY c.timest DESC";
            $statement = $conn->prepare($cmd);
            $statement->execute();
            $result = $statement->fetch();
            if($result){
                do{
                    echo "
                    <figure>
                        <div> $result[first_name] $result[last_name] (".$result['username'].") <br/>";
                        echo substr($result['timest'],0,strpos($result['timest'],' ')) . "<br/>" . substr($result['timest'],strpos($result['timest'],' '));
                        echo "</div>
                        <a href= 'content.php?contentId=$result[id]'> 
                            <img src='$result[file_path]' alt='$result[content_name]'>
                        </a>    
                        <figcaption>$result[content_name]</figcaption>
                    </figure> 
                    ";
                }while($result = $statement->fetch());
            }
            ?>
        </div>
    </body>
</html>