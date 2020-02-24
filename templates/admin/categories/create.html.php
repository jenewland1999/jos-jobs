<main class="sidebar">
    <?php if ($isLoggedIn) : ?>
        <section class="left">
            <ul>
                <li><a href="/admin/categories/">Categories</a></li>
                <li><a href="/admin/jobs/">Jobs</a></li>
            </ul>
        </section>
        <section class="right">
            <h2>Create Category</h2>

            <?php if ($message) : ?>
                <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>
            
            <form action="" method="POST">
                <label>Name</label>
                <input type="text" name="category[name]" />
                <input type="submit" name="submit" value="Create" />
            </form>
        </section>
    <?php else: ?>
        <h2>Log in</h2>

        <form action="/admin/index.php" method="post" style="padding: 40px">
            <label>Enter Password</label>
            <input type="password" name="password" />

            <input type="submit" name="submit" value="Log In" />
        </form>
    <?php endif; ?>
</main>
