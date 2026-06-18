document.addEventListener("DOMContentLoaded", function () {
    const nameInput = document.getElementById("search_doctor_by_name");
    const deptSelect = document.getElementById("search_doctor_by_department");
    const doctorsList = document.getElementById("doctorsList");

    loadDoctors();

    nameInput.addEventListener("input", loadDoctors);
    deptSelect.addEventListener("change", loadDoctors);

    function loadDoctors() {
        const name = nameInput.value;
        const department_id = deptSelect.value;

        const xhr = new XMLHttpRequest();
        xhr.open("GET", `get_doctors.php?name=${encodeURIComponent(name)}&department_id=${encodeURIComponent(department_id)}`, true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                doctorsList.innerHTML = xhr.responseText;
            } else {
                doctorsList.innerHTML = "<p>Error loading doctors.</p>";
            }
        };
        xhr.send();
    }
});

document.addEventListener("click", function (e) {
    if (e.target.classList.contains("book-btn")) {
        const doctorId = e.target.getAttribute("data-doctor-id");
        const doctorName = e.target.getAttribute("data-doctor-name");

        const isLoggedIn = document.body.getAttribute("data-loggedin") === "true";

        if (!isLoggedIn) {
            alert("Please login first to book an appointment.");
            window.location.href = "loginForm.php";
            return;
        }

        window.location.href = `bookAppointment.php?doctor_id=${doctorId}&doctor_name=${encodeURIComponent(doctorName)}`;
    }
});
