<main class="sidebar">
    <section class="left"></section>
    <section class="right">
        <h2>Login to your account</h2>

        <?php if (!empty($errors)): ?>
            <ul class="errors">
                <?php foreach ($errors as $error): ?>
                    <li class="error">
                        <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form action="" method="post">
            <label for="user_email">Email Address</label>
            <input
                type="email"
                name="user[email]"
                id="user_email"
                value="<?php echo htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
            />

            <label for="user_pwd">Password</label>
            <input
                type="password"
                name="user[password]"
                id="user_pwd"
            />

            <input type="submit" name="submit" value="Login" />
        </form>
    </section>
</main>
