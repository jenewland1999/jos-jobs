<main class="sidebar">
    <section class="left">
        <ul>
            <?php foreach ($categories as $category) : ?>
                <li>
                    <a
                        class="<?php echo $category['id'] === ($_GET['category'] ?? '') ? 'current' : '' ?>"
                        href="/jobs?category=<?php echo $category['id']; ?>"
                    >
                        <?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <section class="right">
        <h1>
            <?php echo $heading . ' '; ?>
            Jobs
        </h1>

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
