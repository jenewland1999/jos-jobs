<main class="sidebar">
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) : ?>
        <section class="left">
            <ul>
                <li><a href="/admin/categories/">Categories</a></li>
                <li><a href="/admin/jobs/">Jobs</a></li>
            </ul>
        </section>
        <section class="right">
            <h2>You are now logged in</h2>
        </section>
    <?php else: ?>
        <h2>Log in</h2>

        <form action="" method="post" style="padding: 40px">
            <label>Enter Password</label>
            <input type="password" name="password" />

            <input type="submit" name="submit" value="Log In" />
        </form>
    <?php endif; ?>    
</main>
