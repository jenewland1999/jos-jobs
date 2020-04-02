<main class="sidebar">
    <?php include __DIR__ . '/../navigation.html.php'; ?>
    <section class="right">
        <h2>Locations</h2>

        <?php if ($authUser->hasPermission(\JosJobs\Entity\User::PERM_CREATE_LOCATIONS)): ?>
            <a class="new" href="/admin/locations/create">
                Create new location
            </a>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th style="width: 5%;">&nbsp;</th>
                    <th style="width: 5%;">&nbsp;</th>
                </tr>
                <?php foreach($locations as $location): ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($location->name, ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <td>
                            <?php if ($authUser->hasPermission(\JosJobs\Entity\User::PERM_UPDATE_LOCATIONS)): ?>
                                <a style="float: right;" href="/admin/locations/update?id=<?php echo $location->location_id; ?>">
                                    Update
                                </a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($authUser->hasPermission(\JosJobs\Entity\User::PERM_DELETE_LOCATIONS)): ?>
                                <form action="/admin/locations/delete" method="post">
                                    <input type="hidden" name="location_id" value="<?php echo $location->location_id; ?>" />
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
