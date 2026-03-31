<?php if (isset($_GET['pcSuccess'])): ?>
    <div class="happy-toast">Password changed successfully!</div>
<?php elseif (isset($_GET['deleteService'])): ?>
    <div class="happy-toast">Service successfully removed!</div>
<?php elseif (isset($_GET['serviceAdded'])): ?>
    <div class="happy-toast">Service successfully added!</div>
<?php elseif (isset($_GET['animalRemoved'])): ?>
    <div class="happy-toast">Animal successfully removed!</div>
<?php elseif (isset($_GET['locationAdded'])): ?>
    <div class="happy-toast">Location successfully added!</div>
<?php elseif (isset($_GET['deleteLocation'])): ?>
    <div class="happy-toast">Location successfully removed!</div>
<?php elseif (isset($_GET['registerSuccess'])): ?>
    <div class="happy-toast">Volunteer registered successfully!</div>
<?php endif ?>
