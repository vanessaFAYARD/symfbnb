$('#add-image').click(function(){
    // numéro des futurs champs à créer
    const index = +$('#widgets-counter').val();

    // prototype des entrées
    const tmpl = $('#annonce_form_images').data('prototype').replace(/__name__/g, index);
    //console.log(tmpl);

    //j'injecte le code dans la div
    $('#annonce_form_images').append(tmpl);

    $('#widgets-counter').val(index + 1);

    //gestion bouton suppression
    handleDeleteButtons();
});

function handleDeleteButtons() {
    $('button[data-action="delete"]').click(function(){
        const target = this.dataset.target;
        //console.log(target);
        $(target).remove();
    });
}

function updateCounter() {
    const count = +$('#annonce_form_images div.form-group').length;
    $('#widgets-counter').val(count);
}

updateCounter();
handleDeleteButtons();
