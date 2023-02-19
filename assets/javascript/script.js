$("#frm").validate({
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

document.onreadystatechange = function () {
  if (document.readyState !== "complete") {
    document.querySelector(
      "body").style.visibility = "hidden";
    document.querySelector(
      "#loader").style.visibility = "visible";
  } else {
    document.querySelector(
      "#loader").style.display = "none";
    document.querySelector(
      "body").style.visibility = "visible";
  }
};

$(document).ready(function () {
  $("#sendResetLinkBtn").click(function () {
    if ($(".error").text() != "") {
      $("#loader").show();
    }
  });
});

document.getElementById("firstname").value = getSavedValue("firstname");
document.getElementById("lastname").value = getSavedValue("lastname");
document.getElementById("largeTextArea").value = getSavedValue("largeTextArea");
document.getElementById("phone").value = getSavedValue("phone");

function saveValue(e) {
  var id = e.id; 
  var val = e.value; 
  localStorage.setItem(id, val); 
}

function getSavedValue(v) {
  if (!localStorage.getItem(v)) {
    return "";
  }
  return localStorage.getItem(v);
}

$("#logoutBtn").click(function () {
  localStorage.clear();
});

let digitValidate = function(ele){
  console.log(ele.value);
  ele.value = ele.value.replace(/[^0-9]/g,'');
}

let tabChange = function(val){
    let ele = document.querySelectorAll('input');
    if(ele[val-1].value != ''){
      ele[val].focus()
    }else if(ele[val-1].value == ''){
      ele[val-2].focus()
    }   
 }