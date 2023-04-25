 let arr = [];
 arr[0] = "Type Here..."
 arr[1] = "Enter Number here..."
 document.getElementById("charField").setAttribute("placeholder", arr[0]);
 document.getElementById("hours").setAttribute("placeholder", arr[1]);
 let charName = document.getElementById("charField");
 let numHours = document.getElementById("hours");
 let cost = document.getElementById("price");
 charName.addEventListener("focusout", nameCorrect);
 numHours.addEventListener("focusout", hoursCorrect);
 cost.addEventListener("click", calc);

 function nameCorrect() {
  let char = charName.value;
  let charError = document.getElementById("charFieldError");
  if(char == "" || char.length < 4) {
    charError.classList.remove("display-none");
    return false;
  } else {
    charError.classList.add("display-none");
    return true;
  }
 }
 
 function hoursCorrect() {
  let num = numHours.value;
  let numError = document.getElementById("hoursError");
  if(num == "" || num < 1) {
    numError.classList.remove("display-none");
    return false;
  } else {
    numError.classList.add("display-none");
    return true;
  }
 }

function calc() {
  let name = charName.value;
  let time = numHours.value;
  let display = document.getElementById("totalDisplay");
  let elguapoChecked = document.getElementById("elguapo").checked;
  let kupaChecked = document.getElementById("kupa").checked;
  let bothChecked = document.getElementById("both").checked;
  let rate = 1000;
  let total = 0;
  if(nameCorrect() && hoursCorrect()) {
    if(elguapoChecked || kupaChecked) {
      total = rate * time;
      display.innerHTML = name + " owes " + total + " platinum pieces for power leveling service";
      display.classList.remove("display-none");
    } else if(bothChecked) {
      rate = 1500;
      total = rate * time;
      display.innerHTML = name + " owes " + total + " platinum pieces for power leveling service";
      display.classList.remove("display-none");
    } else {
      display.classList.add("display-none");
    }
  }
    
}