<main class="sidebar">
    <?php include __DIR__ . '/../navigation.html.php'; ?>
    <section class="right">
        <h2>Jobs</h2>

        <?php if ($authUser->hasPermission(\JosJobs\Entity\User::PERM_CREATE_JOBS)): ?>
            <a class="new" href="/admin/jobs/create">Create new job</a>
        <?php endif; ?>

        <form action="" style="display:block;">
            <div class="input__group">
                <label for="category">Category</label>
                <select name="category" id="category">
                    <option value="" disabled selected>Please select a category...</option>
                    <?php foreach ($categories as $category) : ?>
                        <option
                            value="<?php echo $category->category_id; ?>"
                            <?php echo $category->category_id === ($_GET['category'] ?? '') ? 'selected' : '' ?>
                        >
                            <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input__group">
                <label for="location">Location</label>
                <select name="location" id="location">
                    <option value="" disabled selected>Please select a location...</option>
                    <?php foreach ($locations as $location) : ?>
                        <option
                            value="<?php echo $location->location_id; ?>"
                            <?php echo $location->location_id === ($_GET['location'] ?? '') ? 'selected' : '' ?>
                        >
                            <?php echo htmlspecialchars($location->name, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit">Filter</button>
            <a href="/admin/jobs">Clear Filter</a>
        </form>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Salary</th>
                    <th>Category</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
                <?php foreach($jobs as $job): ?>
                    <?php if (
                        $authUser->user_id === $job->user_id ||
                        $authUser->hasPermission(\JosJobs\Entity\User::PERM_READ_JOBS)
                    ): ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($job->title, ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($job->salary, ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($job->getCategory()->name, ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($job->getLocation()->name, ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td>
                                <?php echo $job->is_archived ? 'Archived' : 'Active'; ?>
                            </td>
                            <td>
                                <a style="float: right;" href="/admin/jobs/applications?id=<?php echo $job->job_id; ?>">
                                    View applicants (<?php echo $job->getApplicationCount(); ?>)
                                </a>
                            </td>
                            <td>
                                <?php if (
                                    $authUser->user_id === $job->user_id ||
                                    $authUser->hasPermission(\JosJobs\Entity\User::PERM_UPDATE_JOBS)
                                ): ?>
                                    <a style="float: right;" href="/admin/jobs/update?id=<?php echo $job->job_id; ?>">
                                        Update
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (
                                    $authUser->user_id === $job->user_id ||
                                    $authUser->hasPermission(\JosJobs\Entity\User::PERM_ARCHIVE_JOBS)
                                ): ?>
                                    <form action="/admin/jobs/archive" method="post">
                                        <input type="hidden" name="job_id" value="<?php echo $job->job_id; ?>" />
                                        <input type="submit" name="submit" value="<?php echo $job->is_archived ? 'Un-archive' : 'Archive'; ?>" />
                                    </form>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (
                                    $authUser->user_id === $job->user_id ||
                                    $authUser->hasPermission(\JosJobs\Entity\User::PERM_DELETE_JOBS)
                                ): ?>
                                    <form action="/admin/jobs/delete" method="post">
                                        <input type="hidden" name="job_id" value="<?php echo $job->job_id; ?>" />
                                        <input type="submit" name="submit" value="Delete" />
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </thead>
        </table>
    </section>
</main>
