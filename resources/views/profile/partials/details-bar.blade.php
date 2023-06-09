<nav>
    <ul class="drop-down closed">
        <li><a href="{{back()}}" class="nav-button">Details</a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Library</a></li>
        <li><a href="#">Contact</a></li>
    </ul>
</nav>
<style>

    html, body { background: #1abc9c; }
    nav {margin-top: 40px; }


    /* Drop Down Styles
    ================================ */
    nav .drop-down {
        list-style: none;
        overflow: hidden; /* When ul height is reduced, ensure overflowing li are not shown */
        height: 172px; /* 172px = (38 (li) + 5 (li border)) * 4 (number of li) */
        background-color: #34495e;
        font-family: Arial, serif;
        width: 200px;
        margin: 0 auto;
        padding: 0;
        text-align: center;
        -webkit-transition: height 0.3s ease;
        transition: height 0.3s ease;
    }

    nav .drop-down.closed {
        /*  When toggled via jQuery this class will reduce the height of the ul which inconjuction
            with overflow: hidden set on the ul will hide all list items apart from the first */
        /* current li height 38px + 5px border */
        height: 43px;
    }

    nav .drop-down li {
        border-bottom: 5px solid #2c3e50;
    }

    nav .drop-down li a {
        display: block;
        color: #ecf0f1;
        text-decoration: none;
        padding: 10px; /* Larger touch target area */
    }

    nav .drop-down li:first-child a:after {
        content: "\25BC";
        float: right;
        margin-left: -30px; /* Excessive -margin to bring link text back to center */
        margin-right: 5px;
    }
</style>
<script>
    (function() {
        // Bind Click event to the drop down navigation button
        document.querySelector('.nav-button').addEventListener('click', function() {
            /*  Toggle the CSS closed class which reduces the height of the UL thus
                hiding all LI apart from the first */
            this.parentNode.parentNode.classList.toggle('closed')
        }, false);
    })();
</script>
