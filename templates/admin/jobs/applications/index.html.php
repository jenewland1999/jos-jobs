<main class="sidebar">
    <?php include __DIR__ . '/../../navigation.html.php'; ?>
    <section class="right">

        <?php if (
            $authUser->user_id === $job->user_id ||
            $authUser->hasPermission(\JosJobs\Entity\User::PERM_READ_ANY_JOBS)
        ): ?>
        <h2>Applications for <?php echo htmlspecialchars($job->title, ENT_QUOTES, 'UTF-8'); ?></h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">Name</th>
                    <th style="width: 10%;">Email</th>
                    <th style="width: 65%;">Details</th>
                    <th style="width: 15%;">CV</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($job->getApplications() as $application): ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($application->name, ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($application->email, ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($application->details, ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <td>
                            <a href="/uploads/cvs/<?php echo htmlspecialchars($application->cv, ENT_QUOTES, 'UTF-8'); ?>" target="_blank">
                                Download CV
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <h2>Permission Denied</h2>
            <p>You're not permitted to view this page.</p>
            <a href="/admin/">Return to Dashboard</a>
        <?php endif; ?>
    </section>
</main>
