<body>
    <!-- get header.php -->
    <?php require 'header.php'; ?>

    <!-- welcome message -->
    <div style="margin-top: 0px; padding: 30px 20px;">
        <h2><b>Welcome <?php echo $person->get_first_name() ?>!</b> Let's get started.</h2>
    </div>

    <!-- get toasts.php -->
    <?php require 'partials/toasts.php'; ?>

    <!-- main toolbar -->
    <div class="full-width-bar">
        <!-- My Profile -->
        <div class="content-box">
            <img src="images/gwenVol.jpg" style="filter:brightness(1) contrast(40%) blur(4px) opacity(60%);" />
            <div class="small-text">Make a difference.</div>
            <div class="large-text">My Profile</div>
            <div class="nav-buttons">
                <button class="nav-button" onclick="window.location.href='viewProfile.php'">
                    <span class="arrow"><img src="images/view-profile.svg" style="width: 40px; border-radius:5px; border-bottom-right-radius: 20px;"></span>
                    <span class="text">View</span>
                </button>
                <button class="nav-button" onclick="window.location.href='editProfile.php'">
                    <span class="arrow"><img src="images/manage-account.svg" style="width: 40px; border-radius:5px; border-bottom-right-radius: 20px;"></span>
                    <span class="text">Edit</span>
                </button>
            </div>
        </div>

        <!-- My Events -->
        <div class="content-box">
            <img src="images/gg.jpg" style="filter:brightness(1) contrast(40%) blur(4px) opacity(60%);" />
            <div class="small-text">Let's have some fun!</div>
            <div class="large-text">My Events</div>
            <div class="nav-buttons">
                <button class="nav-button" onclick="window.location.href='viewAllEvents.php'">
                    <span class="arrow"><img src="images/new-event.svg" style="width: 40px; border-radius:5px; border-bottom-right-radius: 10px;"></span>
                    <span class="text">Sign-Up</span>
                </button>
                <button class="nav-button" onclick="window.location.href='viewMyUpcomingEvents.php'">
                    <span class="arrow"><img src="images/list-solid.svg" style="width: 40px; border-radius:5px; border-bottom-right-radius: 10px;"></span>
                    <span class="text">Upcoming</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Your Dashboard -->
    <div style="margin-top: 50px; padding: 0px 80px;">
        <h2><b>Your Dashboard</b></h2>
    </div>
    <div class="full-width-bar-sub">

        <!-- calculate number of unread messages in inbox -->
        <?php
        require_once('database/dbMessages.php');
        $unreadMessageCount = get_user_unread_count($person->get_id());
        $inboxIcon = 'inbox.svg';
        if ($unreadMessageCount > 0) {
            $inboxIcon = 'inbox-unread.svg';
        }
        ?>

        <!-- Calendar -->
        <div class="content-box-test" onclick="window.location.href='calendar.php'">
            <div class="icon-overlay">
                <img style="border-radius: 5px;" src="images/view-calendar.svg" alt="Calendar Icon">
            </div>
            <div class="large-text-sub">Calendar</div>
            <div class="graph-text">See upcoming events/trainings.</div>
            <button class="arrow-button">→</button>
        </div>

        <!-- Documents -->
        <div class="content-box-test" onclick="window.location.href='boardDocuments.php'">
            <div class="icon-overlay">
                <img style="border-radius: 5px;" src="images/file-regular.svg" alt="Documents Icon">
            </div>
            <div class="large-text-sub">Documents</div>
            <div class="graph-text">Access organization documents.</div>
            <button class="arrow-button">→</button>
        </div>

        <!-- Documentation Upload -->
        <!-- <div class="content-box-test" onclick="window.location.href='upload_encrypted_image.php'">
            <div class="icon-overlay">
                <img style="border-radius: 5px;" src="images/file-regular.svg" alt="Upload Icon">
            </div>
            <div class="large-text-sub">Documentation Upload</div>
            <div class="graph-text">Upload an ID for verification.</div>
            <button class="arrow-button">→</button>
        </div> -->

        <!-- Suggestions -->
        <div class="content-box-test" onclick="window.location.href='createSuggestion.php'">
            <div class="icon-overlay">
                <img style="border-radius: 5px;" src="images/clipboard-regular.svg" alt="Suggestions Icon">
            </div>
            <div class="large-text-sub">Suggestions</div>
            <div class="graph-text">Suggest opportunities for charity events.</div>
            <button class="arrow-button">→</button>
        </div>

        <!-- Inbox -->
        <div class="content-box-test" onclick="window.location.href='inbox.php'">
            <div class="icon-overlay">
                <img style="border-radius: 5px;" src="images/<?php echo $inboxIcon ?>" alt="Notification Icon">
            </div>
            <div class="large-text-sub">
                Inbox<?php if ($unreadMessageCount > 0) { echo ' (' . $unreadMessageCount . ')'; } ?>
            </div>
            <div class="graph-text">Stay up to date.</div>
            <button class="arrow-button">→</button>
        </div>

        <!-- Discussions -->
        <div class="content-box-test" onclick="window.location.href='discussionMain.php'">
            <div class="icon-overlay">
                <img style="border-radius: 5px;" src="images/group.svg" alt="Discussions Icon">
            </div>
            <div class="large-text-sub">Discussions</div>
            <div class="graph-text">View discussions.</div>
            <button class="arrow-button">→</button>
        </div>

        <!-- Training Materials -->
        <div class="content-box-test" onclick="window.location.href='myTrainingMaterials.php'">
            <div class="icon-overlay">
                <img style="border-radius: 5px;" src="images/file-regular.svg" alt="Training Materials Icon">
            </div>
            <div class="large-text-sub">My Training Materials</div>
            <div class="graph-text">Access files for your events.</div>
            <button class="arrow-button">→</button>
        </div>

    </div>

    <!-- get footer -->
    <?php require 'partials/footer.php'; ?>

</body>