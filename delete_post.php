<?php
header('Content-Type: application/json');

if(!isset($_GET['id'])){
    echo json_encode(["status"=>"error","message"=>"No ID provided"]);
    exit;
}

$id = $_GET['id'];
$file = 'news.json';

$news = json_decode(file_get_contents($file), true);

$newNews = [];

foreach($news as $post){
    if($post['id'] != $id){
        $newNews[] = $post;
    }
}

file_put_contents($file, json_encode($newNews, JSON_PRETTY_PRINT));

echo json_encode(["status"=>"success","message"=>"Post deleted successfully"]);
?>