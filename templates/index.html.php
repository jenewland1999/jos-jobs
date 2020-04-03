<main class="home">
    <p>
        Welcome to Jo's Jobs, we're a recruitment agency based in Northampton.
        We offer a range of different office jobs. Get in touch if you'd like to
        list a job with us.
    </p>

    <h2>Select the type of job you are looking for:</h2>
    <ul>
        <?php foreach ($categories as $category) : ?>
            <li>
                <a href="/jobs?category=<?php echo $category->category_id; ?>">
                    <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
    <hr style="margin: 4em 0;" />
    <h2>Jobs closing soon:</h2>
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
</main>
