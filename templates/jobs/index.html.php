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
        <h1>
            <?php echo $heading; ?>
        </h1>

        <ul class="listing">
            <?php foreach($jobs as $job): ?>
                <li>
                    <div class="details">
                        <h2>
                            <?php echo htmlspecialchars($job->title, ENT_QUOTES, 'UTF-8'); ?>
                        </h2>
                        <h3>
                            <?php echo htmlspecialchars($job->salary, ENT_QUOTES, 'UTF-8'); ?>
                        </h3>
                        <p>
                            <?php echo nl2br(htmlspecialchars($job->description, ENT_QUOTES, 'UTF-8')); ?>
                        </p>
                        <a
                            class="more"
                            href="/jobs/apply?id=<?php echo $job->job_id; ?>"
                        >
                            Apply for this job
                        </a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
</main>
