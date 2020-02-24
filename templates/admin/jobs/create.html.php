<main class="sidebar">
    <section class="left">
        <ul>
            <li><a href="/admin/categories/">Categories</a></li>
            <li><a href="/admin/jobs/">Jobs</a></li>
        </ul>
    </section>

    <section class="right">
        <?php if ($isLoggedIn) : ?>
            <h2>Create Job</h2>

            <?php if ($message) : ?>
                <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>

            <form action="" method="post">
                <label for="title">Title</label>
                <input type="text" name="job[title]" id="title" />

                <label for="description">Description</label>
                <textarea name="job[description]" id="description"></textarea>

                <label for="salary">Salary</label>
                <input type="text" name="job[salary]" id="salary" />

                <label for="location">Location</label>
                <input type="text" name="job[location]" id="location" />

                <label for="category">Category</label>
                <select name="job[categoryId]" id="category">
                    <option value="" selected disabled>Please select a category...</option>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo $category['id']; ?>">
                            <?php echo $category['name']; ?>
                        </option>
                    <?php endforeach ?>
                </select>

                <label for="closingDate">Closing Date</label>
                <input type="date" name="job[closingDate]" id="closingDate" />

                <input type="submit" name="submit" value="Create" />
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
