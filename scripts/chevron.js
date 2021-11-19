// Down position
var down = true;

// Page loads
window.onload = () => {
  // Set src initially
  document.getElementById('chevron').src = down ? "./Resources/assets/chevron_down.svg" : "./Resources/assets/chevron_right.svg"
  
  // On click handler
  document.getElementById('chevron').onclick = () => {
    // Toggle down
    down = !down;
    // Change src depending on down bool
    document.getElementById('chevron').src = down ? "./Resources/assets/chevron_down.svg" : "./Resources/assets/chevron_right.svg"
  }
}