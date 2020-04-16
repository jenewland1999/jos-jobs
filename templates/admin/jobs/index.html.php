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
                    <th>Closing Date</th>
                    <th>Category</th>
                    <th>Location</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                <?php foreach($jobs as $job): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($job->title, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($job->salary, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars((new \DateTime($job->closing_date))->format('jS M Y'), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($job->getCategory()->name, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($job->getLocation()->name, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo $job->getUser()->getSanitisedName(); ?></td>
                        <td><?php echo $job->is_archived ? 'Archived' : 'Active'; ?></td>
                        <td>
                            <a style="display:block; text-align:right; margin-bottom: 0.25em;" href="/admin/jobs/applications?id=<?php echo $job->job_id; ?>">
                                View applicants (<?php echo $job->getApplicationCount(); ?>)
                            </a>

                            <?php if (
                                $authUser->user_id === $job->user_id ||
                                $authUser->hasPermission(\JosJobs\Entity\User::PERM_UPDATE_ANY_JOBS)
                            ): ?>
                                <a style="display:block; text-align:right; margin-bottom: 0.25em;" href="/admin/jobs/update?id=<?php echo $job->job_id; ?>">
                                    Update
                                </a>
                            <?php endif; ?>

                            <?php if (
                                $authUser->user_id === $job->user_id ||
                                $authUser->hasPermission(\JosJobs\Entity\User::PERM_ARCHIVE_ANY_JOBS)
                            ): ?>
                                <form action="/admin/jobs/archive" method="post">
                                    <input type="hidden" name="job_id" value="<?php echo $job->job_id; ?>" />
                                    <input type="submit" style="margin-bottom: 0.25em;" name="submit" value="<?php echo $job->is_archived ? 'Un-archive' : 'Archive'; ?>" />
                                </form>
                            <?php endif; ?>

                            <?php if (
                                $authUser->user_id === $job->user_id ||
                                $authUser->hasPermission(\JosJobs\Entity\User::PERM_DELETE_ANY_JOBS)
                            ): ?>
                                <form action="/admin/jobs/delete" method="post">
                                    <input type="hidden" name="job_id" value="<?php echo $job->job_id; ?>" />
                                    <input type="submit" style="margin-bottom: 0.25em;" name="submit" value="Delete" />
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </thead>
        </table>

        <nav class="pagination-wrapper" aria-label="Admin Jobs results pages">
            <ul class="pagination justify-content-center">
                <li class="page-item">
                    <?php if ($currentPage <= 1): ?>
                        <a
                            href="/admin/jobs?page=1<?php echo !empty($categoryId) ? '&category=' . $categoryId : '' ?><?php echo !empty($locationId) ? '&location=' . $locationId : '' ?>"
                            class="page-link disabled"
                            disabled
                        >
                            Previous
                        </a>
                    <?php else: ?>
                        <a
                            href="/admin/jobs?page=<?php echo $currentPage - 1 ?><?php echo !empty($categoryId) ? '&category=' . $categoryId : '' ?><?php echo !empty($locationId) ? '&location=' . $locationId : '' ?>"
                            class="page-link"
                        >
                            Previous
                        </a>
                    <?php endif; ?>
                </li>

                <?php for ($i = 1; $i <= ceil($totalJobs/10); $i++): ?>
                    <li class="page-item">
                        <a
                            href="/admin/jobs?page=<?php echo $i ?><?php echo !empty($categoryId) ? '&category=' . $categoryId : '' ?><?php echo !empty($locationId) ? '&location=' . $locationId : '' ?>"
                            class="page-link<?php echo $i == $currentPage ? ' active' : ''; ?>"
                        >
                            <?php echo $i ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <li class="page-item">
                    <?php if ($currentPage >= ceil($totalJobs/10)): ?>
                        <a
                            href="/admin/jobs?page=<?php echo $totalJobs == 0 ? '1' : ceil($totalJobs/10); ?><?php echo !empty($categoryId) ? '&category=' . $categoryId : '' ?><?php echo !empty($locationId) ? '&location=' . $locationId : '' ?>"
                            class="page-link disabled"
                            disabled
                        >
                            Next
                        </a>
                    <?php else: ?>
                        <a
                            href="/admin/jobs?page=<?php echo $currentPage + 1 ?><?php echo !empty($categoryId) ? '&category=' . $categoryId : '' ?><?php echo !empty($locationId) ? '&location=' . $locationId : '' ?>"
                            class="page-link disabled"
                        >
                            Next
                        </a>
                    <?php endif; ?>
                </li>
            </ul>
        </nav>
    </section>
</main>
