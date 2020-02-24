<main class="sidebar">
    <section class="left">
        <ul>
            <li><a href="/it.php">IT</a></li>
            <li class="current"><a href="/hr.php">Human Resources</a></li>
            <li><a href="/sales.php">Sales</a></li>
        </ul>
    </section>

    <section class="right">
        <h1>Human Resources Jobs</h1>

        <ul class="listing">
            <?php foreach($jobs as $job): ?>
                <li>
                    <div class="details">
                        <h2>
                            <?php echo htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8'); ?>
                        </h2>
                        <h3>
                            <?php echo htmlspecialchars($job['salary'], ENT_QUOTES, 'UTF-8'); ?>
                        </h3>
                        <p>
                            <?php echo nl2br(htmlspecialchars($job['description'], ENT_QUOTES, 'UTF-8')); ?>
                        </p>
                        <a 
                            class="more" 
                            href="/apply.php?id=<?php echo $job['id']; ?>"
                        >
                            Apply for this job
                        </a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
</main>
