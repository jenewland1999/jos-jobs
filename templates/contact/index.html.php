<main class="home">
    <?php if (isset($_GET['reply']) && $_GET['reply'] === 'success'): ?>
        <p>Thank you. Your enquiry has been recorded and you will hear from us shortly.</p>
    <?php endif; ?>

    <h2>Contact Us</h2>

    <p>Please make an enquiry below and we'll aim to get back to you within 5 working days.</p>

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
        <label for="name">Name</label>
        <input
            type="text"
            name="enquiry[name]"
            id="name"
            value="<?php echo $enquiry['name'] ?? null ?>"

        />

        <label for="email">Email</label>
        <input
            type="email"
            name="enquiry[email]"
            id="email"
            value="<?php echo htmlspecialchars($enquiry['email'] ?? null, ENT_QUOTES, 'UTF-8'); ?>"

        />

        <label for="phone">Phone Number</label>
        <input
            type="tel"
            name="enquiry[tel_no]"
            id="phone"
            value="<?php echo htmlspecialchars($enquiry['tel_no'] ?? null, ENT_QUOTES, 'UTF-8'); ?>"
            pattern="\+[0-9]{12}$"

        />

        <label for="message">Message</label>
        <textarea
            name="enquiry[enquiry]"
            id="message"
            rows="5"

        ><?php echo nl2br(htmlspecialchars($enquiry['enquiry'] ?? null, ENT_QUOTES, 'UTF-8')); ?></textarea>

        <input type="submit" name="submit" value="Submit" />
    </form>
</main>
