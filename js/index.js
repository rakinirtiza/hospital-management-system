document.getElementById("find-a-doctor").addEventListener("click", function () {
    window.location.href = "php/user/findDoctors.php";
});

// document.getElementById("book-appointment-btn").addEventListener("click", function () {
//     window.location.href = "php/user/bookAppointment.php";
// });

document.getElementById("test-reports").addEventListener("click", function () {
    window.location.href = "php/user/testReport.php";
});

document.getElementById("have-a-query").addEventListener("click", function () {
    window.location.href = "php/user/contactUs.php";
});

document.getElementById("view-all-doctors").addEventListener("click", function () {
    window.location.href = "php/user/findDoctors.php";
});

document.addEventListener("click", e => {
    if (e.target.classList.contains("book-btn")) {
        const id = e.target.dataset.doctorId;
        const name = encodeURIComponent(e.target.dataset.doctorName);

        const isLoggedIn = document.body.getAttribute("data-loggedin") === "true";

        if (!isLoggedIn) {
            alert("Please login first to book an appointment.");
            window.location.href = "php/user/loginForm.php";
            return;
        }
        
        window.location.href = `php/user/bookAppointment.php?doctor_id=${id}&doctor_name=${name}`;
    }
});
