<main class="sidebar">
    <?php include __DIR__ . '/../navigation.html.php'; ?>
    <section class="right">
        <h2>Users</h2>

        <?php if ($authUser->hasPermission(\JosJobs\Entity\User::PERM_CREATE_USERS)): ?>
            <a class="new" href="/admin/users/create?redirect=users">
                Create new user
            </a>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th style="width: 10%;">Access Level</th>
                    <th style="width: 15%;">&nbsp;</th>
                    <th style="width: 5%;">&nbsp;</th>
                    <th style="width: 5%;">&nbsp;</th>
                </tr>
                <?php foreach($users as $user): ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($user->first_name, ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($user->last_name, ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <td>

                            <?php if ($user->permissions >= 131071): ?>
                                <span style="border-bottom: 1px dotted #333; cursor: help;" title="The owner(s) of the site has all permissions and one must exist at any given time.">Owner</span>
                            <?php elseif ($user->permissions > 818 && $user->permissions < 131071): ?>
                                <span style="border-bottom: 1px dotted #333; cursor: help;" title="An administrator is a privileged client with additional permissions. For example, deleting or updating categories and locations. The permissions they possess vary depending on what is set by an owner. They cannot change permissions, delete or update an owner.">Admin</span>
                            <?php elseif ($user->permissions > 2 && $user->permissions <= 818): ?>
                                <span style="border-bottom: 1px dotted #333; cursor: help;" title="Privileged clients are clients with the ability to view and create new categories and locations.">Privileged Client</span>
                            <?php else: ?>
                                <span style="border-bottom: 1px dotted #333; cursor: help;" title="Clients are the most basic users. They may update their own profile, create job postings and update, archive and view applications for jobs they posted.">Client</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (
                                $authUser->user_id !== $user->user_id &&
                                $authUser->hasPermission(\JosJobs\Entity\User::PERM_PERMISSIONS_USERS)
                            ): ?>
                                <a style="float: right;" href="/admin/users/permissions?id=<?php echo $user->user_id; ?>">
                                    Update Permissions
                                </a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (
                                ($authUser->user_id === $user->user_id ||
                                $authUser->hasPermission(\JosJobs\Entity\User::PERM_UPDATE_USERS)) &&
                                $authUser->permissions >= $user->permissions
                            ): ?>
                                <a style="float: right;" href="/admin/users/update?id=<?php echo $user->user_id; ?>?redirect=users">
                                    Update
                                </a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (
                                $authUser->user_id !== $user->user_id &&
                                $authUser->permissions >= $user->permissions &&
                                $authUser->hasPermission(\JosJobs\Entity\User::PERM_DELETE_USERS)
                            ): ?>
                                <form action="/admin/users/delete" method="post">
                                    <input type="hidden" name="user_id" value="<?php echo $user->user_id; ?>" />
                                    <input type="submit" name="submit" value="Delete" />
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </thead>
        </table>
    </section>
</main>
