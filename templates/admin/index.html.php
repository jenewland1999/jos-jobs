<main class="sidebar">
    <?php include __DIR__ . '/navigation.html.php'; ?>
    <section class="right">
        <h2>Welcome back, <?php echo htmlspecialchars($authUser->first_name . ' ' . $authUser->last_name, ENT_QUOTES, 'UTF-8'); ?></h2>
        <a href="/admin/users/update?id=<?php echo $authUser->user_id ?>">Update profile</a>
    </section>
</main>
