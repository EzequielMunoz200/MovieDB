const wrapForm = document.getElementById('movie_MovieActors');
const prototype = wrapForm.dataset.prototype;
let = currentIndex = wrapForm.children.length;

function handleAddActor(evt) {
    const newElt = document.createElement('div');
    newElt.innerHTML = prototype.replace(/__name__/g, currentIndex);
    currentIndex++;
    wrapForm.appendChild(newElt);
}

const btnAddActor = document.getElementById("add-actor");
btnAddActor.addEventListener('click', handleAddActor);