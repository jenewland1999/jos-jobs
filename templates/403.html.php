<?php if ($isLoggedIn): ?>
    <h2>You're not permitted to view this page.</h2>

    <p>You do not have the required access level to view this restricted page. If you believe this is a mistake then please contact us via the enquiries page with the subject heading "Access Levels". Thank you.</p>
<?php else: ?>
    <h2>You're not logged in.</h2>

    <p>You must be logged in to view this page.</p>
    <p><a href="/id/login/">Login</a></p>
<?php endif; ?>
