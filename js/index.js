function scrollToSection() {
    // Scroll to the section with ID "section2"
    document.getElementById('contact_us').scrollIntoView({ behavior: 'smooth' });
  }


  function submitReview() {
    var name = document.getElementById('nameInput').value;
    var phoneNumber = document.getElementById('phoneNumberInput').value;
    var email = document.getElementById('emailInput').value;
    var message = document.getElementById('messageInput').value;

    // Create an object to store the review data
    var formData = {
        name: name,
        phoneNumber: phoneNumber,
        email: email,
        message: message
    };

    // Send the review data to PHP using fetch or XMLHttpRequest
    fetch('receive_review.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.text())
    .then(data => {
        if (data === "success") {
            alert("Review submitted successfully!");
            // Redirect to index.php
            window.location.href = "index.php";
        } else {
            alert("Error: " + data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
