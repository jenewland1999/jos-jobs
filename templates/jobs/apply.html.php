<main class="home">
    <h2>
        Apply for
        <?php echo $job['title']; ?>
    </h2>

    <form action="" method="post" enctype="multipart/form-data">
        <label>Your name</label>
        <input type="text" name="name" />

        <label>E-mail address</label>
        <input type="text" name="email" />

        <label>Cover letter</label>
        <textarea name="details"></textarea>

        <label>CV</label>
        <input type="file" name="cv" />

        <input type="hidden" name="jobId" value="<?php echo $job['id'];?>" />

        <input type="submit" name="submit" value="Apply" />
    </form>
</main>
