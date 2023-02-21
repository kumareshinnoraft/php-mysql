$("#userDetails").validate({
  rules: {
    username: {
      required: true,
      minlength: 2,
      maxlength: 20
    },
    password: {
      required: true,
      minlength: 5,
      maxlength: 15
    }
  }, message: {

    username: "Please enter the username",
    email: "Please enter a valid email",
    password: "Email must have at least 5 character"
  }
});

$("#userDetails").validate({
  rules: {
    firstname: {
      required: true,
      minlength: 2,
      maxlength: 20
    },
    lastname: {
      required: true,
      minlength: 2,
      maxlength: 20
    },
    image: {
      required: true,
    },
    marksArea: {
      required: true,
      minlength: 5,
      maxlength: 100
    },
    phNum: {
      required: true,
      minlength: 10,
      maxlength: 10
    },
  }, message: {
    firstname: "Please enter the firstname",
    lastname: "Please enter the Lastname",
    email: "Please enter a valid email",
    image: "Please select an image",
    marksArea: "Enter yours marks",
    phNum: "Enter your phone number",
    email: "Enter your valid email"
  }
});


$("#userDetails").on("submit", function () {
  
  if(document.getElementsByClassName("error") == null){
    document.getElementById('loader').style.visibility = "visiable";
  }
});

$("#logoutBtn").click(function () {
  localStorage.clear();
});

document.onreadystatechange = function () {
  var state = document.readyState
  if (state == 'interactive') {
    document.getElementById('loader').style.visibility = "visible";
  } else if (state == 'complete') {
    setTimeout(function () {
      document.getElementById('loader').style.visibility = "hidden";
    }, 1000);
  }
}

let digitValidate = function (element) {
  element.value = element.value.replace(/[^0-9]/g, '');
}

let tabChange = function (value) {

  let element = document.querySelectorAll('input');

  if (element[value - 1].value != '') {

    element[value].focus()

  } else if (ele[val - 1].value == '') {

    element[value - 2].focus()
  }
}
