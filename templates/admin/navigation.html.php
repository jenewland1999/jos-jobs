<aside class="sidebar-navigation left">
    <ul class="sidebar-navigation__nav">
        <li class="sidebar-navigation__nav-item">
            <a href="/admin/" class="sidebar-navigation__nav-link">
                Overview
            </a>
        </li>
        <?php if ($authUser->hasPermission(\JosJobs\Entity\User::PERM_READ_CATEGORIES)): ?>
            <li class="sidebar-navigation__nav-item">
                <a href="/admin/categories" class="sidebar-navigation__nav-link">
                    Categories
                </a>
            </li>
        <?php endif; ?>
        <?php if ($authUser->hasPermission(\JosJobs\Entity\User::PERM_READ_ENQUIRIES)): ?>
            <li class="sidebar-navigation__nav-item">
                <a href="/admin/enquiries" class="sidebar-navigation__nav-link">
                    Enquiries
                </a>
            </li>
        <?php endif; ?>
        <?php if ($authUser->hasPermission(\JosJobs\Entity\User::PERM_READ_LOCATIONS)): ?>
            <li class="sidebar-navigation__nav-item">
                <a href="/admin/locations" class="sidebar-navigation__nav-link">
                    Locations
                </a>
            </li>
        <?php endif; ?>
        <li class="sidebar-navigation__nav-item">
            <a href="/admin/jobs" class="sidebar-navigation__nav-link">
                Jobs
            </a>
        </li>
        <?php if ($authUser->hasPermission(\JosJobs\Entity\User::PERM_READ_USERS)): ?>
            <li class="sidebar-navigation__nav-item">
                <a href="/admin/users" class="sidebar-navigation__nav-link">
                    Users
                </a>
            </li>
        <?php endif; ?>
    </ul>
</aside>
