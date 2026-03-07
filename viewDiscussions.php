<?php
ob_start();

session_cache_expire(30);
session_start();

$loggedIn = false;
$accessLevel = 0;
$userID = null;
if (isset($_SESSION['_id'])) {
    $loggedIn = true;
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
}

include_once "database/dbDiscussions.php";
include_once "domain/Discussion.php";
include_once "database/dbPersons.php";

$discussions = get_all_discussions();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Gwyneth's Gift | View Discussions</title>
  	<link href="css/normal_tw.css" rel="stylesheet">

<?php
$tailwind_mode = true;
require_once('header.php');
?>
<h1>View Discussions</h1>

</head>
<body>
        
    <main>

      <div class="main-content-box w-[90%] p-8">
        <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>

        <div class="top-bar">
            <?php if ($accessLevel > 2): ?>

                <form action="deleteBulk.php" method="POST" onsubmit="return confirm('Are you sure you want to delete ALL discussions?');">
                    <button type="submit" name="delete_all" class="delete-button">Delete All</button>
                </form>
            <?php endif; ?>

            <div id="bulk-actions" style="display:none;">
                <span style="font-weight: bold;">With Selected:</span>
                <button type="button" id="bulk-delete-btn" class="delete-button" onclick="deleteSelectedDiscussions();">Delete</button>
            </div>

        </div>


        <table>
            <thead>
                <tr>
                    <?php if ($accessLevel > 2): ?>
                        <th><input type="checkbox" id="selectAll"></th>
                    <?php endif; ?>
                    <th>Author</th>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($discussions): ?>
                    <?php foreach ($discussions as $discussion): 
                        $person = get_user_from_author($discussion['author_id']);
                        $author_name = $person->get_first_name() . ' ' . $person->get_last_name();
                        $entryValue = htmlspecialchars($discussion['author_id']) . '|' . htmlspecialchars($discussion['title']);
                    ?>
                        <tr>
                            <?php if ($accessLevel > 2): ?>
                                <td>
                                    <input type="checkbox" class="rowCheckbox" name="selected_discussions[]" value="<?php echo $entryValue; ?>">
                                </td>
                            <?php endif; ?>
        
                            <td><?php echo $author_name; ?></td>
                            <td><?php echo $discussion['title']; ?></td>
                            <td><?php echo $discussion['time']; ?></td>
                            <td>
                                <a href="discussionContent.php?author=<?php echo urlencode($person->get_id()); ?>&title=<?php echo urlencode($discussion['title']); ?>" class="blue-button">View</a>

                                <?php if ($accessLevel > 2): ?>
                                    <form action="deleteDiscussion.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="author_id" value="<?php echo htmlspecialchars($person->get_id()); ?>">
                                        <input type="hidden" name="title" value="<?php echo htmlspecialchars($discussion['title']); ?>">
                                        <button type="submit" class="delete-button" onclick="return confirm('Are you sure you want to delete this discussion?');">Delete</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5">No discussions found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <br>
        <?php
            if ($accessLevel > 2):
            ?>
                <div class="text-center">
                    <a href="createDiscussion.php" class="blue-button">Create Discussion</a>
                </div>
            <?php
            endif;
        ?>

	</div>
    <div class="text-center mt-6">
        <a href="index.php" class="return-button">Return to Dashboard</a>
    </div>
    <div class="info-section">
        <div class="blue-div"></div>
        <p class="info-text">
            Use this tool to filter and search for volunteers or participants by their role, event involvement, and status. Mailing list support is built in.
        </p>
    </div>

    </main>

    <script>
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.rowCheckbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
            toggleBulkActions();
        });

        document.querySelectorAll('.rowCheckbox').forEach(cb => {
            cb.addEventListener('change', toggleBulkActions);
        });

        function toggleBulkActions() {
            const anyChecked = [...document.querySelectorAll('.rowCheckbox')].some(cb => cb.checked);
            document.getElementById('bulk-actions').style.display = anyChecked ? 'block' : 'none';
        }
        function deleteSelectedDiscussions() {
            const selected = [...document.querySelectorAll('.rowCheckbox:checked')]
                .map(cb => cb.value);

            if (selected.length === 0) {
                alert('No discussions selected for deletion.');
                return;
            }

            if (confirm('Are you sure you want to delete the selected discussions?')) {
                const formData = new FormData();
                formData.append('bulk_delete', true);
                formData.append('selected_discussions', JSON.stringify(selected));

                fetch('deleteBulk.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        alert('An error occurred while deleting discussions.');
                    }
                })
                .catch(error => {
                    alert('An error occurred while deleting discussions.');
                });
            }
        }
    </script>
</body>
</html>
<?php ob_end_flush(); ?>