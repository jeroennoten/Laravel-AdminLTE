<li class="nav-item">
    <a class="nav-link" id="darkmode-widget" href="#" role="button">
        <i class="far fa-moon"></i>
    </a>
</li>

{{-- Register Javascript utility class for this element --}}

@once
@push('js')
<script>
    const darkmode_widget = document.getElementById('darkmode-widget');
    const bodyEl = document.querySelector('body');
    const darkmode_icon = darkmode_widget.getElementsByTagName('i')[0];
    const darkMode = () => {
            bodyEl.classList.toggle('dark-mode')
        }
    const enableIconDarkMode = () => {
            darkmode_icon.classList.remove('far');
            darkmode_icon.classList.add('fas');
        }
    const disableIconDarkMode = () => {
            darkmode_icon.classList.remove('fas');
            darkmode_icon.classList.add('far');
        }

    darkmode_widget.addEventListener('click', function (e) { 
        // Get the value of the "dark" item from the local storage on every click
        setDarkMode = localStorage.getItem('dark-mode');
        if(setDarkMode !== "on") {
            darkMode();
            // Set the value of the itwm to "on" when dark mode is on
            setDarkMode = localStorage.setItem('dark-mode', 'on');
            enableIconDarkMode();
        } else {
            darkMode();
            // Set the value of the item to  "null" when dark mode if off
            setDarkMode = localStorage.setItem('dark-mode', null);
            disableIconDarkMode();
        }
    });

    // Get the value of the "dark" item from the local storage
    let setDarkMode = localStorage.getItem('dark-mode');

    // Check dark mode is on or off on page reload
    if(setDarkMode === 'on') {
        darkMode();
        enableIconDarkMode();
    }

</script>
@endpush
@endonce

