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
        <!-- user management button -->
        <div class="content-box">
            <img src="images/cpr.jpg" style="filter:brightness(2) contrast(40%) blur(4px) opacity(60%);">

            <div class="small-text">Make a difference.</div>
            <div class="large-text">User Management</div>

            <button class="circle-arrow-button" onclick="window.location.href='volunteerManagement.php'">
                <span class="button-text">Go</span>
                <div class="circle">&gt;</div>
            </button>
        </div>

        <!-- event management button -->
        <div class="content-box">
            <img src="images/momprom.jpg" style="filter:brightness(1) contrast(40%) blur(4px) opacity(60%);">
            <div class="small-text">Let's have some fun!</div>
            <div class="large-text">Event Management</div>
            <button class="circle-arrow-button" onclick="window.location.href='eventManagement.php'">
                <span class="button-text"><?php
                                            require_once('database/dbEvents.php');
                                            require_once('database/dbPersons.php');
                                            require_once('database/dbApplications.php');
                                            $pendingsignups = all_pending_names();
                                            if (sizeof($pendingsignups) > 0) {
                                                echo '<span class="colored-box">' . sizeof($pendingsignups) . '</span>';
                                            }
                                            ?> Sign-Ups </span>
                <div class="circle">&gt;</div>
            </button>
        </div>
    </div>
        
    <div style="margin-top: 50px; padding: 0px 80px;">
        <h2><b>Admin Dashboard</b></h2>
    </div>

    <div class="full-width-bar-sub">

        <!-- number of inbox messages -->
        <?php
        require_once('database/dbMessages.php');
        $unreadMessageCount = 0;
        $inboxIcon = 'inbox.svg';
        if (isset($person)) {
            $unreadMessageCount = get_user_unread_count($person->get_id());
            if ($unreadMessageCount > 0) {
                $inboxIcon = 'inbox-unread.svg';
            }
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

        <!-- Manage Documents -->
        <!-- <div class="content-box-test" onclick="window.location.href='view_encrypted_gallery.php'">
            <div class="icon-overlay">
                <img style="border-radius: 5px;" src="images/file-regular.svg" alt="Document Icon">
            </div>

            <div class="large-text-sub">View Pending IDs </div>
            <div class="graph-text">View pending and arbitrate user submitted IDs.</div>
            <button class="arrow-button">→</button>
        </div> -->

        <!-- System Notifications -->
        <div class="content-box-test" onclick="window.location.href='inbox.php'">
            <div class="icon-overlay">
                <img style="border-radius: 5px;" src="images/<?php echo $inboxIcon ?>" alt="Notification Icon">
            </div>

            <div class="large-text-sub">
                System Notifications<?php
                                    if ($unreadMessageCount > 0) {
                                        echo ' (' . $unreadMessageCount . ')';
                                    }
                                    ?>
            </div>
            <div class="graph-text">Stay up to date.</div>
            <button class="arrow-button">→</button>
        </div>

        <!-- Generate Report -->
        <div class="content-box-test" onclick="window.location.href='generateReport.php'">
            <div class="icon-overlay">
                <img style="border-radius: 5px;" src="images/create-report.svg" alt="Report Icon">
            </div>

            <div class="large-text-sub">Generate Report</div>
            <div class="graph-text">From this quarter or annual.</div>
            <button class="arrow-button">→</button>
        </div>

        <!-- Create Email -->
        <div class="content-box-test" onclick="window.location.href='createEmail.php'">
            <div class="icon-overlay">
                <img style="border-radius: 5px;" src="images/inbox.svg" alt="Email Icon">
            </div>

            <div class="large-text-sub">Create Email</div>
            <div class="graph-text">Send new messages to volunteers.</div>
            <button class="arrow-button">→</button>
        </div>

        <!-- View Drafts -->
        <div class="content-box-test" onclick="window.location.href='viewDrafts.php'">
            <div class="icon-overlay">
                <img style="border-radius: 5px;" src="images/search.svg" alt="Drafts Icon">
            </div>

            <div class="large-text-sub">View Drafts</div>
            <div class="graph-text">Check saved email drafts.</div>
            <button class="arrow-button">→</button>
        </div>

        <!-- Generate Email List -->
        <div class="content-box-test" onclick="window.location.href='generateEmailList.php'">
            <div class="icon-overlay">
                <img style="border-radius: 5px;" src="images/send.png" alt="Email List Icon">
            </div>

            <div class="large-text-sub">Generate Email List</div>
            <div class="graph-text">Volunteer Emails</div>
            <button class="arrow-button">→</button>
        </div>

        <!-- User Suggestions -->
        <div class="content-box-test" onclick="window.location.href='viewSuggestions.php'">
            <div class="icon-overlay">
                <img style="border-radius: 5px;" src="images/clipboard-regular.svg" alt="Discussions Icon">
            </div>

            <div class="large-text-sub">User Suggestions</div>
            <div class="graph-text">View user submitted suggestions.</div>
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

        <!-- Add Board Meeting -->
        <div class="content-box-test" onclick="window.location.href='addBoardMeeting.php'">
            <div class="icon-overlay">
                <img style="border-radius: 5px;" src="images/search.svg" alt="Drafts Icon">
            </div>

            <div class="large-text-sub">Add Board Meeting</div>
            <div class="graph-text">Schedule a Board Meeting.</div>
            <button class="arrow-button">→</button>
        </div>

        <!-- Documents -->
        <div class="content-box-test" onclick="window.location.href='boardDocuments.php'">
            <div class="icon-overlay">
                <img style="border-radius: 5px;" src="images/file-regular.svg" alt="Drafts Icon">
            </div>

            <div class="large-text-sub">Documents</div>
            <div class="graph-text">Manage files.</div>
            <button class="arrow-button">→</button>
        </div>

        <!-- Training Materials -->
        <div class="content-box-test" onclick="window.location.href='myTrainingMaterials.php'">
            <div class="icon-overlay">
                <img style="border-radius: 5px;" src="images/file-regular.svg" alt="Training Materials Icon">
            </div>

            <div class="large-text-sub">Training Materials</div>
            <div class="graph-text">Access files for your events.</div>
            <button class="arrow-button">→</button>
        </div>


    </div>

    <?php require 'partials/footer.php'; ?>

</body>