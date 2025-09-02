<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Troy')</title>

    <!-- CDN links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&amp;family=League+Spartan:wght@100..900&amp;family=Marcellus&amp;family=News+Cycle:wght@400;700&amp;family=Noto+Sans:ital,wght@0,100..900;1,100..900&amp;family=Outfit:wght@100..900&amp;family=Playfair+Display:ital,wght@0,400..900;1,400..900&amp;family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <!-- custom css -->
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/dashboard.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/admin-custom.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    <!-- LightGallery CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightgallery/css/lightgallery.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

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

    if ('{{ auth()->guard('admin')->check() }}') {
        // Initialize Pusher
        var pusher = new Pusher('98a4d638299f9ae02d19', {
            cluster: 'ap2',
            encrypted: true
        });

        // Subscribe to ride-requests channel
        var rideRequestsChannel = pusher.subscribe('ride-requests');
        rideRequestsChannel.bind('RideRequestNotificationEvent', function(data) {
         
            var loggedInUserIsAdmin = {!! auth()->guard('admin')->user() && auth()->guard('admin')->user()->roles->first()->name === 'super-admin' ? 'true' : 'false' !!};
            if (loggedInUserIsAdmin) {
                handleNotificationEvent(data);
            }

        });

        // Subscribe to customer-ride-cancel channel
        var customerCancelRideChannel = pusher.subscribe('customer-ride-cancel');
        customerCancelRideChannel.bind('CustomerCancelRideNotificationEvent', function(data) {
            var loggedInUserId = {{ auth()->guard('admin')->check() ? auth()->guard('admin')->id() : 'null' }};
            if (data.admin_id == loggedInUserId) {
                handleNotificationEvent(data);
            }
        });

        var uploadDocumentsChannel = pusher.subscribe('upload-documents');
        uploadDocumentsChannel.bind('UploadDocumentNotificationEvent', function(data) {
            var loggedInUserRoleName = "{{ auth()->guard('admin')->check() ? auth()->guard('admin')->user()->roles->first()->name : 'null' }}";
            if (loggedInUserRoleName == "super-admin" || loggedInUserRoleName == "admin") {
                handleNotificationEvent(data);
            }
        });

        var verifyDocumentsChannel = pusher.subscribe('admin-verify-documents');
        verifyDocumentsChannel.bind('AdminVerifyDocumentNotificationsEvent', function(data) {
            var loggedInUserId = {{ auth()->guard('admin')->check() ? auth()->guard('admin')->id() : 'null' }};
            
            if (data.admin_id == loggedInUserId) {
                handleNotificationEvent(data);
            }
            
        });

        /**
         * Handles the notification event and updates UI
         * @param {Object} notification - The notification data received from Pusher
         */
        function handleNotificationEvent(notification) {
            console.log(notification);

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
         * Function to increment the notification count badge
         */
        function appendNotificationToHeader(notification) {
            const notificationList = document.getElementById('header-notification-list');

            const noNotificationMessage = document.getElementById('no-notifications-message');
            
            // Remove the "No new notifications" message if it's there
            if (noNotificationMessage) {
                noNotificationMessage.remove();
            }

            if (notificationList) {
                const newNotification = `
                    <li class="border-bottom py-2">
                        <a class="dropdown-item" href="{{route('admin-rides')}}">
                        <span class="pe-2">
                            <img src="{{asset('assets/images/drrop1.png')}}">
                        </span>
                        ${notification.title}
                        </a>
                    </li>
                `;
                notificationList.insertAdjacentHTML('afterbegin', newNotification);
            }

            // Ensure that "View all notifications" button appears if it's not there
            if (!document.querySelector('.dropdown-menu #view-all-notifications-btn')) {
                const viewAllButton = `
                    <li class="py-2">
                        <a class="dropdown-item text-center text-primary" href="{{ route('notification-list') }}" id="view-all-notifications-btn">View all notifications</a>
                    </li>
                `;
                notificationList.insertAdjacentHTML('beforeend', viewAllButton);
            }
        }

         /**
         * Function to increment the notification count badge
         */
        function incrementNotificationCount() {
            const notificationBadge = document.querySelector('.a-notify-icon .badge');

            if (notificationBadge) {
                let currentCount = parseInt(notificationBadge.textContent) || 0;
                notificationBadge.textContent = currentCount + 1;
            } else {
                // If badge doesn't exist, create and append it
                const notifyIcon = document.querySelector('.a-notify-icon a');
                if (notifyIcon) {
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-danger';
                    badge.textContent = '1';
                    notifyIcon.appendChild(badge);
                }
            }
        }


        document.addEventListener('DOMContentLoaded', function () {
            const notificationLinks = document.querySelectorAll('.mark-as-read');

            notificationLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    const notificationId = this.getAttribute('data-id');

                    if (notificationId) {
                        // Send AJAX request to mark notification as read
                        fetch('{{ route('notifications.markAsRead') }}', {
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
                                window.location.href = '{{ route('admin-rides') }}';
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