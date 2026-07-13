@section('title', 'Create New Invite')

<div class="modal fade" id="finishedModal" tabindex="-1" aria-labelledby="finishedModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="finishedModalLabel">Successfully generated a key</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        You can now give it out.<br>
        <code id="keyhere"></code>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<x-app-layout>
    <div class="container mt-5">
        <x-card :title="'create new invite key'">
            <p class="text-muted">Please be careful with who you invite.</p>
            <button class="btn btn-success" onclick="generateKey()"><i class="far fa-key mr-2"></i> create invite key</button>

        </x-card>
    </div>


<script>
    async function generateKey() {
        let invite = document.getElementById('invkeyfinal');

        const response = await fetch("/app/admin/generateKey");

        const pp = await response.json();

        keyhere.innerHTML = pp.key; 

        $('#finishedModal').modal('show')

    }
</script>
</x-app-layout>
