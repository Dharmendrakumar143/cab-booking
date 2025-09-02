<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Home')</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css">
  <link href="{{asset('assets/css/style.css')}}" rel="stylesheet" type="text/css">
  <link href="{{asset('assets/css/custom.css')}}" rel="stylesheet" type="text/css">
 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  <style>
        /* Styling for the star rating */
        .fa {
            font-size: 18px;
            cursor: pointer;
        }
        .checked {
            color: orange;
        }
        .error {
            color: red;
            font-size: 14px;
        }
        button[disabled] {
            background-color: #cccccc;
            cursor: not-allowed;
        }
  </style>

<style>
        /* Custom style for Toastr notifications */
        .toast-info .toast-message {
            display: flex;
            align-items: center;
        }
        .toast-info .toast-message i {
            margin-right: 10px;
        }
        .toast-info .toast-message .notification-content {
            display: flex;
            flex-direction: row;
            align-items: center;
        }
    </style>

<script>
    // Enable Pusher logging - don't include this in production
    Pusher.logToConsole = true;

    if ('{{ auth()->check() }}') {
        // Initialize Pusher
        var pusher = new Pusher('98a4d638299f9ae02d19', {
            cluster: 'ap2',
            encrypted: true
        });

        // Subscribe to ride-requests channel
        var adminConfirmRideChannel = pusher.subscribe('admin-confirm-ride');
        adminConfirmRideChannel.bind('AdminConfirmRideNotificationEvent', function(data) {
            // console.log('notification innner=='+data);
            var loggedInUserId = {{ auth()->check() ? auth()->id() : 'null' }};
            if (data.user_id === loggedInUserId) {
                handleCustomerNotificationEvent(data);
            }
        });

        // Subscribe to admin-cancel-ride channel
        var adminCancelRideChannel = pusher.subscribe('admin-cancel-ride');
        adminCancelRideChannel.bind('AdminCancelRideNotificationEvent', function(data) {
            var loggedInUserId = {{ auth()->check() ? auth()->id() : 'null' }};
            if (data.user_id === loggedInUserId) {
                handleCustomerNotificationEvent(data);
            }
        });

        /**
         * Handles the notification event and updates UI
         * @param {Object} notification - The notification data received from Pusher
         */
        function handleCustomerNotificationEvent(notification) {
            console.log('notification=='+notification);

            // Display notification using Toastr
            if (notification.title) {
                toastr.info(
                    ``,
                    notification.title,
                    {
                        closeButton: true,
                        progressBar: true,
                        timeOut: 0, // Set timeOut to 0 to make it persist until closed
                        extendedTimeOut: 0, // Ensure the notification stays open
                        positionClass: 'toast-top-right',
                        enableHtml: true
                    }
                );
            } else {
                console.error('Invalid data received:', notification);
            }

            // Append the notification to the header list
            appendNotificationToHeader(notification);

            // Increment the notification count
            incrementNotificationCount();
        }

        /**
         * Function to append notification details to the header notification list
         * @param {Object} notification - The notification data to append
         */
        function appendNotificationToHeader(notification) {
            const notificationList = document.getElementById('header-notification-list');

            if (notificationList) {
                const newNotification = `
                    <li class="border-bottom py-2">
                        <a class="dropdown-item" href="{{route('my-trip')}}">
                        <span class="pe-2">
                            <img src="{{asset('assets/images/drrop1.png')}}">
                        </span>
                        ${notification.title}
                        </a>
                    </li>
                `;
                notificationList.insertAdjacentHTML('afterbegin', newNotification);
            }
        }

        /**
         * Function to increment the notification count badge
         */
        function incrementNotificationCount() {
            const notificationBadge = document.querySelector('.bell-icon .badge');

            if (notificationBadge) {
                let currentCount = parseInt(notificationBadge.textContent) || 0;
                notificationBadge.textContent = currentCount + 1;
            } else {
                // If badge doesn't exist, create and append it
                const notifyIcon = document.querySelector('.bell-icon a');
                if (notifyIcon) {
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-danger';
                    badge.textContent = '1';
                    notifyIcon.appendChild(badge);
                }
            }
        }


        document.addEventListener('DOMContentLoaded', function () {
            const notificationLinks = document.querySelectorAll('.customer-mark-as-read');

            notificationLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    const notificationId = this.getAttribute('data-id');

                    if (notificationId) {
                        // Send AJAX request to mark notification as read
                        fetch('{{ route('customer-mark-as-read') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ id: notificationId })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // this.closest('li').classList.add('read-notification');
                                window.location.href = '{{ route('my-trip') }}';
                            } else {
                                console.error(data.message);
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    }
                });
            });
        });
    }

</script>

</head>