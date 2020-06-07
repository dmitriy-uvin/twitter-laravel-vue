<?php

declare(strict_types=1);

namespace App\Action\Comment;

use App\Exceptions\CommentNotFoundException;
use App\Repository\CommentRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

final class UploadCommentImageAction
{
    private $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function execute(UploadCommentImageRequest $request): UploadCommentImageResponse
    {
        try {
            $comment = $this->commentRepository->getById($request->getId());
        } catch (ModelNotFoundException $exception) {
            throw new CommentNotFoundException();
        }

        if($comment->author_id !== Auth::id()) {
            throw new AuthorizationException();
        }

        $filePath = Storage::putFileAs(
            Config::get('filesystems.comment_images_dir'),
            $request->getImage(),
            $request->getImage()->hashName(),
            'public'
        );

        $comment->image_url = Storage::url($filePath);

        $comment = $this->commentRepository->save($comment);

        return new UploadCommentImageResponse($comment);
    }
}
