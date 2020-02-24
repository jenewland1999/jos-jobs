<main class="sidebar">
    <?php if ($isLoggedIn) : ?>
        <section class="left">
            <ul>
                <li><a href="/admin/categories/">Categories</a></li>
                <li><a href="/admin/jobs/">Jobs</a></li>
            </ul>
        </section>
        <section class="right">
            <h2>Jobs</h2>

            <a class="new" href="/admin/jobs/create.php">Create new job</a>

            <table>
                <thead>
                    <tr>
                        <th style="width: 55%;">Title</th>
                        <th style="width: 15%;">Salary</th>
                        <th style="width: 15%;">&nbsp;</th>
                        <th style="width: 5%;">&nbsp;</th>
                        <th style="width: 5%;">&nbsp;</th>
                        <th style="width: 5%;">&nbsp;</th>
                    </tr>
                    <?php foreach($jobs as $job): ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($job['salary'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td>
                                <a style="float: right;" href="/admin/jobs/applicants?id=<?php echo $job['id']; ?>">
                                    View applicants (<?php echo $job['applicantCount']; ?>)
                                </a>
                            </td>
                            <td>
                                <a style="float: right;" href="/admin/jobs/update.php?id=<?php echo $job['id']; ?>">
                                    Update
                                </a>
                            </td>
                            <td>
                                <form action="/admin/jobs/delete.php" method="post">
                                    <input type="hidden" name="id" value="<?php echo $job['id']; ?>" />
                                    <input type="submit" name="submit" value="Delete" />
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </thead>
            </table>
        </section>
    <?php else: ?>
        <h2>Log in</h2>

        <form action="/admin/index.php" method="post" style="padding: 40px">
            <label>Enter Password</label>
            <input type="password" name="password" />

            <input type="submit" name="submit" value="Log In" />
        </form>
    <?php endif; ?>
</main>
