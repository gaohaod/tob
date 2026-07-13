@section('title', 'Render Asset')
<x-app-layout>
  <div class="container mt-5">
    <x-card :title="'render asset'">
      <p class="fw-regular text-muted">Re-render an asset. This action isn't destructive and you can't get punished in any way for it.</p>
      <div class="form-group mb-2">
        <label for="assetType">Type</label>
        <select id="assetType" name="type" class="form-control">
          <option value="shirt">Shirt</option>
          <option value="pants">Pants</option>
          <option value="hat">Hat</option>
          <option value="gear">Gear</option>
          <option value="user">User</option>
          <option value="model">Model</option>
          <option value="head">Head</option>
          <option value="gear">Gear</option>
          <option value="place">Place</option>
          <option value="mesh">Mesh</option>
        </select>
      </div>

      <div class="form-group mb-2">
        <input type="text" name="assetIdToRender" class="form-control" id="assetIdToRender" placeholder="Asset id to render">
      </div>

      <button id="renderButton" onclick="disableButton(this)" class="btn btn-info">Render</button>

    </x-card>
  </div>
    <script>
document.getElementById('renderButton').addEventListener('click', function() {
    var assetType = document.getElementById('assetType').value;
    
    var assetId = document.getElementById('assetIdToRender').value;

    var url = `/app/admin/thumbnail/${assetType}/${assetId}`;

    fetch(url, {
        method: 'GET',
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();

    })
    .then(data => {
        console.log('Success:', data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
});
</script>

<script src="/functions.js"></script>

</x-app-layout>