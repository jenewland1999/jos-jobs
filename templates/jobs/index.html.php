<main class="sidebar">
    <section class="left">
        <form action="">
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

            <label for="location">Location</label>
            <select name="location" id="location">
                <option value="" disabled selected>Please select a location...</option>
                <?php foreach ($locations as $location) : ?>
                    <option
                        value="<?php echo htmlspecialchars($location->location_id, ENT_QUOTES, 'UTF-8'); ?>"
                        <?php echo $location->location_id === ($_GET['location'] ?? '') ? 'selected' : '' ?>
                    >
                        <?php echo htmlspecialchars($location->name, ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <a href="/jobs">Clear Filter</a>
            <button type="submit">Filter Jobs</button>
        </form>
    </section>
    <section class="right">
        <h1><?php echo $heading; ?></h1>

        <ul class="listing">
            <?php foreach($jobs as $job): ?>
                <li class="details" style="margin-bottom: 0;">
                    <h5>
                        <em><?php echo htmlspecialchars($job->getCategory()->name, ENT_QUOTES, 'UTF-8'); ?></em>
                        &middot;
                        <em><?php echo htmlspecialchars($job->getLocation()->name, ENT_QUOTES, 'UTF-8'); ?></em>
                    </h5>
                    <h2><?php echo htmlspecialchars($job->title, ENT_QUOTES, 'UTF-8'); ?></h2>
                    <h3><?php echo htmlspecialchars($job->salary, ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p><?php echo (new \CupOfPHP\Markdown($job->description))->toHtml(); ?></p>
                    <a class="more" href="/jobs/apply?id=<?php echo $job->job_id; ?>">Apply for this job</a>
                    <p style="margin: 1em 0 0;">Closes on <?php echo htmlspecialchars((new DateTime($job->closing_date))->format('dS M Y'), ENT_QUOTES, 'UTF-8'); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>

        <nav class="pagination-wrapper" aria-label="Jobs results pages">
            <ul class="pagination justify-content-center">
                <li class="page-item">
                    <?php if ($currentPage <= 1): ?>
                        <a
                            href="/jobs?page=1<?php echo !empty($categoryId) ? '&category=' . $categoryId : '' ?><?php echo !empty($locationId) ? '&location=' . $locationId : '' ?>"
                            class="page-link disabled"
                            disabled
                        >
                            Previous
                        </a>
                    <?php else: ?>
                        <a
                            href="/jobs?page=<?php echo $currentPage - 1 ?><?php echo !empty($categoryId) ? '&category=' . $categoryId : '' ?><?php echo !empty($locationId) ? '&location=' . $locationId : '' ?>"
                            class="page-link"
                        >
                            Previous
                        </a>
                    <?php endif; ?>
                </li>

                <?php for ($i = 1; $i <= ceil($totalJobs/10); $i++): ?>
                    <li class="page-item">
                        <a
                            href="/jobs?page=<?php echo $i ?><?php echo !empty($categoryId) ? '&category=' . $categoryId : '' ?><?php echo !empty($locationId) ? '&location=' . $locationId : '' ?>"
                            class="page-link<?php echo $i == $currentPage ? ' active' : ''; ?>"
                        >
                            <?php echo $i ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <li class="page-item">
                    <?php if ($currentPage >= ceil($totalJobs/10)): ?>
                        <a
                            href="/jobs?page=<?php echo $totalJobs == 0 ? '1' : ceil($totalJobs/10); ?><?php echo !empty($categoryId) ? '&category=' . $categoryId : '' ?><?php echo !empty($locationId) ? '&location=' . $locationId : '' ?>"
                            class="page-link disabled"
                            disabled
                        >
                            Next
                        </a>
                    <?php else: ?>
                        <a
                            href="/jobs?page=<?php echo $currentPage + 1 ?><?php echo !empty($categoryId) ? '&category=' . $categoryId : '' ?><?php echo !empty($locationId) ? '&location=' . $locationId : '' ?>"
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
