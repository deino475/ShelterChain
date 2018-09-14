<div class = "container">
  <div class = "card">
    <div class = "container">
      <div class = "row">
        <h3 class = "text-xs-center">Shelter Data</h3>
      </div>
      <div class = "row">
        <div class = " container table-responsive">
          <table class = "table">
            <thead>
              <tr>
                <th scope="col">Name</th>
                <th scope="col">Address</th>
                <th scope="col">City</th>
                <th scope="col">State</th>
                <th scope="col">Zip</th>
                <th scope="col">Pets Allowed</th>
                <th scope="col">ADA Compliant</th>
                <th scope="col">Shelter Open?</th>
                <th scope="col">Latitude</th>
                <th scope="col">Longitude</th>
                <th scope="col">Edit</th>
              </tr>
            </thead>
            <tbody id = "shelters-info">
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<div class = "container" style="margin-top: 50px;">
  <div class = "card">
    <div class = "container" style = "padding: 10px;">
      <div class = "row">
        <h3 style = "margin-left: 32%;">Shelter-Chain Node Dashboard</h3>
      </div>
      <hr>
      <div class = "row">
        <div class = "col-md-12">
          <div class = "form-group">
            <label for="org_name">Organization Name</label>
            <input type = "text" class = "form-control" id = "org_name" placeholder="Organization Name">
          </div>
        </div>
      </div>

      <div class = "row">
        <div class = "col-md-12">
          <div class = "form-group">
            <label for="street_name">Street Name</label>
            <input type = "text" class = "form-control" id = "street_name" placeholder="Street Name">
          </div>
        </div>
      </div>

      <div class = "row">
        <div class = "col-md-4">
          <div class = "form-group">
            <label for="city_name">City Name</label>
            <input type = "text" class = "form-control" id = "city_name" placeholder="City Name">
          </div>
        </div>
        <div class = "col-md-4">
          <div class = "form-group">
            <label for="state_name">State Name</label>
            <input type = "text" class = "form-control" id = "state_name" placeholder="State Name">
          </div>
        </div>
        <div class = "col-md-4">
          <div class = "form-group">
            <label for="zip_code">Zip Code</label>
            <input type = "text" class = "form-control" id = "zip_code" placeholder="Zip Code">
          </div>
        </div>
      </div>

      <div class = "row">
        <div class = "col-md-4">
          <div class = "form-group">
            <label for="pets">Pets Allowed?</label>
            <select class = "form-control" id = "pets">
              <option value = "---">Select an Option</option>
              <option value = "Yes">Yes</option>
              <option value = "No">No</option>
            </select>
          </div>
        </div>
        <div class = "col-md-4">
          <div class = "form-group">
            <label for="ada">ADA Compliant?</label>
            <select class = "form-control" id = "ada">
              <option value = "---">Select an Option</option>
              <option value = "Yes">Yes</option>
              <option value = "No">No</option>
            </select>
          </div>
        </div>
        <div class = "col-md-4">
          <div class = "form-group">
            <label for="available">Shelter Open?</label>
            <select class = "form-control" id = "available">
              <option value = "---">Select an Option</option>
              <option value = "Yes">Yes</option>
              <option value = "No">No</option>
            </select>
          </div>
        </div>
      </div>

      <div class = "row">
        <div class = "col-md-6">
          <div class = "form-group">
            <label for="latitude">Latitude</label>
            <input type = "text" class = "form-control" id = "latitude" placeholder="Organization Name">
          </div>
        </div>
        <div class = "col-md-6">
          <div class = "form-group">
            <label for="longitude">Longitude</label>
            <input type = "text" class = "form-control" id = "longitude" placeholder="Organization Name">
          </div>
        </div>
      </div>

      <div class = "row">
        <div class = "container text-center">
          <button class = "btn btn-primary" id = "clear_data">Clear Data</button>
          <button class="btn btn-primary" id = "mine_a_block">Submit</button>
        </div>
      </div>
    </div>
  </div>
</div>
<br>
<div class = "container">
  <ul class = "list-group">
    <li class = "list-group-item">
      <span style = "float: left;">Add Peer</span>
      <input style = "float: left;" type = "text" class = "form-control" id = "peer" placeholder="Insert URL of Peer Here"> 
      <button class = "btn btn-primary" id = "add-peer" style = "float:right;">Add Peer</button>
    </li>
  </ul>
</div>
<script type="text/javascript">
  var data;
  document.getElementById('mine_a_block').addEventListener('click', function(){
    var xhttp = new XMLHttpRequest();
    var org_name = document.getElementById('org_name').value;
    var street_name = document.getElementById('street_name').value;
    var city_name = document.getElementById('city_name').value;
    var state_name = document.getElementById('state_name').value;
    var zip_code = document.getElementById('zip_code').value;
    var pets = document.getElementById('pets').value;
    var ada = document.getElementById('ada').value;
    var lat = document.getElementById('latitude').value;
    var lng = document.getElementById('longitude').value;
    var available = document.getElementById('available').value;

    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        alert(this.responseText);
      }
    }
    xhttp.open("POST", "?r=/mine", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(
      "org_name=" + org_name 
      + "&street_name=" + street_name 
      + "&city_name=" + city_name
      + "&state_name=" + state_name 
      + "&zip_code=" + zip_code
      + "&pets=" + pets
      + "&ada=" + ada
      + "&available=" + available
      + "&lat=" + lat
      + "&lng=" + lng );
    load_shelter_data();
  });

  document.getElementById('clear_data').addEventListener('click', function(){
    document.getElementById('org_name').value = "";
    document.getElementById('street_name').value = "";
    document.getElementById('city_name').value = "";
    document.getElementById('state_name').value = "";
    document.getElementById('zip_code').value = "";
    document.getElementById('pets').value = "Yes";
    document.getElementById('available').value = "Yes" ;
    document.getElementById('ada').value =  "Yes";
    document.getElementById('latitude').value = "";
    document.getElementById('longitude').value = "";
  });

  document.getElementById('add-peer').addEventListener('click',function(){
    var xhttp = new XMLHttpRequest();
    var peer_url = document.getElementById('peer').value;
    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        alert(this.responseText);
      }
    }
    xhttp.open("POST", "?r=/join-peer", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(
      "peer=" + peer_url 
    );
  });

  function load_shelter_data() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        data = JSON.parse(this.responseText);
        display_shelter_data();
      }
    }
    xhttp.open("POST", "?r=/last-block/data", true);
    xhttp.send();
  }

  function display_shelter_data() {
    var shelter_data_display_area = document.getElementById('shelters-info');
    shelter_data_display_area.innerHTML = "";
    var string_to_append = "";
    for (var shelter_info in data) {
      if (data[shelter_info][6] == 'Yes' ) {
        string_to_append += '<tr class = "table-success">';
      }
      else {
        string_to_append += '<tr class = "table-danger">';
      }
      string_to_append += '<td>' + data[shelter_info][0] + '</td>' +
      '<td>' + data[shelter_info][1] + '</td>' +
      '<td>' + data[shelter_info][2] + '</td>' +
      '<td>' + data[shelter_info][3] + '</td>' +
      '<td>' + data[shelter_info][4] + '</td>' +
      '<td>' + data[shelter_info][5] + '</td>' +
      '<td>' + data[shelter_info][6] + '</td>' +
      '<td>' + data[shelter_info][7] + '</td>' +
      '<td>' + data[shelter_info][8] + '</td>' +
      '<td>' + data[shelter_info][9] + '</td>' +
      '<td><button class = "btn btn-primary" onclick=\'update_data("' +shelter_info+ '")\'>Edit</button></td>' +
      '<tr>';
    }
    shelter_data_display_area.innerHTML = string_to_append;
  }

  function update_data(shelter_id) {
    document.getElementById('org_name').value = data[shelter_id][0];
    document.getElementById('street_name').value = data[shelter_id][1];
    document.getElementById('city_name').value = data[shelter_id][2];
    document.getElementById('state_name').value = data[shelter_id][3];
    document.getElementById('zip_code').value = data[shelter_id][4];
    document.getElementById('pets').value = data[shelter_id][5];
    document.getElementById('available').value = data[shelter_id][6];
    document.getElementById('ada').value = data[shelter_id][7];
    document.getElementById('latitude').value = data[shelter_id][8];
    document.getElementById('longitude').value = data[shelter_id][9];
  }

  load_shelter_data();
</script>