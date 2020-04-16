<main class="sidebar">
    <?php include __DIR__ . '/../navigation.html.php'; ?>
    <section class="right">
        <?php if (!empty($errors)): ?>
            <ul class="errors">
                <?php foreach ($errors as $error): ?>
                    <li class="error">
                        <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if (
            (empty($user->user_id) && $authUser->hasPermission(\JosJobs\Entity\User::PERM_CREATE_USERS)) ||
            (!empty($user->user_id) && ($authUser->user_id === $user->user_id ||$authUser->hasPermission(\JosJobs\Entity\User::PERM_UPDATE_USERS)) && $authUser->permissions >= $user->permissions)
        ): ?>
            <h2><?php echo isset($_GET['id']) ? 'Update' : 'Create' ?> User</h2>

            <?php if (isset($_GET['id']) && $user->user_id === $authUser->user_id): ?>
                <p>Updating your account details will log you out. Please be sure to write down your password.</p>
            <?php endif; ?>

            <form action="" method="post">
                <div class="input-group">
                    <label for="user_first_name">First Name</label>
                    <input
                        type="text"
                        name="user[first_name]"
                        id="user_first_name"
                        value="<?php echo htmlspecialchars($user->first_name ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    />
                </div>

                <div class="input-group">
                    <label for="user_last_name">Last Name</label>
                    <input
                        type="text"
                        name="user[last_name]"
                        id="user_last_name"
                        value="<?php echo htmlspecialchars($user->last_name ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    />
                </div>

                <div class="input-group">
                    <label for="user_email">Email Address</label>
                    <input
                        type="email"
                        name="user[email]"
                        id="user_email"
                        value="<?php echo htmlspecialchars($user->email ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    />
                </div>

                <div class="input-group">
                    <label for="user_pwd">Password</label>
                    <input
                        type="password"
                        name="user[password]"
                        id="user_pwd"
                    />
                    <?php if (isset($_GET['id'])): ?>
                        <small>Passwords cannot be edited. Please set a new password.</small>
                    <?php endif; ?>
                </div>

                <input type="hidden" name="user[user_id]" value="<?php echo $user->user_id ?? ''; ?>" />
                <input type="submit" name="submit" value="<?php echo isset($_GET['id']) ? 'Update' : 'Create' ?>" />
            </form>
        <?php else: ?>
            <h2>Permission Denied</h2>
            <p>You're not permitted to view this page.</p>
            <a href="/admin/">Return to Dashboard</a>
        <?php endif; ?>
    </section>
</main>
