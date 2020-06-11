<?php

declare(strict_types=1);

namespace App\Action\User;

use App\Action\SendNotificationToUserRequest;
use App\Action\User\SendNotificationToUserResponse;
use App\Entity\User;
use App\Mail\LikedCommentEmail;
use App\Mail\LikedTweetEmail;
use App\Repository\CommentRepository;
use App\Repository\TweetRepository;
use App\Repository\UserRepository;
use Illuminate\Mail\Mailer;

final class SendNotificationToUserAction
{
    private $userRepository;
    private $tweetRepository;
    private $commentRepository;
    private $mailer;

    public function __construct(
        UserRepository $userRepository,
        TweetRepository $tweetRepository,
        CommentRepository $commentRepository,
        Mailer $mailer
    ) {
        $this->userRepository = $userRepository;
        $this->tweetRepository = $tweetRepository;
        $this->commentRepository = $commentRepository;
        $this->mailer = $mailer;
    }

    public function execute(SendNotificationToUserRequest $request)
    {
        $receiver = $this->userRepository->getById($request->getReceiverId());
        $liker = $this->userRepository->getById($request->getLikerId());
        $type = $request->getType();

        if($type === 'tweet') {
            $tweet = $this->tweetRepository->getById($request->getLikedEntityId());
            $this->mailer->to($receiver)->send(
                new LikedTweetEmail(
                    $receiver,
                    $liker,
                    $tweet
                )
            );
        } elseif ($type === 'comment') {
            $comment = $this->commentRepository->getById($request->getLikedEntityId());
            $this->mailer->to($receiver)->send(
                new LikedCommentEmail(
                    $receiver,
                    $liker,
                    $comment
                )
            );
        }


        return new SendNotificationToUserResponse($receiver);
    }
}
