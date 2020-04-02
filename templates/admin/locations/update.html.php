<main class="sidebar">
    <?php include __DIR__ . '/../navigation.html.php'; ?>
    <section class="right">
        <h2><?php echo isset($_GET['id']) ? 'Update' : 'Create' ?> Location</h2>

        <?php if (!empty($errors)): ?>
            <ul class="errors">
                <?php foreach ($errors as $error): ?>
                    <li class="error">
                        <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form action="" method="POST">
            <label>Name</label>
            <input
                type="text"
                name="location[name]"
                value="<?php echo htmlspecialchars($location->name ?? '', ENT_QUOTES, 'UTF-8'); ?>"
            />
            <input type="hidden" name="location[location_id]" value="<?php echo $location->location_id ?? ''; ?>" />
            <input type="submit" name="submit" value="<?php echo isset($_GET['id']) ? 'Update' : 'Create' ?>" />
        </form>
    </section>
</main>
