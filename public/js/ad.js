/** @format */

// je recupere le numero des futurs champs que je vais créer

$("#add-image").click(function () {
  let index = +$("#widgets-counter").val();

  console.log(index);
  // je recupere le prototype des entrees

  const tmpl = $("#ad_images")
    .data("prototype")
    .replace(/__name__/g, index);

  // console.log(tmpl)

  // j'injecte ce code au sein de la div
  $("#ad_images").append(tmpl);

  $("#widgets-counter").val(index + 1);

  // je gere le boutton supprimer
  handleDeletebuttons();
});

function handleDeletebuttons() {
  $('button[data-action="delete"]').click(function () {
    // rappel en javascript le this correspond a l evenement du scope de la fonction soit ici le click sur le button
    const target = this.dataset.target;

    $(target).remove();
  });
}

function updateCounter() {
  let count = +$("#ad_images div.form-group").length;

  $("widgets-counter").val(count);
}

updateCounter();

// ici je rerajoute le handledeletebutton car sur des futures pages il y ejn aura de créer il faudra donc que cette option soit dispobible pour les photos entendues par default
handleDeletebuttons();
