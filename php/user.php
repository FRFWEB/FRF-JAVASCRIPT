<?php
$link = mysqli_connect('localhost','root','','rest');
if(!$link){
    echo mysqli_connect_error();
}
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    //GET VALUE
    $data = file_get_contents('php://input');
    //CONVERT TO ARRAY
    $data = json_decode( $data, true );
    $getMessage = generateCrud("INSERT INTO usuarios VALUES ('','$data[name]','$data[user]','$data[pass]')",$link,'create','200');
    if($getMessage == 200){
        //CREATE JSON
        if(file_exists('../users.json')){
            $readData = generateCrud("SELECT * FROM usuarios",$link,'read','');
             while($row = mysqli_fetch_array($readData)){
                $users[] = array(
                    "id"=>$row['id_usuario'],
                    "name"=>$row['name'],
                    "user"=>$row['user'],
                    "password"=>$row['pass'],
                );
            }
            $file = fopen('../users.json','w');
            fwrite($file,json_encode($users)); 
            fclose($file);
            echo $getMessage;
        }else{
            $readData = generateCrud("SELECT * FROM usuarios",$link,'read','');
            while($row = mysqli_fetch_array($readData)){
               $users[] = array(
                   "id"=>$row['id_usuario'],
                   "name"=>$row['name'],
                   "user"=>$row['user'],
                   "password"=>$row['pass'],
               );
           }
           $file = fopen('../users.json','w');
           fwrite($file,json_encode($users)); 
           fclose($file);
            echo $getMessage;
        }
    }
}


//FUNCTIONS
function generateCrud($sql, $link,$typecrud,$message){
    if($typecrud == 'create'){
        $search = mysqli_query($link, $sql);
        if($search){
            if($result = mysqli_affected_rows($link)){
                return $message;
            }
        }else{
            return 'Message error: '.mysqli_error($link);
        }
    }
    if($typecrud == 'read'){
        $search = mysqli_query($link, $sql);
        if($search){
            if(mysqli_num_rows($search)> 0){
                return $search;
            }else{
                return 'Message error: Data not exists';
            }
        }else{
            return 'Message error: '.mysqli_error($link);
        }
    }
    if($typecrud == 'update'){
        $search = mysqli_query($link, $sql);
        if($search){
            if($result = mysqli_affected_rows($link)){
                return $message;
            }else{
                if($result == 0){
                    return 'Message: Data Update before';
                }else{
                    return 'Message error: '.mysqli_error($link);
                }
            }
        }else{
            return 'Message error: '.mysqli_error($link);
        }
    }
    if($typecrud == 'delete'){
        $search = mysqli_query($link, $sql);
        if($search){
            if($result = mysqli_affected_rows($link)){
                return $message;
            }else{
                if($result == 0){
                    return 'Message: Data Delete before';
                }else{
                    return 'Message error: '.mysqli_error($link);
                }
            }
        }else{
            return 'Message error: '.mysqli_error($link);
        }
    }
}
?>