<?php
namespace App\Helpers;

use App\Models\File;
use Exception;
use Illuminate\Support\Facades\Storage;

class FileHelper{
    public static function store($request_file, $path){
        try {
            $path = $request_file->store($path,'public');
            $file = File::create([
                'path' => $path,
                'size' => Storage::size('public/'.$path),
                'type' => Storage::mimeType('public/'.$path),
            ]);
            return $file;
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage());
        }
    }
    public static function delete($file_path){
        if($file_path != null){
            Storage::disk('public')->delete($file_path);
            File::where('path', $file_path)->delete();
            return true;
        }else{
            return false;
        }
    }
}
?>
