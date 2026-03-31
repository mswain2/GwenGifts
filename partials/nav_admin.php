<div class="navbar">

    <!-- Left Section: Logo & Nav Links -->

    <div class="left-section">
        <div class="logo-container">
            <a href="index.php"><img src="images/cropped-logo.png" alt="Logo"></a>
        </div>
            <!--<a href="viewCheckInOut.php" style="color: white; text-decoration: none;"><div class="date-box">Check In/Out</div></a>-->
        <div class="nav-links">
            <div class="nav-item"><a href="index.php">Home</a></div>
            <div class="nav-item">Events <span>&#9660</span>
                <div class="dropdown">
                    <a href="addEvent.php" style="text-decoration: none;">
                    <div class="in-nav">
                        <img src="images/plus-solid.svg">
                        <span>Create Event</span>
                    </div>
                    </a>
                    <a href="viewAllEvents.php" style="text-decoration: none;">
                    <div class="in-nav">
                        <img src="images/list-solid.svg">
                        <span>Browse Events</span>
                    </div>
                    </a>
                    <a href="editHours.php" style="text-decoration: none;">
                    <div class="in-nav">
                        <img src="images/clock-regular.svg">
                        <span>Change Event Hours</span>
                    </div>
                    </a>
                    <a href="viewPendingApps.php" style="text-decoration: none;">
                    <div class="in-nav">
                        <img src="images/users-solid.svg">
                        <span>Pending Applications</span>
                    </div>
                    </a>
                    <a href="adminViewingEvents.php" style="text-decoration: none;">
                    <div class="in-nav">
                        <img src="images/pencil-solid-full.svg">
                        <span>Edit Event</span>
                    </div>
                    </a>
                </div>
            </div>
            <!-- <div class="nav-item"> Groups <span>&#9660</span>
                <div class="dropdown">
                    <a href="createGroup.php" style="text-decoration: none;">
                    <div class="in-nav">
                        <img src="images/creategroup.svg">
                        <span>Create Group</span>
                    </div>
                    </a>

                    <a href="showGroups.php" style="text-decoration: none;">
                    <div class="in-nav">
                        <img src="images/group.svg">
                        <span>View Groups</span>
                    </div>
                    </a>

                    <a href="noShows.php" style="text-decoration: none;">
                    <div class="in-nav">
                        <img src="images/group.svg">
                        <span>No Shows</span>
                    </div>
                    </a>
                </div>
            </div> -->
        </div>
    </div>

    <!-- Right Section: Date & Icon -->
    
    <div class="right-section">
        <a href="calendar.php">
            <div class="icon-butt">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="var(--main-color)" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 4C3 3.44772 3.44772 3 4 3H6V2C6 1.44772 6.44772 1 7 1C7.55228 1 8 1.44772 8 2V3H16V2C16 1.44772 16.4477 1 17 1C17.5523 1 18 1.44772 18 2V3H20C20.5523 3 21 3.44772 21 4V21C21 21.5523 20.5523 22 20 22H4C3.44772 22 3 21.5523 3 21V4ZM5 5V20H19V5H5ZM7 10H9V12H7V10ZM11 10H13V12H11V10ZM15 10H17V12H15V10ZM7 14H9V16H7V14ZM11 14H13V16H11V14ZM15 14H17V16H15V14Z"/>
                </svg>
            </div>
        </a>
        <div class="date-box"></div>
        <div class="nav-links">
            <div class="nav-item">
                <div class="icon">
                    <img src="images/usaicon.png" alt="User Icon" class="icon-img in-nav-img">
                    <div class="dropdown">
                        <a href="viewProfile.php" style="text-decoration: none;"><div>View Profile</div></a>
                        <a href="editProfile.php" style="text-decoration: none;"><div>Edit Profile</div></a>
                        <a href="changePassword.php" style="text-decoration: none;"><div>Change Password</div></a>
                        <a href="logout.php" style="text-decoration: none;"><div>Log Out</div></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>