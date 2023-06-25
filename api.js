const endpoint = "http://localhost:8888/ProjectActivity/api/";

// Cookie Functions --------------------------------
function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(";").shift();
}

function getUser() {
  fetch(endpoint + "getuser.php?id=" + getCookie("user_id"))
    .then((response) => response.json())
    .then((data) => {
      document.querySelector("#name").innerHTML =
        data.user.firstname + " " + data.user.lastname;
      console.log(data);
    });
}

function checkSession() {
  const userIDCookie = getCookie("user_id");
  if (userIDCookie) {
    window.location.replace("home.html");
  }
}

function checkLoggedInStatus() {
  const userIDCookie = getCookie("user_id");
  console.log(userIDCookie);
  if (!userIDCookie) {
    window.location.replace("login.html");
  }
}

// Store form variables
try {
  const loginForm = document.querySelector("#loginForm");
  loginForm.addEventListener("submit", login);
} catch (e) {}

try {
  const registrationForm = document.querySelector("#registrationForm");
  registrationForm.addEventListener("submit", register);
} catch (e) {}

try {
  const logoutButton = document.querySelector("#logout");
  logoutButton.addEventListener("click", logout);
} catch (e) {}

try {
  const newPostButton = document.querySelector("#newpost_btn");
  newPostButton.addEventListener("click", newPost);
} catch (e) {}

// Post Functions
function newPost() {
  const postContent = document.querySelector("#newpost").value;
  fetch(endpoint + "newpost.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      content: postContent,
      user_id: getCookie("user_id"),
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      document.querySelector("#newpost").value = "";
      getPosts();
    });
}

function getPosts() {
  fetch(endpoint + "getposts.php")
    .then((response) => response.json())
    .then((data) => {
      let postHTML = "";
      data.forEach((post) => {
        postHTML += `
            <div class="card mt-4">
            <div class="card-body">
              <p class="fw-bold">${post.firstname} ${post.lastname}</p>
              <p>${post.content}</p>
            </div>
          </div>
            `;
      });
      document.querySelector("#newsfeed").innerHTML = postHTML;
    });
}

function login(event) {
  event.preventDefault();

  // get form data
  const email = document.querySelector("#email").value;
  const password = document.querySelector("#password").value;

  const formData = new FormData();
  formData.append("email", email);
  formData.append("password", password);

  fetch(endpoint + "login.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Store user session in a cookie
        document.cookie = `user_id=${data.user_id}; expires=Thu, 18 Dec 2099 12:00:00 GMT`;

        window.location.replace("home.html");
      } else {
        alert(data.message);
      }
    });
}

function register(event) {
  event.preventDefault();

  // get form data
  const email = document.querySelector("#email").value;
  const firstname = document.querySelector("#firstname").value;
  const lastname = document.querySelector("#lastname").value;
  const birthdate = document.querySelector("#birthdate").value;
  const password = document.querySelector("#password").value;
  const confirm_password = document.querySelector("#confirm_password").value;

  if (password === confirm_password) {
    const formData = new FormData();
    formData.append("email", email);
    formData.append("firstname", firstname);
    formData.append("lastname", lastname);
    formData.append("birthdate", birthdate);
    formData.append("password", password);

    fetch(endpoint + "register.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Registration successful!");
          window.location.replace("login.html");
        } else {
          alert("Email already exists!");
        }
      });
  } else {
    alert("Passwords do not match!");
  }
}

function logout() {
  fetch(endpoint + "logout.php")
    .then((response) => response.json())
    .then((data) => {
      alert(data.message);

      // Clear session cookies
      document.cookie = `user_id=''; expires=Thu, 18 Dec 1970 12:00:00 GMT`;

      window.location.replace("login.html");
    });
}
