<main class="home">
    <h2>Apply for <?php echo $job->title; ?></h2>

    <?php if (!empty($errors)): ?>
            <ul class="errors">
                <?php foreach ($errors as $error): ?>
                    <li class="error">
                        <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    <form action="" method="post" enctype="multipart/form-data">
        <label for="name">Your Name</label>
        <input
            type="text"
            name="application[name]"
            id="name"
            value="<?php echo htmlspecialchars($application['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
            placeholder="John Smith"

        />

        <label for="email">Email Address</label>
        <input
            type="email"
            name="application[email]"
            id="email"
            value="<?php echo htmlspecialchars($application['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
            placeholder="john.smith@example.org"

        />

        <label for="details">Cover Letter</label>
        <textarea
            name="application[details]"
            id="details"
            placeholder="Write a cover letter to accompany your application."
        ><?php echo htmlspecialchars($application['details'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>

        <label for="cv">CV</label>
        <input
            type="file"
            name="cv"
            id="cv"

        />

        <input
            type="hidden"
            name="application[job_id]"
            value="<?php echo $job->job_id;?>"
        />

        <input type="submit" name="submit" value="Apply" />
    </form>
</main>
