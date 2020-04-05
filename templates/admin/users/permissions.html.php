<main class="sidebar">
    <?php include __DIR__ . '/../navigation.html.php'; ?>
    <section class="right">
        <h2>Update <?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name, ENT_QUOTES, 'UTF-8'); ?>'s Permissions</h2>

        <form action="" method="post">
            <fieldset>
                <legend>Category Permissions</legend>
                <?php foreach ($permissionsCategories as $key => $value): ?>
                    <label for="permission_<?php echo $key ?>">
                        <?php echo ucwords(strtolower(preg_replace('/PERM/', '', str_replace('_', ' ', $key), 1))); ?>
                    </label>
                    <input
                        type="checkbox"
                        name="permissions[]"
                        id="permission_<?php echo $key ?>"
                        value="<?php echo $value ?>"
                        <?php echo $user->hasPermission($value) ? 'checked' : ''; ?>
                    />
                <?php endforeach; ?>
            </fieldset>

            <fieldset>
                <legend>Enquiries Permissions</legend>
                <?php foreach ($permissionsEnquiries as $key => $value): ?>
                    <label for="permission_<?php echo $key ?>">
                        <?php echo ucwords(strtolower(preg_replace('/PERM/', '', str_replace('_', ' ', $key), 1))); ?>
                    </label>
                    <input
                        type="checkbox"
                        name="permissions[]"
                        id="permission_<?php echo $key ?>"
                        value="<?php echo $value ?>"
                        <?php echo $user->hasPermission($value) ? 'checked' : ''; ?>
                    />
                <?php endforeach; ?>
            </fieldset>

            <fieldset>
                <legend>Job Permissions</legend>
                <?php foreach ($permissionsJobs as $key => $value): ?>
                    <label for="permission_<?php echo $key ?>">
                        <?php echo ucwords(strtolower(preg_replace('/PERM/', '', str_replace('_', ' ', $key), 1))); ?>
                    </label>
                    <input
                        type="checkbox"
                        name="permissions[]"
                        id="permission_<?php echo $key ?>"
                        value="<?php echo $value ?>"
                        <?php echo $user->hasPermission($value) ? 'checked' : ''; ?>
                    />
                <?php endforeach; ?>
            </fieldset>

            <fieldset>
                <legend>Location Permissions</legend>
                <?php foreach ($permissionsLocations as $key => $value): ?>
                    <label for="permission_<?php echo $key ?>">
                        <?php echo ucwords(strtolower(preg_replace('/PERM/', '', str_replace('_', ' ', $key), 1))); ?>
                    </label>
                    <input
                        type="checkbox"
                        name="permissions[]"
                        id="permission_<?php echo $key ?>"
                        value="<?php echo $value ?>"
                        <?php echo $user->hasPermission($value) ? 'checked' : ''; ?>
                    />
                <?php endforeach; ?>
            </fieldset>

            <fieldset>
                <legend>User Permissions</legend>
                <?php foreach ($permissionsUsers as $key => $value): ?>
                    <label for="permission_<?php echo $key ?>">
                        <?php echo ucwords(strtolower(preg_replace('/PERM/', '', str_replace('_', ' ', $key), 1))); ?>
                    </label>
                    <input
                        type="checkbox"
                        name="permissions[]"
                        id="permission_<?php echo $key ?>"
                        value="<?php echo $value ?>"
                        <?php echo $user->hasPermission($value) ? 'checked' : ''; ?>
                    />
                <?php endforeach; ?>
            </fieldset>

            <input type="hidden" name="user_id" value="<?php echo $user->user_id; ?>" />
            <input type="submit" name="submit" value="Update Permissions" />
        </form>
    </section>
</main>
