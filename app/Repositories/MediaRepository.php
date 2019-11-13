<?php

namespace App\Repositories;

use App\Models\Media;
use Carbon\Carbon;

class MediaRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Media::class;
    }

    public function storageFile($files){
        $mediaArr = [];

        if(is_array($files)){
            foreach ($files as $file){
                $mediaArr[] = $this->saveFile($file);
            }
        }else{
            $mediaArr[] = $this->saveFile($files);
        }

        return $mediaArr;
    }

    private function saveFile($file){
        $fileName = $file->getClientOriginalName();
        $pos = strrpos($fileName, '.');

        $extension = substr($fileName, $pos + 1, strlen($fileName) - $pos);
        $fileName = substr($fileName, 0, $pos);

        $now = Carbon::now();
        $uploadPath = 'uploads/' . $now->year . '/' . $now->month . '/' . $now->day;

        $path = $file->storeAs($uploadPath, md5($fileName . time()) . '.' . $extension);

        if ($path) {
            $media = new $this->model;
            $media->name = $fileName . '.' . $extension;
            $media->extension = $extension;
            $media->path = $path;
            $media->status = MEDIA_PENDING;
            $media->save();

            return $media;
        }
    }
}