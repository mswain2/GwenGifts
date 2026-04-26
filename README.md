# Student Names
Samer Bishay
Olivia Buchanan
Eric McGowan
Saumik Mullah
Brian Prelle
Charles Stoeter
Mackenzie Swain

# Gwyneth's Gift Volunteer Management Web Application 

## Purpose
This project is the result of a semester's worth of collaboration among UMW students. The goal of the project was to create a web application that better suits the needs of Gwyneth's Gift, specficilly as a system to manage their volunteers and events. The system allows volunteers to sign up for events, manage their profile, check-in and out of events, and view their calendar, volunteer hours, and training materials. Event managers are able to create, edit, and cancel events, and manage event specific information such as attendace, comments, media, rosters, reports, volunteer hours, and training materials. Board members are able to manage documents, participate in forums, and view foundation anayltics reports. Administrators are able to manage user accounts and contact users through email.

## Authors
The Gwyneth's Gift volunteer management system is based on a previous project for Whiskey Valor. This was originally developed by another group of UMW students.

## User Types
There are four types of users (also referred to as 'roles') within the Gwyneth's Gift VMS.
* Volunteers
* Event Managers
* Board Members
* Administrators

The capabilities of these roles are described in the purpose section at the beginning of this file.

There is also a root admin account with username 'vmsroot'. The default password for this account is 'vmsroot'. This account has hardcoded Admin privileges. It is crucial that this account be given a strong password and that the password be easily remembered, as it cannot easily be reset. This account should be used for system administration purposes only.

## Design Documentation
Several types of diagrams describing the design of the Gwyneth's Gift VMS, including sequence diagrams and use case diagrams, are available. Please contact Dr. Polack for access.

## "localhost" Installation
Below are the steps required to run the project on your local machine for development and/or testing purposes.
1. [Download and install XAMPP](https://www.apachefriends.org/download.html)
2. Open a terminal/command prompt and change directory to your XAMPP install's htdocs folder
  * For Mac, the htdocs path is `/Applications/XAMPP/xamppfiles/htdocs`
  * For Ubuntu, the htdocs path is `/opt/lampp/htdocs/`
  * For Windows, the htdocs path is `C:\xampp\htdocs`
3. Clone the Gwyneth's Gift repo by running the following command: 'git clone https://github.com/mswain2/GwenGifts.git'
4. Start the XAMPP MySQL server and Apache server
5. Open the PHPMyAdmin console by navigating to [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/)
6. Create a new database named `gwengiftsdb`. With the database created, navigate to it by clicking on it in the lefthand pane
7. Import the `GwenGifts.sql` file located in `GwenGifts/sql` into this new database
8. Create a new user by navigating to `Privileges -> New -> Add user account`
9. Enter the following credentials for the new user:
  * Name: `gwengiftsdb`
  * Hostname: `Local`
  * Password: `gwengiftsdb`
  * Leave everything else untouched
10. Navigate to [http://localhost/GwenGifts/](http://localhost/ODHS-Animal/) 
11. Log into the root user account using the username `vmsroot` with password `vmsroot`

Installation is now complete.

## Platform
Dr. Polack chose SiteGround as the platform on which to host the project. Below are some guides on how to manage the live project.

### SiteGround Dashboard
Access to the SiteGround Dashboard requires a SiteGround account with access. Access is managed by Dr. Polack.

### Localhost to Siteground
Follow these steps to transfter your localhost version of the Step VA code to Siteground. For a video tutorial on how to complete these steps, contact Dr. Polack.
1. Create an FTP Account on Siteground, giving you the necessary FTP credentials. (Hostname, Username, Password, Port)
2. Use FTP File Transfer Software (Filezilla, etc.) to transfer the files from your localhost folders to your siteground folders using the FTP credentials from step 1.
3. Create the following database-related credentials on Siteground under the MySQL tab:
  - Database - Create the database for the siteground version under the Databases tab in the MySQL Manager by selecting the 'Create Database' button. Database name is auto-generated and can be changed if you like.
  - User - Create a user for the database by either selecting the 'Create User' button under the Users tab, or by selecting the 'Add New User' button from the newly created database under the Databases tab. User name is auto-generated and can be changed  if you like.
  - Password - Created when user is created. Password is auto generated and can be changed if you like.
4. Access the newly created database by navigating to the PHPMyAdmin tab and selecting the 'Access PHPMyAdmin' button. This will redirect you to the PHPMyAdmin page for the database you just created. Navigate to the new database by selecting it from the database list on the left side of the page.
5. Select the 'Import' option from the database options at the top of the page. Select the 'Choose File' button and import the "vms.sql" file from your software files.
  - Ensure that you're keeping your .sql file up to date in order to reduce errors in your Siteground code. Keep in mind that Siteground is case-sensitive, and your database names in the Siteground files must be identical to the database names in the database.
6. Navigate to the 'dbInfo.php' page in your Siteground files. Inside the connect() function, you will see a series of PHP variables. ($host, $database, $user, $pass) Change the server name in the 'if' statement to the name of your server, and change the $database, $user, and $pass variables to the database name, user name, and password that you created in step 3. 

### Clearing the SiteGround cache
#### Chrome
1. Open Chrome and click on the three-dot menu icon in the top-right corner.
2. Navigate to **More Tools** > **Clear Browsing Data**.
3. In the pop-up window:
   - Select the **Time Range** (e.g., "Last 24 hours" or "All time").
   - Check the box for **Cached images and files**.
4. Click **Clear Data**.

#### Safari
1. Open Safari and click on **Safari** in the menu bar at the top of the screen.
2. Select **Preferences** > **Privacy**.
3. Click the **Manage Website Data** button.
4. In the pop-up window, click **Remove All**, then confirm by selecting **Remove Now**.

Clearing your cache will help ensure that you're seeing the latest updates to the application. If you continue experiencing issues, consider reaching out for further support.

### External Libraries and APIs
These were external libraries utilized by previous groups that may still be included.

The only outside library utilized by the Step VA is the jQuery library. The version of jQuery used by the system is stored locally within the repo, within the lib folder. jQuery was used to implement form validation and the hiding/showing of certain page elements. Additionally, the Font Awesome library was used for some of the icon pictures. This library is linked in the headers of some files "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css".

## License
The project remains under the [GNU General Public License v3.0](https://www.gnu.org/licenses/gpl.txt).

## Acknowledgements
Thank you to Dr. Polack and Gwyneth's Gift for the opportunity to work on this project.