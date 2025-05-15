let numOfNotes = 0;

document.getElementById("btnAddNote").addEventListener('click', function(event) {
    showNewNoteFields();
});

async function showNewNoteFields() {
    const itemsContainer = document.getElementById("notesForms");
    const currentNote = `
    <fieldset class="form-floating mb-3 pt-1 border-top">
        <legend>Nuova Nota</legend>
        <div class="form-floating my-3">
            <input name="Testo${numOfNotes}" type="text" class="form-control" id="Testo${numOfNotes}" placeholder="Testo" required />
            <label for="Testo${numOfNotes}">Testo</label>
        </div>
        <div class="form-floating my-3">
            <input name="autore${numOfNotes}" type="text" class="form-control" id="autore${numOfNotes}" placeholder="autore" />
            <label for="autore${numOfNotes}">Autore Nota</label>
        </div>
        <div class="form-floating my-3">
            <input name="AncoraNota${numOfNotes}" type="text" class="form-control" id="AncoraNota${numOfNotes}" placeholder="AncoraNota" value="#" required />
            <label for="AncoraNota${numOfNotes}">Ancora Nota (DEVE iniziare con #!)</label>
        </div>
    </fieldset>`;
    if (!document.getElementById("nonote")) {
        itemsContainer.insertAdjacentHTML('afterbegin', currentNote);
    } else {
        itemsContainer.innerHTML = currentNote;
    }
    numOfNotes++;
    document.getElementById("numNote").value = numOfNotes;
}