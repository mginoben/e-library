// Show borrow alert
var showModal = new bootstrap.Modal(document.querySelector(".show_modal"), {});
showModal.toggle();

// Function to copy ISBN
function copy_data(containerid) {
  var range = document.createRange();
  range.selectNode(containerid); //changed here
  window.getSelection().removeAllRanges();
  window.getSelection().addRange(range);
  document.execCommand("copy");
  window.getSelection().removeAllRanges();
  alert("ISBN copied");
}
