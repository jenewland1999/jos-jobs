<style>
    h1, h2, h3, h4, h5, h6 {
        margin-bottom: 0.5em;
    }

    p {
        margin-bottom: 1em;
    }

    dd {
        margin-bottom: 0.5em;
    }

    dt {
        margin-bottom: 0.25em;
    }

    form select, form label, form input, form textarea {
        display: inline-block;
        padding: 0;
        float: none;
        clear: none;
        width: auto;
        margin: 0;
        margin-bottom: 0.5rem;
    }

    input[type="submit"] {
        clear: none;
        margin-left: 0;
        width: auto;
    }
</style>

<main class="sidebar">
    <?php include __DIR__ . '/../navigation.html.php'; ?>
    <section class="right" style="display: grid; grid-template-columns: repeat(6, 1fr); grid-template-rows: min-content 1fr; grid-gap: 1rem;">
        <h2 style="grid-column: span 6;">Enquiry #<?php echo htmlspecialchars($enquiry->enquiry_id, ENT_QUOTES, 'UTF-8'); ?></h2>

        <div class="enquirers-details" style="grid-column: span 3;">
            <h3>Enquirer's Details</h3>
            <dl>
                <dt><strong>Name:</strong></dt>
                <dd><?php echo htmlspecialchars($enquiry->name, ENT_QUOTES, 'UTF-8'); ?></dd>
                <dt><strong>Email Address:</strong></dt>
                <dd>
                    <a href="mailto:<?php echo htmlspecialchars($enquiry->email, ENT_QUOTES, 'UTF-8'); ?>" target="_blank">
                        <?php echo htmlspecialchars($enquiry->email, ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                </dd>
                <dt><strong>Phone Number:</strong></dt>
                <dd>
                    <a href="tel:<?php echo htmlspecialchars($enquiry->tel_no, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($enquiry->tel_no, ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                </dd>
            </dl>
        </div>

        <div class="admin-details" style="grid-column: span 3;">
            <h3>Admin Details</h3>
            <dl>
                <dt><strong>Assignee:</strong></dt>
                <?php if (empty($enquiry->getUser())): ?>
                    <dd>Not currently assigned</dd>
                <?php else: ?>
                    <dd><?php echo $enquiry->getUser()->getSanitisedName() ?></dd>
                <?php endif; ?>

                <dt><strong>Status:</strong></dt>
                <dd><?php echo $enquiry->is_complete ? 'Complete' : 'Incomplete'; ?></dd>
            </dl>
        </div>

        <div class="enquiry-body" style="grid-column: span 6;">
            <h3>Enquiry Body</h3>
            <p>
                <?php echo nl2br(htmlspecialchars($enquiry->enquiry, ENT_QUOTES, 'UTF-8')); ?>
            </p>
        </div>

        <?php if ($authUser->hasPermission(\JosJobs\Entity\User::PERM_ASSIGN_ENQUIRIES)): ?>
            <form action="/admin/enquiries/assign" method="post" style="grid-column: span 3;">
                <label for="assignee">Assignee</label>
                <select name="user_id" id="assignee">
                    <option value="" selected>Please select an assignee...</option>
                    <?php foreach ($users as $user): ?>
                        <option
                            value="<?php echo $user->user_id ?>"
                            <?php echo $enquiry->user_id !== null && $user->user_id === $enquiry->user_id ? 'selected' : '' ?>
                        >
                            <?php echo $user->getSanitisedName(); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" name="enquiry_id" value="<?php echo $enquiry->enquiry_id; ?>" />
                <input type="submit" name="submit" value="Assign Enquiry" />
                <p><small>To un-assign the enquiry set it to 'Please select an assignee...' and press 'Assign Enquiry'.</small></p>
            </form>
        <?php endif; ?>

        <?php if ($authUser->hasPermission(\JosJobs\Entity\User::PERM_COMPLETE_ENQUIRIES)): ?>
            <form action="/admin/enquiries/complete" method="post" style="grid-column: span 2;">
                <input type="hidden" name="enquiry_id" value="<?php echo $enquiry->enquiry_id; ?>" />
                <input type="submit" name="submit" value="<?php echo $enquiry->is_complete ? 'Mark as Incomplete' : 'Mark as Complete' ?>" />
            </form>
        <?php endif; ?>

        <?php if ($authUser->hasPermission(\JosJobs\Entity\User::PERM_DELETE_ENQUIRIES)): ?>
            <form action="/admin/enquiries/delete" method="post" style="grid-column: span 1;">
                <input type="hidden" name="enquiry_id" value="<?php echo $enquiry->enquiry_id; ?>" />
                <input type="submit" name="submit" value="Delete" />
            </form>
        <?php endif; ?>
    </section>
</main>
