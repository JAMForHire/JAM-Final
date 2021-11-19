// Get number id from string id
function get_id(id) {
  // Switch on the id string
  switch(id) {
    case 'jar_1':
      return 0;
      break;
    case 'jar_2':
      return 1;
      break;
    case 'jar_3':
      return 2;
      break;
    default:
      return null;
      break;
  }
}

// Generate modal text
function gen_modal(id) {
  // Get current id
  var curr_id = get_id(id);

  // If got valid id
  if(curr_id !== null) {
    // Open json file
    $.getJSON('jars.json').done((data) => {
      var jars = data.jars;
      let curr_jar = jars[curr_id];

      // Fill applicable parts
      document.getElementById('company').placeholder = curr_jar['company'];
      document.getElementById('date').placeholder = curr_jar['date'];
      document.getElementById('notes').placeholder = curr_jar['notes'];
      document.getElementById('app_link').placeholder = curr_jar['app_link'];
      document.getElementById('progress').value = curr_jar['progress'];
    });
  }

  // No valid id
  else {
    // Defaul placeholder texts for adding
    document.getElementById('company').placeholder = "Enter company name";
    document.getElementById('date').placeholder = "mm/dd/yy";
    document.getElementById('notes').placeholder = "Enter notes";
    document.getElementById('app_link').placeholder = "Enter app link";
    document.getElementById('progress').value = "";
  }
}