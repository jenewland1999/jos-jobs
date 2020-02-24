<main class="sidebar">
    <section class="left">
        <ul>
            <li><a href="/admin/categories/">Categories</a></li>
            <li><a href="/admin/jobs/">Jobs</a></li>
        </ul>
    </section>

    <section class="right">
        <?php if ($isLoggedIn) : ?>
            <h2>Update Job</h2>

            <?php if ($message) : ?>
                <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>

            <form action="" method="post">
                <label for="title">Title</label>
                <input type="text" name="job[title]" id="title" value="<?php echo htmlspecialchars($job['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />

                <label for="description">Description</label>
                <textarea name="job[description]" id="description">
                    <?php echo nl2br(htmlspecialchars($job['title'] ?? '', ENT_QUOTES, 'UTF-8')); ?>
                </textarea>

                <label for="salary">Salary</label>
                <input type="text" name="job[salary]" id="salary" value="<?php echo htmlspecialchars($job['salary'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />

                <label for="location">Location</label>
                <input type="text" name="job[location]" id="location" value="<?php echo htmlspecialchars($job['location'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />

                <label for="category">Category</label>
                <select name="job[categoryId]" id="category">
                    <option value="" selected disabled>
                        Please select a category...
                    </option>
                    <?php foreach ($categories as $category) : ?>
                        <option 
                            value="<?php echo $category['id']; ?>" 
                            <?php echo $category['id'] === ($job['categoryId'] ?? '') ? 'selected' : '' ?>
                        >
                            <?php echo htmlspecialchars($category['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach ?>
                </select>

                <label for="closingDate">Closing Date</label>
                <input type="date" name="job[closingDate]" id="closingDate" value="<?php echo htmlspecialchars($job['closingDate'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />

                <input type="hidden" name="job[id]" value="<?php echo $job['id'] ?? ''; ?>">

                <input type="submit" name="submit" value="Update" />
            </form>
        <?php else: ?>
            <h2>Log in</h2>

            <form action="/admin/index.php" method="post" style="padding: 40px">
                <label>Enter Password</label>
                <input type="password" name="password" />

                <input type="submit" name="submit" value="Log In" />
            </form>
        <?php endif; ?>
    </section>
</main>
