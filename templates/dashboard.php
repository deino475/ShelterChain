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
        <button class="btn btn-primary" style = "margin-left: 45%;"  id = "mine_a_block">Submit</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  document.getElementById('mine_a_block').addEventListener('click', mine_block);
  function mine_block() {
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
  }
</script>