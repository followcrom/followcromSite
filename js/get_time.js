document.addEventListener("DOMContentLoaded", function () {
  var greeting = "";
  var timeOfDay = new Date().getHours();

  if (timeOfDay >= 6 && timeOfDay < 12) {
    greeting = "Good morning";
  } else if (timeOfDay >= 12 && timeOfDay < 19) {
    greeting = "Good afternoon";
  } else if (timeOfDay >= 19 && timeOfDay < 24) {
    greeting = "Good evening";
  } else {
    greeting = "Hi Night Owl";
  }

  document.getElementById("timeGreeting").textContent = greeting;
});
