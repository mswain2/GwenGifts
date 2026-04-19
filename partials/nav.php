<div class="navbar">
    <!-- Left Section: Logo & Nav Links -->
    <div class="left-section">
        <div class="nav-logo">
            <a href="index.php"><img src="images/gwynethsGiftLogo.png" alt="Logo"></a>
        </div>
        <div class="nav-links">
            <div class="nav-item"><a href="index.php">Home</a></div>
            <div class="nav-item">My Events <span class="dropdown-arrow">&#9660;</span>
                <div class="dropdown">
                    <a href="viewAllEvents.php" style="text-decoration: none;">
                    <div class="in-nav">
                        <img src="images/list-solid.svg">
                        <span>Browse Events</span>
                    </div>
                    </a>
                    <a href="viewMyUpcomingEvents.php" style="text-decoration: none;">
                    <div class="in-nav">
                        <img src="images/new-event.svg">
                        <span>My Upcoming</span>
                    </div>
                    </a>
                </div>
            </div>
            <!-- <div class="nav-item"> Groups <span>&#9660</span>
                <div class="dropdown">
                    <a href="volunteerViewGroup.php" style="text-decoration: none;">
                    <div class="in-nav">
                        <img src="images/group.svg">
                        <span>My Groups</span>
                    </div>
                    </a>
                </div>
            </div> -->
        </div>
    </div>

    <!-- Right Section: Date & Icon -->
    <div class="right-section">
        <a href="calendar.php" title="Calendar">
            <div class="icon-butt">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="#C9AB81" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 4C3 3.44772 3.44772 3 4 3H6V2C6 1.44772 6.44772 1 7 1C7.55228 1 8 1.44772 8 2V3H16V2C16 1.44772 16.4477 1 17 1C17.5523 1 18 1.44772 18 2V3H20C20.5523 3 21 3.44772 21 4V21C21 21.5523 20.5523 22 20 22H4C3.44772 22 3 21.5523 3 21V4ZM5 5V20H19V5H5ZM7 10H9V12H7V10ZM11 10H13V12H11V10ZM15 10H17V12H15V10ZM7 14H9V16H7V14ZM11 14H13V16H11V14ZM15 14H17V16H15V14Z"/>
                </svg>
            </div>
        </a>
        <a href="help.php" title="Help Center">
            <div class="icon-butt">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="#C9AB81" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"/>
                </svg>
            </div>
        </a>
        <div class="date-box"></div>
        <div class="nav-links">
            <div class="nav-item" style="outline:none;">
                <div class="icon">
                    <img src="<?= $_pfp_escaped ?>" alt="User Icon" class="profile-icon">
                    <div class="dropdown">
                        <a href="viewProfile.php" style="text-decoration: none;">
                        <div class="in-nav">
                            <img src="images/view-profile-solid.svg">
                            <span>View Profile</span>
                        </div>
                        </a>
                        <a href="editProfile.php" style="text-decoration: none;">
                        <div class="in-nav">
                            <img src="images/pencil-solid-full.svg">
                            <span>Edit Profile</span>
                        </div>
                        </a>
                        <a href="changePassword.php" style="text-decoration: none;">
                        <div class="in-nav">
                            <img src="images/change-password.svg">
                            <span>Change Password</span>
                        </div>
                        </a>
                        <a href="logout.php" style="text-decoration: none;">
                        <div class="in-nav">
                            <img src="images/logout.svg">
                            <span>Log Out</span>
                        </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>