console.log("This is script.js");

const header_side_menu = document.getElementById('header_side_menu'),
menu = document.getElementById('menu'),
message = document.getElementById('message');

menu.addEventListener('click', ()=>{
    console.log("toggles");
    header_side_menu.classList.toggle('d_none');
});

function remove_mess(){
    message.remove();
}
function remove_echo(){
    let elem = Array.from(document.getElementsByClassName("yellow"));
    elem.forEach(element => {
        element.classList.add("d_none");
    });
}


// Disappearing alert
if (message !== null) {
    setTimeout(() => {
        remove_mess();
        remove_echo();
    }, 5000);
}


// Click event for sign up button
const signup_popup = document.getElementById('signup_popup');

const sign_up_btn = document.getElementById('sign_up_btn');
sign_up_btn.addEventListener('click', (e)=>{
    signup_popup.classList.add('show_popup');
    login_popup.classList.remove('show_popup');
    e.preventDefault();
});
// cross event for sign up
const signup_cross_btn = document.getElementById('signup_cross_btn');
if(signup_cross_btn!==null){
    signup_cross_btn.addEventListener('click', ()=>{
        signup_popup.classList.remove('show_popup');
    });
}

// Click event for login button
const login_popup = document.getElementById('login_popup');

const login_in_btn = document.getElementById('login_in_btn');
login_in_btn.addEventListener('click', (e)=>{
    login_popup.classList.add('show_popup');
    signup_popup.classList.remove('show_popup');
    e.preventDefault();
});
// cross event for login
const login_cross_btn = document.getElementById('login_cross_btn');
login_cross_btn.addEventListener('click', (e)=>{
    login_popup.classList.remove('show_popup');
});


const nums = document.querySelectorAll(".nums"),
previous = document.querySelector("#previous"),
next = document.querySelector("#next");

// ~ Setting up initial step
let current_step = 0;

nums.forEach((element, index) => {
    element.addEventListener('click', (e)=>{
        // Set current step to index
        current_step = index;
        // Remove selected class from previously selected btn
        document.querySelector('.selected').classList.remove('selected');
        // Add the selected class to clicked btn
        element.classList.add('selected');
        e.preventDefault();
    })    
});

// Cross btn for more div
const more_popup = document.querySelector('.more_popup');
const more_cross_btn = document.querySelector('#more_cross_btn');
if(more_cross_btn!==null){
    more_cross_btn.addEventListener('click', ()=>{
        more_popup.classList.add('d_none');
    });
}

// Closing more div where clicked anywhere outside it
window.addEventListener('click', function(e){
    // Removing the element when it is present
    if(more_popup!==null){
        more_popup.classList.add('d_none');
    }
    
});

// Click here to login function
function click_here_login(){
    console.log("Clicked to show login div");
    login_popup.classList.add('show_popup');
    signup_popup.classList.remove('show_popup');
}
function click_here_signup(){
    console.log("Clicked to show signup div");
    signup_popup.classList.add('show_popup');
    login_popup.classList.remove('show_popup');
}

// Pagination
console.log("Fix the arrow keys");