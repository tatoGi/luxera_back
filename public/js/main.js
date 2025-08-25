
function uploadFile(input) {
    const file = input.files[0];
    const reader = new FileReader();

    reader.onload = function (e) {
        const uploadedImage = document.createElement('img');
        uploadedImage.src = e.target.result;
        uploadedImage.classList.add("w-32", "h-32", "object-cover", "rounded-lg");

        const deleteButton = document.createElement('button');
        deleteButton.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="fill-current text-red-700 w-4 h-4" viewBox="0 0 512 512">
                <path d="M310.6 361.4c12.5 12.5 12.5 32.75 0 45.25C304.4 412.9 296.2 416 288 416s-16.38-3.125-22.62-9.375L160 301.3L54.63 406.6C48.38 412.9 40.19 416 32 416S15.63 412.9 9.375 406.6c-12.5-12.5-12.5-32.75 0-45.25l105.4-105.4L9.375 150.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 210.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-105.4 105.4L310.6 361.4z" />
            </svg>
        `;
        deleteButton.classList.add("absolute", "top-2", "right-2", "p-1", "bg-white", "rounded-full", "shadow-md");
        deleteButton.onclick = function () {
            const container = document.getElementById("image-container");
            container.removeChild(uploadedImage);
            container.removeChild(deleteButton);
            input.value = ''; // Reset input value after deleting
            document.getElementById("upload-text").innerText = "Select a file"; // Reset label text
            // Also clear the hidden input value
            document.getElementById("icon-input").value = '';
        };

        const div = document.createElement('div');
        div.classList.add("relative", "mb-2");
        div.appendChild(uploadedImage);
        div.appendChild(deleteButton);

        const container = document.getElementById("image-container");
        container.appendChild(div);

        document.getElementById("upload-text").innerText = file.name;

        // Set the value of the hidden input to the file name
        document.getElementById("icon-input").value = file.name;
    };

    reader.readAsDataURL(file);
}

function deleteImage() {
    const container = document.getElementById("image-container");
    container.removeChild(container.querySelector('img'));
    container.removeChild(container.querySelector('button'));
}
const sidebar = document.querySelector("aside");
const maxSidebar = document.querySelector(".max");
const miniSidebar = document.querySelector(".mini");
const roundout = document.querySelector(".roundout");
const maxToolbar = document.querySelector(".max-toolbar");
const logo = document.querySelector('.logo');
const content = document.querySelector('.content');
const moon = document.querySelector(".moon");
const sun = document.querySelector(".sun");

// Function to set the initial state of the sidebar
function setInitialState() {
    // Open the sidebar by default
    sidebar.classList.remove("-translate-x-48");
    sidebar.classList.add("translate-x-none");
    maxSidebar.classList.remove("hidden");
    maxSidebar.classList.add("flex");
    miniSidebar.classList.remove("flex");
    miniSidebar.classList.add("hidden");
    maxToolbar.classList.add("translate-x-0");
    maxToolbar.classList.remove("translate-x-24", "scale-x-0");
    logo.classList.remove("ml-12");
    content.classList.remove("ml-12");
    content.classList.add("ml-12", "md:ml-60");
}

// Call the setInitialState function when the page loads
window.addEventListener('DOMContentLoaded', setInitialState);
function setDark(val){
        if(val === "dark"){
            document.documentElement.classList.add('dark')
            moon.classList.add("hidden")
            sun.classList.remove("hidden")
        }else{
            document.documentElement.classList.remove('dark')
            sun.classList.add("hidden")
            moon.classList.remove("hidden")
        }
    }
// Toggle function for opening and closing the sidebar
function openNav() {
    if (sidebar.classList.contains('-translate-x-48')) {
        // max sidebar 
        sidebar.classList.remove("-translate-x-48");
        sidebar.classList.add("translate-x-none");
        maxSidebar.classList.remove("hidden");
        maxSidebar.classList.add("flex");
        miniSidebar.classList.remove("flex");
        miniSidebar.classList.add("hidden");
        maxToolbar.classList.add("translate-x-0");
        maxToolbar.classList.remove("translate-x-24", "scale-x-0");
        logo.classList.remove("ml-12");
        content.classList.remove("ml-12", "md:ml-60");
        content.classList.add("ml-12", "md:ml-60");
    } else {
        // mini sidebar
        sidebar.classList.add("-translate-x-48");
        sidebar.classList.remove("translate-x-none");
        maxSidebar.classList.add("hidden");
        maxSidebar.classList.remove("flex");
        miniSidebar.classList.add("flex");
        miniSidebar.classList.remove("hidden");
        maxToolbar.classList.add("translate-x-24", "scale-x-0");
        maxToolbar.classList.remove("translate-x-0");
        logo.classList.add('ml-12');
        content.classList.remove("ml-12", "md:ml-60");
        content.classList.add("ml-12");
    }
}
   // JavaScript to toggle dropdown menus
   document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.list-child').forEach(function (toggle) {
        toggle.addEventListener('click', function () {
            var menu = this.querySelector('.absolute');
            menu.classList.toggle('hidden');
        });
    });
});
// $(document).ready(function() {
//     $('.select2').select2({
//         placeholder: 'Choose a Category',
//         allowClear: true // Optionally enable the "Clear" button
//     });
// });
$(document).ready(function() {
    $('.delete-image').on('click', function(e) {
        e.preventDefault(); // Prevent the default form submission behavior
        var $deleteButton = $(this); // Cache the delete button element
        var id = $deleteButton.data("id");
        var Url = $deleteButton.data('route');
        var TOKEN = $deleteButton.data("token");

        if (confirm("დოკუმენტის წაშლა!?")) {
            $.ajax({
                url: Url,
                method: 'DELETE',
                data: {
                    _token: TOKEN,
                },
                success: function(response) {
                    // Find the parent div with class trash and remove it from the DOM
                    $deleteButton.closest('.trash').remove();
                },
                error: function(error) {
                    console.error('Error deleting image:', error);
                }
            });
        }
    });
});

$(document).ready(function() {
    $('.delete-icon').on('click', function(e) {
        e.preventDefault(); 
        var Url = $(this).data('route');
        var TOKEN = $(this).data("token");

        if (confirm("Are you sure you want to delete this category icon?")) {
            $.ajax({
                url: Url,
                method: 'DELETE',
                data: {
                    _token: TOKEN,
                },
                success: function(response) {
                    // Handle success, e.g., hide parent div
                    $('#image-container').hide(); // Hide the parent div
                },
                error: function(error) {
                    console.error('Error deleting category icon:', error);
                }
            });
        }
    });
});
let tabTogglers = document.querySelectorAll("#tabs a");
let tabContents = document.querySelectorAll("#tab-contents > div");

tabTogglers.forEach(function(toggler) {
    toggler.addEventListener("click", function(e) {
        e.preventDefault();
        
        let tabName = this.getAttribute("href").substring(1);

        tabTogglers.forEach(function(tabToggler) {
            tabToggler.parentElement.classList.remove("border-t", "border-r", "border-l", "-mb-px", "bg-white");
        });
        
        tabContents.forEach(function(tabContent) {
            tabContent.classList.add("hidden");
            if (tabContent.id === tabName) {
                tabContent.classList.remove("hidden");
            }
        });

        this.parentElement.classList.add("border-t", "border-r", "border-l", "-mb-px", "bg-white");
    });
});
$(document).ready(function() {
    // Initial positioning of the language selector
    adjustLanguageSelectorPosition();

    // Adjust the position of the language selector when scrolling
    $(window).scroll(function() {
        adjustLanguageSelectorPosition();
    });

    function adjustLanguageSelectorPosition() {
        var languageSelector = $(".language-selector");
        var languageSelectorHeight = languageSelector.outerHeight();
        var scrollTop = $(window).scrollTop();
        var windowHeight = $(window).height();
        var languageSelectorTop = windowHeight * 0.3 - languageSelectorHeight / 2 + scrollTop;

        // Set the top position of the language selector
        languageSelector.css("top", languageSelectorTop + "px");
    }
});
$(document).ready(function () {
    // Initialize Nestable
    $('.dd').nestable({
        maxDepth: 10
    });

    // Handle update event
    $('.dd').on('change', function() {
        // Serialize the structure
        var orderArr = $('.dd').nestable('serialize');

        // Get CSRF token from the meta tag
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        // Get the locale from the data attribute
        var url = $('#nestable').data('route');

        // Construct the AJAX request URL with the locale
        var url = url;

        // Send an AJAX request to save changes
        $.ajax({
            url: url,
            type: "POST",
            data: {
                orderArr: orderArr,
                '_token': csrfToken
            },
            success: function(data) {
                // Optionally, you can handle the response from the server here
            }
        });
    });

    // Prevent propagation of mousedown event for .glyphicon elements
    $('.glyphicon').mousedown(function (e) {
        e.stopPropagation();
    });
});

