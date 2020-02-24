<main class="sidebar">
    <?php if ($isLoggedIn) : ?>
        <section class="left">
            <ul>
                <li><a href="/admin/categories/">Categories</a></li>
                <li><a href="/admin/jobs/">Jobs</a></li>
            </ul>
        </section>
        <section class="right">
            <h2>Categories</h2>

            <a class="new" href="/admin/categories/create.php">
                Create new category
            </a>

            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th style="width: 5%;">&nbsp;</th>
                        <th style="width: 5%;">&nbsp;</th>
                    </tr>
                    <?php foreach($categories as $category): ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td>
                                <a style="float: right;" href="/admin/categories/update.php?id=<?php echo $category['id']; ?>">
                                    Update
                                </a>
                            </td>
                            <td>
                                <form action="/admin/categories/delete.php" method="post">
                                    <input type="hidden" name="id" value="<?php echo $category['id']; ?>" />
                                    <input type="submit" name="submit" value="Delete" />
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </thead>
            </table>
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
