function disableSubmit(button) {
  button.disabled = true;
  button.innerHTML = '<i class="fa fa-spinner fa-spin" style="margin-right: 0.25rem" aria-hidden="true"></i>Submitting...';
  button.form.submit();
}

document.addEventListener("DOMContentLoaded", function () {
  let currentImageIndex = 0;
  const totalImages = document.querySelectorAll('.image-container img').length;

  const imageContainer = document.querySelector('.image-container');
  if (imageContainer == null) return;

  // Function to update visibility of gallery buttons
  function updateGalleryButtons() {
    // Show or hide previous button based on the current index
    if (currentImageIndex > 0) {
      document.querySelector('.btn-prev').classList.remove('hidden');
      document.querySelector('.btn-prev').classList.add('block');
    }
    else {
      document.querySelector('.btn-prev').classList.remove('block');
      document.querySelector('.btn-prev').classList.add('hidden');
    }
    // Show or hide next button based on the current index
    if (currentImageIndex < totalImages - 1) {
      document.querySelector('.btn-next').classList.remove('hidden');
      document.querySelector('.btn-next').classList.add('block');
    }
    else {
      document.querySelector('.btn-next').classList.remove('block');
      document.querySelector('.btn-next').classList.add('hidden');
    }

    // Update the visibility of images
    document.querySelectorAll('.image-container img').forEach(function (img, index) {
      img.style.display = index === currentImageIndex ? 'block' : 'none';
    });
  }

  // Function to handle previous image button click
  window.prevImage = function () {
    if (currentImageIndex > 0) {
      currentImageIndex--;
      updateGalleryButtons();
    }
  };

  // Function to handle next image button click
  window.nextImage = function () {
    if (currentImageIndex < totalImages - 1) {
      currentImageIndex++;
      updateGalleryButtons();
    }
  };

  // Update the visibility of buttons initially
  updateGalleryButtons();
});

document.addEventListener("DOMContentLoaded", function () {
  const optionsForm = document.querySelector('#options-form');
  if (optionsForm == null) return;

  let isFetchingContent = false;

  const feed = document.querySelector('#feed');
  const feedType = optionsForm.querySelector('select[name=feed-type]');
  const contentType = optionsForm.querySelector('select[name=content-type]');

  const content = document.querySelector('#content');
  let currentPage = 1;

  // Function to handle feed type change
  feedType?.addEventListener('change', function () {
    updateFeed();
  });

  // Function to handle content type change
  contentType?.addEventListener('change', function () {
    updateFeed();
  });

  // Function to load more content when scrolling
  function loadMoreContent() {
    if (!isFetchingContent && content.scrollTop + content.clientHeight >= content.scrollHeight - window.innerHeight * 0.3) {
      isFetchingContent = true;
      currentPage++;
      const url = new URL(optionsForm.action);
      const params = new URLSearchParams();
      params.set('contentType', contentType.value);
      params.set('type', feedType.value);
      params.set('page', currentPage);
      url.search = params.toString();
      sendAjaxRequest('get', url.toString(), null, function () {
        let response;
        try {
          response = JSON.parse(this.responseText);
        }
        catch (e) {
          return;
        }
        feed.innerHTML += response.html;

        addVoting(feed);

        isFetchingContent = false;
        if (response.isLastPage)
          content.removeEventListener('scroll', loadMoreContent);
      });
    }
  }

  // Function to update the feed
  function updateFeed() {
    feed.innerHTML = '';
    // Get the feed type and content type
    currentPage = 1;

    if (feedType.value === 'member') {
      option = feedType.querySelector('option[value=member]');
      optionsForm.action = '/api/feed/' + option.dataset.username;
    }
    else {
      optionsForm.action = '/api/feed';
    }

    // Construct the URL with parameters
    const url = new URL(optionsForm.action);
    const params = new URLSearchParams();
    params.set('contentType', contentType.value);
    params.set('type', feedType.value);
    params.set('page', currentPage);
    url.search = params.toString();

    // Send the AJAX request
    sendAjaxRequest('get', url.toString(), null, function () {
      let response;
      try {
        response = JSON.parse(this.responseText);
      }
      catch (e) {
        return;
      }
      feed.innerHTML = response.html;
      addVoting(feed);

      if (!response.isLastPage)
        content.addEventListener('scroll', loadMoreContent);
      if (response.html === '')
        feed.innerHTML = '<p class="text-center text-2xl font-title font-semibold text-black">Nothing to show here</p>';

    });
  }

  // Update the feed initially
  updateFeed();
});

document.addEventListener("DOMContentLoaded", function () {
  const commentSection = document.querySelector('section.comments-section');
  if (commentSection == null) return;
  const commentSelect = commentSection.querySelector('form.sort-comments select');
  const commentList = commentSection.querySelector('ul.comments');
  if (commentList == null) return;
  let isFetchingComments = false;

  const content = document.querySelector('#content');
  content.addEventListener('scroll', loadMoreComments);

  const id = commentSection.dataset.id;
  let currentPage = 0;

  function getMoreComments() {
    currentPage++;
    const baseUrl = new URL(window.location.origin)
    const url = new URL("/articles/" + id + "/comments", baseUrl);
    const params = new URLSearchParams();
    params.set('sort', commentSelect.value);
    params.set('page', currentPage);
    url.search = params.toString();
    sendAjaxRequest('get', url, null, function () {
      let response;
      try {
        response = JSON.parse(this.responseText);
      }
      catch (e) {
        return;
      }
      commentList.innerHTML += response.html;

      addVoting(commentList);

      isFetchingComments = false;
      updateEventListeners();

      if (response.isLastPage)
        content.removeEventListener('scroll', loadMoreComments);
    });
  }

  commentSelect.addEventListener('change', function () {
    currentPage = 0;
    commentList.innerHTML = '';
    getMoreComments();
    content.removeEventListener('scroll', loadMoreComments);
    content.addEventListener('scroll', loadMoreComments);
  }
  );

  function updateEventListeners() {
    addShowRepliesEventListeners();
    if (typeof username == 'undefined' || username == null) return;
    addReplyEventListeners();
    addDeleteEventListeners();
    addEditEventListeners();
  }

  function loadMoreComments() {
    if (!isFetchingComments && content.scrollTop + content.clientHeight >= content.scrollHeight - window.innerHeight * 0.3) {
      isFetchingComments = true;
      getMoreComments();
    }
  }

  getMoreComments();

  function addShowRepliesEventListeners() {
    const comments = commentSection.querySelectorAll('ul.comments>li.comment-article');
    comments.forEach(function (comment) {
      const showRepliesButton = comment.querySelector('.show-replies');
      const replies = comment.querySelector('ul.replies');
      const hideRepliesButton = comment.querySelector('.hide-replies');

      if (replies == null) return;

      function showRepliesButtonEventListener() {
        replies.classList.toggle('hidden');
        showRepliesButton.classList.toggle('hidden');
        hideRepliesButton.classList.toggle('hidden');
      }

      function hideRepliesButtonEventListener() {
        replies.classList.toggle('hidden');
        showRepliesButton.classList.toggle('hidden');
        hideRepliesButton.classList.toggle('hidden');
      }

      showRepliesButton.removeEventListener('click', showRepliesButtonEventListener);
      hideRepliesButton.removeEventListener('click', hideRepliesButtonEventListener);
      showRepliesButton.addEventListener('click', showRepliesButtonEventListener);
      hideRepliesButton.addEventListener('click', hideRepliesButtonEventListener);
    });
  }

  function addReplyEventListeners() {
    let replyForm = commentSection.querySelector('form.reply-form');
    if (replyForm == null) {
      const commentForm = commentSection.querySelector('form.comment-form');
      replyForm = commentForm.cloneNode(true);
      replyForm.classList.add('reply-form');
      replyForm.querySelector('textarea').id = 'reply-textarea';
      replyForm.querySelector('label').htmlFor = 'reply-textarea';
    }

    const replyButtons = commentSection.querySelectorAll('.comment-article button.comment-reply');
    replyButtons.forEach(function (replyButton) {
      const parent = replyButton.parentElement;
      const cancelButton = parent.querySelector('button.comment-cancel-reply');

      function replyButtonEventListener() {
        const oldCancelReplyButton = commentSection.querySelector('button.comment-cancel-reply:not(.hidden)');
        if (oldCancelReplyButton != null) {
          oldCancelReplyButton.click();
        }
        replyButton.classList.toggle('hidden');
        cancelButton.classList.toggle('hidden');
        replyForm.querySelector('textarea').value = '';
        replyForm.querySelector('input[name=reply_id]').value = replyButton.dataset.id;
        const grandparent = parent.parentElement.parentElement;
        grandparent.insertBefore(replyForm, parent.parentElement.nextSibling);
      }

      function cancelButtonEventListener() {
        replyButton.classList.toggle('hidden');
        cancelButton.classList.toggle('hidden');
        const grandparent = parent.parentElement.parentElement;
        grandparent.removeChild(replyForm);
      }

      replyButton.removeEventListener('click', replyButtonEventListener);
      cancelButton.removeEventListener('click', cancelButtonEventListener);
      replyButton.addEventListener('click', replyButtonEventListener);
      cancelButton.addEventListener('click', cancelButtonEventListener);
    });
  }

  const deleteDialog = commentSection.querySelector('.delete-comment-dialog');
  if (deleteDialog == null) return;

  function deleteDialogButtonsEventListener() {
    const cancelButton = deleteDialog.querySelector('.cancel-delete-comment-dialog');

    function cancelButtonEventListener() {
      deleteDialog.close();
    }

    cancelButton.addEventListener('click', cancelButtonEventListener);
  }

  deleteDialogButtonsEventListener();

  function addDeleteEventListeners() {
    const deleteButtons = commentSection.querySelectorAll('.comment-delete');
    deleteButtons.forEach(function (deleteButton) {

      function deleteButtonEventListener() {
        const form = deleteDialog.querySelector('form');
        form.action = deleteButton.dataset.action;
        deleteDialog.showModal();

      }

      deleteButton.removeEventListener('click', deleteButtonEventListener);
      deleteButton.addEventListener('click', deleteButtonEventListener);
    });
  }

  function addEditEventListeners() {
    let editForm = commentSection.querySelector('form.comment-edit-form');
    if (editForm == null) {
      const commentForm = commentSection.querySelector('form.comment-form');
      editForm = commentForm.cloneNode(true);
      editForm.classList.add('comment-edit-form');
      editForm.querySelector('input[name=reply_id]').remove();
      editForm.querySelector('button[type=submit]').innerHTML = 'Edit';
      const methodInput = document.createElement('input');
      methodInput.type = 'hidden';
      methodInput.name = '_method';
      methodInput.value = 'patch';
      editForm.appendChild(methodInput);
      editForm.querySelector('textarea').placeholder = 'Edit your comment';

    }

    const editButtons = commentSection.querySelectorAll('.comment-edit');
    editButtons.forEach(function (editButton) {
      const footer = editButton.parentElement;
      const cancelButton = footer.querySelector('button.comment-cancel-edit');
      const comment = footer.parentElement;
      const paragraph = comment.querySelector('p');

      function editButtonEventListener() {
        editButton.classList.toggle('hidden');
        paragraph.classList.toggle('hidden');
        cancelButton.classList.toggle('hidden');
        comment.insertBefore(editForm, footer);
        editForm.querySelector('textarea').value = paragraph.innerHTML;
        editForm.action = editButton.dataset.action;
      }

      function cancelButtonEventListener() {
        editButton.classList.toggle('hidden');
        paragraph.classList.toggle('hidden');
        cancelButton.classList.toggle('hidden');
        comment.removeChild(editForm);
      }

      editButton.removeEventListener('click', editButtonEventListener);
      cancelButton.removeEventListener('click', cancelButtonEventListener);
      editButton.addEventListener('click', editButtonEventListener);
      cancelButton.addEventListener('click', cancelButtonEventListener);
    });
  }
});

document.addEventListener("DOMContentLoaded", function () {
  let deleteArticleButton = document.querySelector('.delete-article');
  if (deleteArticleButton == null) return;
  deleteArticleButton.addEventListener('click', function () {
    let dialog = document.querySelector('.delete-article-dialog');
    dialog.showModal();
    let cancelButton = document.querySelector('.cancel-delete-article');
    cancelButton.addEventListener('click', function () {
      dialog.close();
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const follow = document.querySelectorAll('.follow');
  if (follow == null) return;

  follow.forEach(function (currentFollow) {
    const dialog = currentFollow.nextElementSibling;
    if (dialog == null && dialog.nodeName != "DIALOG") return;
    currentFollow.addEventListener('click', function () {
      dialog.showModal();
    });

    const closeButton = dialog.querySelector('#close-follow-dialog');
    closeButton.addEventListener('click', function () {
      dialog.close();
    });
  });
}
);

document.addEventListener("DOMContentLoaded", function () {

  const deleteAccountButton = document.querySelector('#delete-own-account-button');

  if (deleteAccountButton == null) return;

  const dialog = document.querySelector('#delete-own-account-dialog');

  if (dialog == null) return;

  deleteAccountButton.addEventListener('click', function () {
    dialog.showModal();
  });

  const cancelButton = dialog.querySelector('#cancel-delete-own-account-button');

  cancelButton.addEventListener('click', function () {
    dialog.close();
  });

}
);


document.addEventListener("DOMContentLoaded", function () {
  const notificationsQueue = [];

  if (typeof userID === 'undefined' ||
    typeof pusherAPIKey === 'undefined' ||
    typeof pusherCluster === 'undefined') return;

  const pusher = new Pusher(pusherAPIKey, {
    cluster: pusherCluster,
    encrypted: true,
    authEndpoint: '/broadcasting/auth',
  });

  const channel = pusher.subscribe('private-user.' + userID);
  channel.bind('notification-event', function (data) {
    notificationsQueue.push(data);
    count = parseInt(numberOfNotifications.innerHTML);
    numberOfNotifications.innerHTML = count + 1;
    if (notificationsQueue.length === 1) {
      showNotification();
    }
  });

  const body = document.querySelector('body');
  if (body == null) return;

  function buildNotificationHTML(notification) {
    const notificationDiv = document.createElement('div');
    notificationDiv.classList.add('notification');
    if (notification.path != null) {
      const notificationLink = document.createElement('a');
      notificationLink.classList.add('notification-link');
      notificationLink.href = window.location.origin + notification.path;
      notificationLink.innerHTML = notification.message;
      notificationDiv.appendChild(notificationLink);
    }
    else {
      const notificationMessage = document.createElement('p');
      notificationMessage.classList.add('notification-link');
      notificationMessage.innerHTML = notification.message;
      notificationDiv.appendChild(notificationMessage);
    }
    return notificationDiv;
  }


  function removeNotificationDiv() {
    const notificationDiv = body.querySelector('.notification');
    if (notificationDiv != null) {
      notificationDiv.remove();
    }
  }

  function showNotification() {
    removeNotificationDiv();
    if (notificationsQueue.length === 0) return;
    const nextNotification = notificationsQueue[0];
    const notificationDiv = buildNotificationHTML(nextNotification);
    body.appendChild(notificationDiv);

    notificationDiv.addEventListener('animationend', function () {
      notificationsQueue.shift();
      showNotification();
    });
  }

  const notificationToggle = document.querySelector('#notification-toggle');
  const notificationDropdown = document.querySelector('#notification-dropdown');
  if (notificationDropdown == null || notificationToggle == null) return;

  const numberOfNotifications = notificationToggle.querySelector('span.badge');

  let currentPage = 0;
  let isFetchingNotifications = false;
  let scrollFlag = true;
  let countHidden = 0;

  function loadNotifications() {
    currentPage++;
    const url = new URL(window.location.origin + '/api/notifications/' + username);
    const params = new URLSearchParams();
    params.set('page', currentPage);
    url.search = params.toString();
    sendAjaxRequest('get', url, null, function () {
      let response;
      try {
        response = JSON.parse(this.responseText);
      }
      catch (e) {
        console.log(e.message);
        return;
      }
      notificationDropdown.innerHTML += response.html;
      isFetchingNotifications = false;
      numberOfNotifications.innerHTML = response.count - countHidden;
      if (response.isLastPage) {
        scrollFlag = false;
        notificationDropdown.removeEventListener('scroll', loadMoreNotifications);
      }
      else if (scrollFlag) {
        notificationDropdown.addEventListener('scroll', loadMoreNotifications);
        scrollFlag = false;
      }
      addRemoveNotificationEventListener();
    });
  }

  function initialLoadCount() {
    const url = new URL(window.location.origin + '/api/notifications/' + username);
    sendAjaxRequest('get', url, null, function () {
      let response;
      try {
        response = JSON.parse(this.responseText);
      }
      catch (e) {
        console.log(e.message);
        return;
      }
      numberOfNotifications.innerHTML = response.count;
    });
  }

  initialLoadCount();

  notificationToggle.addEventListener('click', function () {
    notificationDropdown.parentElement.classList.toggle('hidden');
    if (!notificationDropdown.parentElement.classList.contains('hidden')) {
      scrollFlag = true;
      notificationDropdown.scrollTo(0, 0);
      currentPage = 0;
      countHidden = 0;
      isFetchingNotifications = false;
      notificationDropdown.innerHTML = '';
      loadNotifications();
    }

    const options_dropdown = document.getElementById('options-dropdown');

    if (options_dropdown == null) return;

    options_dropdown.classList.add('hidden');
    options_dropdown.classList.remove('flex');
  });

  function loadMoreNotifications() {
    if (!isFetchingNotifications && notificationDropdown.scrollTop + notificationDropdown.clientHeight >= notificationDropdown.scrollHeight - window.innerHeight) {
      isFetchingNotifications = true;
      loadNotifications();
    }
  }

  notificationDropdown.addEventListener('scroll', loadMoreNotifications);
  notificationDropdown.parentElement.parentElement.addEventListener('mouseleave', function () {
    notificationDropdown.parentElement.classList.add('hidden');
    notificationDropdown.removeEventListener('scroll', loadMoreNotifications);
    updateReadNotifications();
  }
  );

  function markNotificationAsRead(notification) {
    const url = new URL(window.location.origin + '/api/notifications/' + notification.dataset.id);
    const data = {
      '_method': 'patch',
    }
    sendAjaxRequest('post', url, data, function () {
      let response;
      try {
        response = JSON.parse(this.responseText);
      }
      catch (e) {
        console.log(e.message);
        return;
      }
    });
  }

  function updateReadNotifications() {
    const notifications = notificationDropdown.querySelectorAll('li.hidden');
    countHidden = 0;
    notifications.forEach(function (notification) {
      markNotificationAsRead(notification);
    });
  }

  function addRemoveNotificationEventListener() {
    const notifications = notificationDropdown.querySelectorAll('li.notification-item');
    notifications.forEach(function (notification) {
      const removeNotification = notification.querySelector('.remove-notification');
      let anchor = notification.querySelector('a');
      if (anchor == null) {
        anchor = notification.querySelector('p');
      }

      function anchorEventListener() {
        markNotificationAsRead(notification);
        notification.remove();
      }

      function removeNotificationEventListener() {
        notification.classList.add('hidden');
        countHidden++;
        count = parseInt(numberOfNotifications.innerHTML) - 1;
        numberOfNotifications.innerHTML = count;
        const numberOfNotificationsDropdown = notificationDropdown.querySelectorAll('li:not(.hidden)').length;
        if (count > numberOfNotificationsDropdown && numberOfNotificationsDropdown < 5) {
          loadNotifications();
        }
        if (count === 0) {
          notificationToggle.click();
        }
      }

      anchor.removeEventListener('click', anchorEventListener);
      anchor.addEventListener('click', anchorEventListener);
      removeNotification.addEventListener('click', removeNotificationEventListener);
    });
  }

  function markAllNotificationsAsRead() {
    const button = document.querySelector('#mark-all-notifications-as-read');
    if (button == null) return;

    button.addEventListener('click', function () {
      const url = new URL(window.location.origin + '/api/notifications/' + username);
      const data = {
        '_method': 'patch',
      }
      sendAjaxRequest('post', url, data, function () {
        let response;
        try {
          response = JSON.parse(this.responseText);
        }
        catch (e) {
          console.log(e.message);
          return;
        }
        numberOfNotifications.innerHTML = 0;
        notificationDropdown.innerHTML = '';
        notificationToggle.click();
      });
    }
    );
  }

  markAllNotificationsAsRead();
});

document.addEventListener("DOMContentLoaded", function () {

  const member_menu = document.getElementById('member-menu');

  if (member_menu == null) return;

  const options_dropdown = document.getElementById('options-dropdown');

  member_menu.addEventListener('click', function () {
    options_dropdown.classList.toggle('hidden');
    options_dropdown.classList.toggle('flex');

    notificationDropdown = document.getElementById('notification-dropdown');

    if (notificationDropdown == null) return;

    if (!options_dropdown.classList.contains('hidden')) {
      notificationDropdown.parentElement.classList.add('hidden');
    }
  });

  member_menu.parentElement.addEventListener('mouseleave', function () {
    options_dropdown.classList.add('hidden');
    options_dropdown.classList.remove('flex');
  });
});


document.addEventListener("DOMContentLoaded", function () {
  addVoting(document);
});

function addVoting(document) {

  vote_menus = document.querySelectorAll('.vote-menu');

  if (vote_menus == null) return;

  vote_menus.forEach(function (vote_menu) {

    const id = vote_menu.dataset.id;
    const memberId = vote_menu.dataset.memberid;

    const upvote_button = vote_menu.querySelector('.upvote-button');
    const score = vote_menu.querySelector('.score');
    const downvote_button = vote_menu.querySelector('.downvote-button');

    if (upvote_button == null || score == null || downvote_button == null) return;

    function sendVoteRequest(weight) {
      const baseUrl = new URL(window.location.origin)
      const url = new URL("/api/vote", baseUrl);
      const data = {
        contentItemId: id,
        weight: weight,
        memberId: memberId,
      };
      sendAjaxRequest('post', url, data, function () {
        let response;
        try {
          response = JSON.parse(this.responseText);
        }
        catch (e) {
          return;
        }

        upvote_button.classList.remove('text-gold');
        downvote_button.classList.remove('text-gold');
        if (response.vote === 'upvote') {
          upvote_button.classList.add('text-gold');
        }
        else if (response.vote === 'downvote') {
          downvote_button.classList.add('text-gold');
        }
        score.innerText = response.score;
      });

      const createNotificationUrl = new URL(window.location.origin + '/api/upvote_notifications');
      const createNotificationData = {
        'content_item_id': id,
        'notified_id': vote_menu.dataset.notifiedid,
      };
      sendAjaxRequest('post', createNotificationUrl, createNotificationData, function () {
        let response;
        try {
          response = JSON.parse(this.responseText);
        }
        catch (e) {
          return;
        }
      });

    }

    upvote_button.addEventListener('click', function () {
      sendVoteRequest(1);
    });

    downvote_button.addEventListener('click', function () {
      sendVoteRequest(-1);
    });
  });
}

document.addEventListener("DOMContentLoaded", function () {
  const topicSuggestionButton = document.querySelector('#topic-suggestion-button');
  if (topicSuggestionButton == null) return;

  const dialog = document.querySelector('#topic-suggestion-dialog');
  const cancelButton = dialog.querySelector('#topic-suggestion-cancel');

  topicSuggestionButton.addEventListener('click', function () {
    dialog.showModal();
  }
  );

  cancelButton.addEventListener('click', function () {
    dialog.close();
  });
}
);

document.addEventListener("DOMContentLoaded", function () {
  const content = document.querySelector('#content');
  let isFetchingItems = false;
  let currentPage = 1;

  const adminTopicsForm = document.querySelector('#admin-topics-form');
  if (adminTopicsForm == null) return;

  const topicType = adminTopicsForm.querySelector('select[name=topic-type]');
  const suggestionsType = adminTopicsForm.querySelector('select[name=suggestions-type]');

  const topicList = document.querySelector('#topic-list');
  if (topicList == null) return;

  topicType.addEventListener('change', function () {
    currentPage = 1;
    topicList.innerHTML = '';

    if (topicType.value === 'topics') {
      suggestionsType.classList.add('hidden');
      updateTopics();
    }

    else {
      suggestionsType.classList.remove('hidden');
      updateSuggestions();
    }
  }
  );

  suggestionsType.addEventListener('change', function () {
    currentPage = 1;
    topicList.innerHTML = '';
    updateSuggestions();
  });

  content.addEventListener('scroll', loadMoreItems);

  function updateTopics() {
    adminTopicsForm.action = '/topics';
    const url = new URL(adminTopicsForm.action);
    const params = new URLSearchParams();
    params.set('page', currentPage);
    url.search = params.toString();
    sendAjaxRequest('get', url.toString(), null, function () {
      let response;
      try {
        response = JSON.parse(this.responseText);
      }
      catch (e) {
        return;
      }
      topicList.innerHTML += response.html;
      isFetchingItems = false;
      if (response.isLastPage) {
        content.removeEventListener('scroll', loadMoreItems);
      }
      editTopics();
      deleteTopics();
    });
  }

  function updateSuggestions() {
    adminTopicsForm.action = '/topic_suggestions';
    const url = new URL(adminTopicsForm.action);
    const params = new URLSearchParams();
    params.set('suggestionsType', suggestionsType.value);
    params.set('page', currentPage);
    url.search = params.toString();
    sendAjaxRequest('get', url.toString(), null, function () {
      let response;
      try {
        response = JSON.parse(this.responseText);
      }
      catch (e) {
        return;
      }
      topicList.innerHTML += response.html;
      isFetchingItems = false;
      if (response.isLastPage) {
        content.removeEventListener('scroll', loadMoreItems);
      }
    });
  }

  function updateTopicList() {
    if (topicType.value === 'topics') {
      updateTopics();
    }
    else {
      updateSuggestions();
    }
  }

  function getMoreItems() {
    currentPage++;
    updateTopicList();
  }


  function loadMoreItems() {
    if (!isFetchingItems && content.scrollTop + content.clientHeight >= content.scrollHeight - window.innerHeight * 0.3) {
      isFetchingItems = true;
      getMoreItems();
    }
  }

  updateTopicList();

  function deleteTopics() {
    const delete_buttons = document.querySelectorAll('.delete-topic-button');

    delete_buttons.forEach(function (deleteTopicButton) {

      const parent = deleteTopicButton.parentElement;

      const dialog = parent.querySelector('.delete-topic-dialog');

      const cancelDeleteTopicButton = parent.querySelector('.cancel-delete-topic');

      deleteTopicButton.addEventListener('click', function () {
        dialog.showModal();
      });

      cancelDeleteTopicButton.addEventListener('click', function () {
        dialog.close();
      });
    });
  };

  function editTopics() {
    const delete_buttons = document.querySelectorAll('.edit-topic-button');

    delete_buttons.forEach(function (editTopicButton) {

      const parent = editTopicButton.parentElement;

      const dialog = parent.querySelector('.edit-topic-dialog');

      const cancelEditTopicButton = parent.querySelector('.cancel-edit-topic');

      editTopicButton.addEventListener('click', function () {
        dialog.showModal();
      });

      cancelEditTopicButton.addEventListener('click', function () {
        dialog.close();
      });
    });
  };
}
);

document.addEventListener("DOMContentLoaded", function () {
  const editProfileImage = document.querySelector('#edit-profile-image');
  if (editProfileImage == null) return;

  const input = editProfileImage.querySelector('input[type=file]');

  input.addEventListener('change', function () {
    const img = editProfileImage.querySelector('img');
    const files = input.files;

    for (let i = 0; i < files.length; i++) {
      const reader = new FileReader();

      reader.onload = function (e) {
        img.src = e.target.result;
      };

      reader.readAsDataURL(files[i]);
    }
  }
  );
}
);

document.addEventListener("DOMContentLoaded", function () {

  const createContentButton = document.querySelector('#create-content-button');

  if (createContentButton == null) return;

  const dialog = document.querySelector('#create-content-dialog');

  if (dialog == null) return;

  const topicSuggestionButtonAlt = document.querySelector('#topic-suggestion-button-alt');
  if (topicSuggestionButtonAlt == null) return;

  const topic_dialog = document.querySelector('#topic-suggestion-dialog');

  createContentButton.addEventListener('click', function () {
    dialog.showModal();
  });

  topicSuggestionButtonAlt.addEventListener('click', function () {
    dialog.close();
    topic_dialog.showModal();
  });

  const cancelButton = dialog.querySelector('#create-content-close');

  cancelButton.addEventListener('click', function () {
    dialog.close();
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const banTagButton = document.querySelector('#ban-tag-button');
  if (banTagButton == null) return;

  const banTagDialog = document.querySelector('#ban-tag-dialog');
  if (banTagDialog == null) return;

  banTagButton.addEventListener('click', function () {
    banTagDialog.showModal();

    const cancelButton = banTagDialog.querySelector('#cancel-ban-tag');
    if (cancelButton == null) return;

    cancelButton.addEventListener('click', function () {
      banTagDialog.close();
    });
  }
  );
}
);

document.addEventListener("DOMContentLoaded", function () {
  const editTagButton = document.querySelector('#edit-tag-button');
  if (editTagButton == null) return;

  const editTagDialog = document.querySelector('#edit-tag-dialog');
  if (editTagDialog == null) return;

  editTagButton.addEventListener('click', function () {
    editTagDialog.showModal();

    const cancelButton = editTagDialog.querySelector('#cancel-edit-tag');
    if (cancelButton == null) return;

    cancelButton.addEventListener('click', function () {
      editTagDialog.close();
    });
  }
  );
}
);

document.addEventListener("DOMContentLoaded", function () {
  const content = document.querySelector('#content');
  let isFetchingItems = false;
  let currentPage = 1;

  const adminTagsForm = document.querySelector('#admin-tags-form');
  if (adminTagsForm == null) return;

  const tagsType = adminTagsForm.querySelector('select[name=tagsType]');
  if (tagsType == null) return;

  const tagsList = document.querySelector('#tag-list');
  if (tagsList == null) return;

  tagsType.addEventListener('change', function () {
    currentPage = 1;
    tagsList.innerHTML = '';
    updateTags();
  }
  );

  content.addEventListener('scroll', loadMoreItems);

  function updateTags() {
    adminTagsForm.action = '/tags';
    const url = new URL(adminTagsForm.action);
    const params = new URLSearchParams();
    params.set('page', currentPage);
    params.set('tagsType', tagsType.value);
    url.search = params.toString();
    sendAjaxRequest('get', url.toString(), null, function () {
      let response;
      try {
        response = JSON.parse(this.responseText);
      }
      catch (e) {
        return;
      }
      tagsList.innerHTML += response.html;
      isFetchingItems = false;
      if (response.isLastPage) {
        content.removeEventListener('scroll', loadMoreItems);
      }
      editTags();
      banTags();
    });
  }

  function getMoreItems() {
    currentPage++;
    updateTags();
  }


  function loadMoreItems() {
    if (!isFetchingItems && content.scrollTop + content.clientHeight >= content.scrollHeight - window.innerHeight * 0.3) {
      isFetchingItems = true;
      getMoreItems();
    }
  }

  updateTags();

  function banTags() {
    const ban_buttons = document.querySelectorAll('.ban-tag-button');

    ban_buttons.forEach(function (banTagButton) {

      const parent = banTagButton.parentElement;

      const dialog = parent.querySelector('.ban-tag-dialog');

      const cancelBanTagButton = parent.querySelector('.cancel-ban-tag');

      banTagButton.addEventListener('click', function () {
        dialog.showModal();
      });

      cancelBanTagButton.addEventListener('click', function () {
        dialog.close();
      });
    });
  };

  function editTags() {
    const edit_buttons = document.querySelectorAll('.edit-tag-button');

    edit_buttons.forEach(function (editTagButton) {

      const parent = editTagButton.parentElement;

      const dialog = parent.querySelector('.edit-tag-dialog');

      const cancelEditTagButton = parent.querySelector('.cancel-edit-tag');

      editTagButton.addEventListener('click', function () {
        dialog.showModal();
      });

      cancelEditTagButton.addEventListener('click', function () {
        dialog.close();
      });
    });
  };
}
);

document.addEventListener("DOMContentLoaded", function () {
  const memberProfileItems = document.querySelector('#member-profile-items');
  const profileOptions = document.querySelector('.profile-options');
  if (memberProfileItems == null || profileOptions == null) return;

  const content = document.querySelector('#content');
  let isFetchingProfileItems = false;
  let currentPage = 0;

  function getMoreArticles() {
    currentPage++;
    const baseUrl = new URL(window.location.origin)
    const url = new URL("/api/members/" + memberProfileItems.dataset.username + "/articles", baseUrl);
    const params = new URLSearchParams();
    params.set('page', currentPage);
    url.search = params.toString();
    sendAjaxRequest('get', url, null, function () {
      let response;
      try {
        response = JSON.parse(this.responseText);
      }
      catch (e) {
        console.log(e);
        return;
      }
      memberProfileItems.innerHTML += response.html;
      isFetchingProfileItems = false;
      if (response.isLastPage)
        content.removeEventListener('scroll', loadMoreProfileItems);
    });
  }

  function loadMoreProfileItems() {
    if (!isFetchingProfileItems && content.scrollTop + content.clientHeight >= content.scrollHeight - window.innerHeight * 0.3) {
      isFetchingProfileItems = true;
      getMoreItems();
    }
  }

  function getMoreComments() {
    currentPage++;
    const baseUrl = new URL(window.location.origin)
    const url = new URL("/api/members/" + memberProfileItems.dataset.username + "/comments", baseUrl);
    const params = new URLSearchParams();
    params.set('page', currentPage);
    url.search = params.toString();
    sendAjaxRequest('get', url, null, function () {
      let response;
      try {
        response = JSON.parse(this.responseText);
      }
      catch (e) {
        console.log(e);
        return;
      }
      memberProfileItems.innerHTML += response.html;
      isFetchingProfileItems = false;
      if (response.isLastPage)
        content.removeEventListener('scroll', loadMoreProfileItems);
    });
  }

  function getMoreItems() {
    if (memberProfileItems.dataset.type === 'articles') {
      getMoreArticles();
    }
    else if (memberProfileItems.dataset.type === 'comments') {
      getMoreComments();
    }
    addVoting(memberProfileItems);
  }

  function updateDatasetType() {
    const button = profileOptions.querySelector('button:disabled');
    if (button == null) return;
    memberProfileItems.dataset.type = button.dataset.type;
  }

  updateDatasetType();

  function addButtonsEventListner() {
    const buttons = profileOptions.querySelectorAll('button');
    buttons.forEach(function (button) {
      button.addEventListener('click', function () {
        buttons.forEach(function (button) {
          button.disabled = false;
        });
        button.disabled = true;
        updateDatasetType();
        currentPage = 0;
        memberProfileItems.innerHTML = '';
        getMoreItems();
        content.removeEventListener('scroll', loadMoreProfileItems);
        content.addEventListener('scroll', loadMoreProfileItems);
      });
    });
  }

  addButtonsEventListner();

  content.addEventListener('scroll', loadMoreProfileItems);
  getMoreItems();
}
);

document.addEventListener("DOMContentLoaded", function () {
  const section = document.querySelector('#tag-articles');
  if (section == null) return;

  const content = document.querySelector('#content');
  let isFetchingArticles = false;
  let currentPage = 0;

  function getMoreArticles() {
    currentPage++;
    const baseUrl = new URL(window.location.origin)
    const url = new URL("/api/tags/" + section.dataset.tag + "/articles", baseUrl);
    const params = new URLSearchParams();
    params.set('page', currentPage);
    url.search = params.toString();
    sendAjaxRequest('get', url, null, function () {
      let response;
      try {
        response = JSON.parse(this.responseText);
      }
      catch (e) {
        console.log(e);
        return;
      }

      console.log(response.debug);
      section.innerHTML += response.html;
      isFetchingArticles = false;

      addVoting(section);
      if (response.isLastPage)
        content.removeEventListener('scroll', loadMoreArticles);
    });
  }

  function loadMoreArticles() {
    if (!isFetchingArticles && content.scrollTop + content.clientHeight >= content.scrollHeight - window.innerHeight * 0.3) {
      isFetchingArticles = true;
      getMoreArticles();
    }
  }

  content.addEventListener('scroll', loadMoreArticles);
  getMoreArticles();
}
);

document.addEventListener("DOMContentLoaded", function () {
  const appealButton = document.querySelector('#appeal-button');
  if (appealButton == null) return;

  const appealDialog = document.querySelector('#appeal-dialog');
  if (appealDialog == null) return;

  const cancelButton = appealDialog.querySelector('#cancel-appeal');
  if (cancelButton == null) return;

  appealButton.addEventListener('click', function () {
    appealDialog.showModal();
  }
  );

  cancelButton.addEventListener('click', function () {
    appealDialog.close();
  });
}
);

document.addEventListener("DOMContentLoaded", function () {
  const administrationOptions = document.querySelector('#administration-options');
  if (administrationOptions == null) return;

  function addButtonsEventListner() {
    const buttons = administrationOptions.querySelectorAll('button');
    buttons.forEach(function (button) {
      button.addEventListener('click', function () {
        buttons.forEach(function (button) {
          button.disabled = false;
        });
        button.disabled = true;
        updateDatasetType();
        updateSections();
      });
    });
  }


  function updateDatasetType() {
    const button = administrationOptions.querySelector('button:disabled');
    if (button == null) return;
    administrationOptions.dataset.type = button.dataset.type + '-list';
  }

  function updateSections() {
    const sections = document.querySelectorAll('.administration-section');
    sections.forEach(function (section) {
      section.classList.add('hidden');
    });
    const section = document.querySelector('#' + administrationOptions.dataset.type);
    if (section == null) return;
    section.classList.remove('hidden');
  }

  addButtonsEventListner();
  updateDatasetType();
  updateSections();
}
);


document.addEventListener("DOMContentLoaded", function () {
  const membersList = document.querySelector('#members-list');
  if (membersList == null) return;

  const content = document.querySelector('#content');
  const administrationMembersList = document.querySelector('#administration-members-list');

  let isFetchingMembers = false;
  let currentPage = 0;

  function getMoreMembers() {
    currentPage++;
    const baseUrl = new URL(window.location.origin)
    const url = new URL("/api/members", baseUrl);
    const params = new URLSearchParams();
    params.set('page', currentPage);
    url.search = params.toString();
    sendAjaxRequest('get', url, null, function () {
      let response;
      try {
        response = JSON.parse(this.responseText);
      }
      catch (e) {
        console.log(e);
        return;
      }
      administrationMembersList.innerHTML += response.html;
      isFetchingMembers = false;
      if (response.isLastPage)
        content.removeEventListener('scroll', loadMoreMembers);

      addPromoteMemberEventListeners(administrationMembersList);
      addBlockMemberEventListeners(administrationMembersList);
      addUnblockMemberEventListeners(administrationMembersList);
      addDeleteMemberEventListeners(administrationMembersList);
    });
  }

  function loadMoreMembers() {
    if (!isFetchingMembers && !membersList.classList.contains("hidden") && content.scrollTop + content.clientHeight >= content.scrollHeight - window.innerHeight * 0.3) {
      isFetchingMembers = true;
      getMoreMembers();
    }
  }

  content.addEventListener('scroll', loadMoreMembers);
  getMoreMembers();
}
);

document.addEventListener("DOMContentLoaded", function () {

  const profileCard = document.querySelector('#profile-card');

  if (profileCard == null) return;

  addPromoteMemberEventListeners(profileCard);
  addBlockMemberEventListeners(profileCard);
  addUnblockMemberEventListeners(profileCard);
  addDeleteMemberEventListeners(profileCard);

});

function addPromoteMemberEventListeners(document) {

  const promoteMemberButtons = document.querySelectorAll('.promote-member-button');

  if (promoteMemberButtons.length === 0) return;

  promoteMemberButtons.forEach(function (button) {
    const promoteMemberDialog = button.parentElement.querySelector('.promote-member-dialog');
    if (promoteMemberDialog == null) return;

    const cancelButton = promoteMemberDialog.querySelector('.cancel-promote-member');
    if (cancelButton == null) return;

    cancelButton.addEventListener('click', function () {
      promoteMemberDialog.close();
    });

    button.addEventListener('click', function () {
      promoteMemberDialog.showModal();
    });
  });
}

function addBlockMemberEventListeners(document) {

  let block_buttons = document.querySelectorAll('.block-member-button');

  block_buttons.forEach(function (blockMemberButton) {

    let parent = blockMemberButton.parentElement;

    let dialog = parent.querySelector('.block-member-dialog');

    let cancelBlockMemberButton = parent.querySelector('.cancel-block-member');

    blockMemberButton.addEventListener('click', function () {
      dialog.showModal();
    });

    cancelBlockMemberButton.addEventListener('click', function () {
      dialog.close();
    });
  });
}

function addUnblockMemberEventListeners(document) {

  let unblock_buttons = document.querySelectorAll('.unblock-member-button');

  unblock_buttons.forEach(function (unblockMemberButton) {

    let parent = unblockMemberButton.parentElement;

    let dialog = parent.querySelector('.unblock-member-dialog');

    let cancelUnblockMemberButton = parent.querySelector('.cancel-unblock-member');

    unblockMemberButton.addEventListener('click', function () {
      dialog.showModal();
    });

    cancelUnblockMemberButton.addEventListener('click', function () {
      dialog.close();
    });
  });
}

function addDeleteMemberEventListeners(document) {

  let delete_buttons = document.querySelectorAll('.delete-member-button');

  delete_buttons.forEach(function (deleteMemberButton) {

    let parent = deleteMemberButton.parentElement;

    let dialog = parent.querySelector('.delete-member-dialog');

    let cancelDeleteMemberButton = parent.querySelector('.cancel-delete-member');

    deleteMemberButton.addEventListener('click', function () {
      dialog.showModal();
    });

    cancelDeleteMemberButton.addEventListener('click', function () {
      dialog.close();
    });
  });
}

document.addEventListener("DOMContentLoaded", function () {
  const appealsList = document.querySelector('#appeals-list');
  if (appealsList == null) return;

  const content = document.querySelector('#content');
  const administrationAppealsList = document.querySelector('#appeal-list');
  let isFetchingAppeals = false;
  let currentPage = 0;

  function getMoreAppeals() {
    currentPage++;
    const baseUrl = new URL(window.location.origin)
    const url = new URL("/api/appeals", baseUrl);
    const params = new URLSearchParams();
    params.set('page', currentPage);
    url.search = params.toString();
    sendAjaxRequest('get', url, null, function () {
      let response;
      try {
        response = JSON.parse(this.responseText);
      }
      catch (e) {
        console.log(e);
        return;
      }
      administrationAppealsList.innerHTML += response.html;
      isFetchingAppeals = false;
      if (response.isLastPage)
        content.removeEventListener('scroll', loadMoreAppeals);
      handleAppeals(administrationAppealsList);
    });
  }

  function loadMoreAppeals() {
    if (!isFetchingAppeals && !appealsList.classList.contains("hidden") && content.scrollTop + content.clientHeight >= content.scrollHeight - window.innerHeight * 0.3) {
      isFetchingAppeals = true;
      getMoreAppeals();
    }
  }

  function handleAppeals(document) {
    const acceptAppealButtons = document.querySelectorAll('.accept-appeal-button');
    if (acceptAppealButtons == null) return;

    acceptAppealButtons.forEach(function (acceptAppealButton) {
      const dialog = acceptAppealButton.parentElement.querySelector('.accept-appeal-dialog');
      if (dialog == null) return;

      const cancelButton = dialog.querySelector('.cancel-accept-appeal');

      acceptAppealButton.addEventListener('click', function () {
        dialog.showModal();
      });

      cancelButton.addEventListener('click', function () {
        dialog.close();
      });
    });
  };

  content.addEventListener('scroll', loadMoreAppeals);
  getMoreAppeals();
}
);

document.addEventListener("DOMContentLoaded", function () {
  const proposeTopicButton = document.querySelector('#sitemap-propose-topic');
  if (proposeTopicButton == null) return;

  proposeTopicButton.addEventListener('click', function () {
    const dialog = document.querySelector('#topic-suggestion-dialog');
    if (dialog == null) return;

    dialog.showModal();

    const cancelButton = dialog.querySelector('#topic-suggestion-cancel');

    cancelButton.addEventListener('click', function () {
      dialog.close();
    });
  }
  );
}
);

document.addEventListener("DOMContentLoaded", function () {

  const snackbar = document.getElementById("snackbar");

  if (!snackbar) return;

  snackbar.classList.add('show');

  setTimeout(
    function(){ snackbar.classList.remove('show'); }, 
    3000
  );
});