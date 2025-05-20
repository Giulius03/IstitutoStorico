<?php if ($templateParams["action"] == "D"): ?>
<div class="modal fade" id="confirmElimination" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalTitle">Conferma eliminazione</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?php echo $templateParams['modalText'] ?> Proseguire?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                <input class="btn btn-danger" type="submit" value="Elimina" />
            </div>
        </div>
    </div>
</div>
<?php endif; ?>