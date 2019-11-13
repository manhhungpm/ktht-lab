<?php

namespace App\Http\Controllers;

use App\Http\Requests\Media\UploadFileRequest;
use App\Repositories\MediaRepository;

class MediaController extends Controller
{
    /**
     * @var MediaRepository
     */
    protected $mediaRepository;

    function __construct(MediaRepository $mediaRepository)
    {
        $this->middleware('auth');

        $this->mediaRepository = $mediaRepository;
    }

    public function uploadFile(UploadFileRequest $request)
    {
        return $this->mediaRepository->storageFile($request->file('file'));
    }
}