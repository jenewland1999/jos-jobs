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
            (empty($job->job_id) && $authUser->hasPermission(\JosJobs\Entity\User::PERM_CREATE_JOBS)) ||
            (!empty($job->job_id) && ($authUser->user_id === $job->user_id ||$authUser->hasPermission(\JosJobs\Entity\User::PERM_UPDATE_JOBS)))
        ): ?>
            <h2><?php echo isset($_GET['id']) ? 'Update' : 'Create' ?> Job</h2>
            <form action="" method="post">
                <label for="title">Title</label>
                <input type="text" name="job[title]" id="title" value="<?php echo htmlspecialchars($job->title ?? '', ENT_QUOTES, 'UTF-8'); ?>" />

                <label for="description">Description</label>
                <textarea name="job[description]" id="description"><?php echo nl2br(htmlspecialchars($job->description ?? '', ENT_QUOTES, 'UTF-8')); ?></textarea>

                <label for="salary">Salary</label>
                <input type="text" name="job[salary]" id="salary" value="<?php echo htmlspecialchars($job->salary ?? '', ENT_QUOTES, 'UTF-8'); ?>" />

                <label for="location">location</label>
                <select name="job[location_id]" id="location">
                    <option value="" selected disabled>
                        Please select a location...
                    </option>
                    <?php foreach ($locations as $location) : ?>
                        <option
                            value="<?php echo $location->location_id; ?>"
                            <?php echo $location->location_id === ($job->location_id ?? '') ? 'selected' : '' ?>
                        >
                            <?php echo htmlspecialchars($location->name ?? '', ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach ?>
                </select>

                <label for="category">Category</label>
                <select name="job[category_id]" id="category">
                    <option value="" selected disabled>
                        Please select a category...
                    </option>
                    <?php foreach ($categories as $category) : ?>
                        <option
                            value="<?php echo $category->category_id; ?>"
                            <?php echo $category->category_id === ($job->category_id ?? '') ? 'selected' : '' ?>
                        >
                            <?php echo htmlspecialchars($category->name ?? '', ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach ?>
                </select>

                <label for="closing_date">Closing Date</label>
                <input type="date" name="job[closing_date]" id="closing_date" value="<?php echo htmlspecialchars($job->closing_date ?? '', ENT_QUOTES, 'UTF-8'); ?>" />

                <input type="hidden" name="job[job_id]" value="<?php echo $job->job_id ?? ''; ?>">

                <input type="submit" name="submit" value="<?php echo isset($_GET['id']) ? 'Update' : 'Create' ?>" />
            </form>
        <?php else: ?>
            <h2>Permission Denied</h2>
            <p>You're not permitted to view this page.</p>
            <a href="/admin/">Return to Dashboard</a>
        <?php endif; ?>
    </section>
</main>
