<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet"/>

<div class="bodyuser">
    <div class="container">
        <button class="btn" id="btn">
            Users
            <i class="bx bx-chevron-down" id="arrow"></i>
        </button>

        <div class="dropdown" id="dropdown">
            <a href="#create">
                <i class="bx bx-plus-circle"></i>
                Create New
            </a>
            <a href="#draft">
                <i class="bx bx-book"></i>
                All Drafts
            </a>
            <a href="#move">
                <i class="bx bx-folder"></i>
                Move To
            </a>
            <a href="#profile">
                <i class="bx bx-user"></i>
                Profile Settings
            </a>
            <a href="#notification">
                <i class="bx bx-bell"></i>
                Notification
            </a>
            <a href="#settings">
                <i class="bx bx-cog"></i>
                Settings
            </a>
        </div>
    </div>
</div>

<style>
    @import url(https://fonts.googleapis.com/css?family=Inter:100,200,300,regular,500,600,700,800,900);

   .bodyuser * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Inter", sans-serif;
        --shadow: rgba(0, 0, 0, 0.05) 0px 6px 10px 0px,
        rgba(0, 0, 0, 0.1) 0px 0px 0px 1px;
        --color: #166e67;
        --gap: 0.5rem;
        --radius: 5px;
    }

    .bodyuser body{
        margin: 2rem;
        background-color: #b3e6f4;
        font-size: 0.9rem;
        color: black;
    }

    .btn {
        background-color: white;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        column-gap: var(--gap);
        padding: 0.6rem;
        cursor: pointer;
        border-radius: var(--radius);
        border: none;
        box-shadow: var(--shadow);
        position: relative;
    }

    .bx {
        font-size: 1.1rem;
    }

    .dropdown {
        position: absolute;
        width: 250px;
        box-shadow: var(--shadow);
        border-radius: var(--radius);
        margin-top: 0.3rem;
        background: white;

        visibility: hidden;
        opacity: 0;
        transform: translateY(0.5rem);
        transition: all 0.1s cubic-bezier(0.16, 1, 0.5, 1);
    }

    .dropdown a {
        display: flex;
        align-items: center;
        column-gap: var(--gap);
        padding: 0.8rem 1rem;
        text-decoration: none;
        color: black;
    }

    .dropdown a:hover {
        background-color: var(--color);
        color: white;
    }

    .show {
        visibility: visible;
        opacity: 1;
        transform: translateY(0rem);
    }

    .arrow {
        transform: rotate(180deg);
        transition: 0.2s ease;
    }
</style>

<script scoped>
    const dropdownBtn = document.getElementById("btn");
    const dropdownMenu = document.getElementById("dropdown");
    const toggleArrow = document.getElementById("arrow");

    // Toggle dropdown function
    const toggleDropdown = function () {
        dropdownMenu.classList.toggle("show");
        toggleArrow.classList.toggle("arrow");
    };

    // Toggle dropdown open/close when dropdown button is clicked
    dropdownBtn.addEventListener("click", function (e) {
        e.stopPropagation();
        toggleDropdown();
    });

    // Close dropdown when dom element is clicked
    document.documentElement.addEventListener("click", function () {
        if (dropdownMenu.classList.contains("show")) {
            toggleDropdown();
        }
    });
</script>
