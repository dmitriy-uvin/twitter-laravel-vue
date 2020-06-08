<?php

declare(strict_types=1);

namespace App\Action\User;

use App\Action\SendNotificationToUserRequest;
use App\Action\User\SendNotificationToUserResponse;
use App\Entity\User;
use App\Mail\LikedTweetEmail;
use App\Repository\TweetRepository;
use App\Repository\UserRepository;
use Illuminate\Mail\Mailer;

final class SendNotificationToUserAction
{
    private $userRepository;
    private $tweetRepository;
    private $mailer;

    public function __construct(UserRepository $userRepository, TweetRepository $tweetRepository, Mailer $mailer)
    {
        $this->userRepository = $userRepository;
        $this->tweetRepository = $tweetRepository;
        $this->mailer = $mailer;
    }

    public function execute(SendNotificationToUserRequest $request)
    {
        $receiver = $this->userRepository->getById($request->getReceiverId());
        $liker = $this->userRepository->getById($request->getLikerId());
        $tweet = $this->tweetRepository->getById($request->getTweetId());

        $this->mailer->to($receiver)->send(
            new LikedTweetEmail(
                $receiver,
                $liker,
                $tweet
            )
        );

        return new SendNotificationToUserResponse($receiver);
    }
}
