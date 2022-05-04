<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

function uploadImage($file, $folderName) {
    $fileNameOriginal = $file->getClientOriginalName();
    //Chuyển file vừa lấy vào public/uploads/folderName/Auth:id() có tên fileName
    $filePath = $file->move('public/uploads/' . $folderName . '/' . Auth::id(), $fileNameOriginal);
    $dataUploadTraits = [
        'file_name' => $fileNameOriginal,
        //Thay thế public/$folderName thành storage/$folderName
        'file_path' => $filePath
    ];
    return $dataUploadTraits;
}

// function storageTraitsUploadMultiple($file, $folderName) {
//     $fileNameOriginal = $file->getClientOriginalName();
//     $fileNameHash = Str::random(20) . '.' . $file->getClientOriginalExtension();
//     //Chuyển file vừa lấy vào storage/app/public/product có tên fileName
//     $filePath = $file->storeAs('public/' . $folderName . '/' . Auth::id(), $fileNameHash);
//     $dataUploadTraits = [
//         'file_name' => $fileNameOriginal,
//         //Thay thế public/$folderName thành storage/$folderName
//         'file_path' => Storage::url($filePath)
//     ];
//     return $dataUploadTraits;
// }
