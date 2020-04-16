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
</main>
