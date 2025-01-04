<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="#"
           class="nav-link"
           id="accounts"
           aria-current="page"
           onclick="goTo('?page=accounts','accounts', event)">
            ACCOUNTS
        </a>
    </li>
    <br>
    <li class="nav-item">
        <a href="#"
           class="nav-link"
           id="organizations"
           onclick="goTo('?page=organizations','organizations', event)">ORGANIZATIONS</a>
    </li>
    <br>
    <li class="nav-item">
        <a href="#"
           class="nav-link"
           id="courses"
           onclick="goTo('?page=courses','courses', event)">COURSES</a>
    </li>
    <br>
</ul>

<style>
    /* Default link style */
    .nav-link {
        color: #000; /* Black for inactive links */
    }

    /* Success green background for active links */
    .nav-link.active {
        background-color: #198754 !important; /* Bootstrap success green */
        color: #fff !important; /* White text for contrast */
    }

    /* Optional hover effect */
    .nav-link:hover {
        background-color: #145c32; /* Darker green on hover */
        color: #fff !important;
    }
</style>

<script>
    function goTo(endpoint, caller, e) {
        $('ul>li>a.active').removeClass('active'); // Remove active class
        $(`#${caller}`).addClass('active'); // Add active class to the clicked link
        window.location.href = endpoint; // Navigate to the new page
        e.preventDefault(); // Prevent default link behavior
        return false;
    }

    const url = new URL(window.location.href);
    const searchParams = url.searchParams;
    const page = searchParams.get('page');
    if (page) {
        $('ul>li>a.nav-link').removeClass('active'); // Reset active classes
        $(`#${page}`).addClass('active'); // Set active class for the current page
    }
</script>
