<?php
header('Content-Type: application/json');

if(isset($_POST['title'], $_POST['description'], $_POST['author'], $_POST['page'])){

    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){

        $fileTmp = $_FILES['image']['tmp_name'];
        $fileName = time().'_'.basename($_FILES['image']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowed = ['jpg','jpeg','png','gif'];

        if(!in_array($fileExt, $allowed)){
            echo json_encode(['status'=>'error','message'=>'Invalid image type']);
            exit;
        }

        $uploadPath = 'uploads/'.$fileName;

        move_uploaded_file($fileTmp, $uploadPath);

    } else {

        echo json_encode(['status'=>'error','message'=>'Image required']);
        exit;

    }

    // Handle multiple pages
    $pages = $_POST['page'];

    if(is_array($pages)){
        $pageValue = implode(",", $pages);
    } else {
        $pageValue = $pages;
    }

    $newsFile = 'news.json';

    $news = file_exists($newsFile) ? json_decode(file_get_contents($newsFile), true) : [];

    $newPost = [

        'id'=>time(),
        'title'=>$_POST['title'],
        'description'=>$_POST['description'],
        'author'=>$_POST['author'],
        'page'=>$pageValue,
        'image'=>$uploadPath

    ];

    $news[] = $newPost;

    file_put_contents($newsFile, json_encode($news, JSON_PRETTY_PRINT));

    echo json_encode(['status'=>'success','message'=>'Post uploaded']);

} else {

    echo json_encode(['status'=>'error','message'=>'Missing data']);

}
?>