<main class="sidebar">
    <?php if ($isLoggedIn) : ?>
        <section class="left">
            <ul>
                <li><a href="/admin/categories/">Categories</a></li>
                <li><a href="/admin/jobs/">Jobs</a></li>
            </ul>
        </section>
        <section class="right">
            <h2>Applicants for <?php echo htmlspecialchars($job['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h2>

            <table>
                <thead>
                    <tr>
                        <th style="width: 10%;">Name</th>
                        <th style="width: 10%;">Email</th>
                        <th style="width: 65%;">Details</th>
                        <th style="width: 15%;">CV</th>
                    </tr>
                    <?php foreach($applicants as $applicant): ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($applicant['name'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($applicant['email'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($applicant['details'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td>
                                <a href="/cvs/<?php echo htmlspecialchars($applicant['cv'], ENT_QUOTES, 'UTF-8'); ?>">
                                    Download CV
                                </a>
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
