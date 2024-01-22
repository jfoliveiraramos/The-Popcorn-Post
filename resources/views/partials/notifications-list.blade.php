@forelse ($notifications as $notification)
    <li class="notification-item focus:bg-brown focus:text-white hover:bg-brown hover:text-white py-2 px-4 flex border-b border-beige border-solid gap-x-2 relative" data-id="{{ $notification->id }}">
        <div class="notification-content flex flex-col grow">
            @if ($notification->blockNotification)
            <p class="grow">
                Your account has been blocked!
            </p>
        @elseif ($notification->followNotification)
            <a href="/members/{{ $notification->followNotification->follower->username }}" class="grow">
                {{ $notification->followNotification->follower->name() }} is now following you!
            </a>
        @elseif ($notification->commentNotification)
            <a href="/articles/{{ $notification->commentNotification->comment->article->id }}" class="grow">
                {{ $notification->commentNotification->comment->author()->name() }} commented on your article!
            </a>
        @elseif ($notification->contentNotification->undefinedTopicNotification)
            <a href="/articles/{{ $notification->contentNotification->content_item->id }}" class="grow">
                Your article has been marked as undefined topic!
            </a>
        @elseif ($notification->contentNotification->upvoteNotification)
            <a href="/articles/{{ $notification->contentNotification->content_item->id }}" class="grow">
                @if ($notification->contentNotification->upvoteNotification->amount == 1)
                Your contribution has reached {{ $notification->contentNotification->upvoteNotification->amount }} upvote!
                @else
                Your contribution has reached {{ $notification->contentNotification->upvoteNotification->amount }} upvotes!
                @endif
            </a>
        @elseif ($notification->contentNotification->removalNotification)
            <p class="grow">
                One of contributions has been removed!
            </p>
        @elseif ($notification->contentNotification->editNotification)
            <a href="/articles/{{ $notification->contentNotification->content_item->id }}" class="grow">
                One of contributions has been edited!
            </a>
        @else
            Unknown Notification
        @endif
        <span class="date text-xxs sm:text-xs text-gray-400 basis-full mt-1">{{ $notification->date() }}</span>
        </div>
        <div class="notification-options">
            <i class="fa fa-times remove-notification hover:cursor-pointer" aria-hidden="true"></i>
            <span class="sr-only">Remove</span>
        </div>
    </li>
@empty
    <li class="notification-empty focus:bg-brown focus:text-white hover:bg-brown hover:text-white h-14 p-2 flex border-b-2 border-beige border-solid flex-wrap gap-x-2">
        <p class="grow m-auto text-center">
            No notifications
        </p>
    </li>
@endforelse
