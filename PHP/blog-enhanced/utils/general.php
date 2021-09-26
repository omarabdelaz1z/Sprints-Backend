<?php

function getUploadedImage($files) {
    $tokens = explode('.', $files['image']['name']);
    $extension = $tokens[count($tokens) - 1];
    array_pop($tokens);

    $fileName = implode('.', $tokens) . '_' . strtotime("now") . '.' . $extension;
    move_uploaded_file($files['image']['tmp_name'], BASE_PATH . '/assets/post-images/' . $fileName);
    return $fileName;
}

function sendMail($name, $to_email, $subject, $body) {
    $body.='<br>'.$name;
    return mail($to_email, $subject, $body);
}