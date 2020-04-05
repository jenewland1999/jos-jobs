<main class="sidebar">
    <?php include __DIR__ . '/../navigation.html.php'; ?>
    <section class="right">
        <h2>Enquiries</h2>

        <table>
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Tel No.</th>
                    <th scope="col">Assignee</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($enquiries as $enquiry): ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($enquiry->name, ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($enquiry->email, ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($enquiry->tel_no, ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <td>
                            <?php echo !empty($enquiry->user_id) ? $enquiry->getUser()->getSanitisedName() : null ?>
                        </td>
                        <td>
                            <?php echo $enquiry->is_complete ? 'Complete' : 'Incomplete'; ?>
                        </td>
                        <td>
                            <a href="/admin/enquiries/enquiry?id=<?php echo $enquiry->enquiry_id ?>">
                                View
                            </a>

                            <?php if ($authUser->hasPermission(\JosJobs\Entity\User::PERM_COMPLETE_ENQUIRIES)): ?>
                                <form action="/admin/enquiries/complete" method="post">
                                    <input type="hidden" name="enquiry_id" value="<?php echo $enquiry->enquiry_id; ?>" />
                                    <input type="submit" name="submit" value="<?php echo $enquiry->is_complete ? 'Mark as Incomplete' : 'Mark as Complete' ?>" />
                                </form>
                            <?php endif; ?>

                            <?php if ($authUser->hasPermission(\JosJobs\Entity\User::PERM_DELETE_ENQUIRIES)): ?>
                                <form action="/admin/enquiries/delete" method="post">
                                    <input type="hidden" name="enquiry_id" value="<?php echo $enquiry->enquiry_id; ?>" />
                                    <input type="submit" name="submit" value="Delete" />
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>
