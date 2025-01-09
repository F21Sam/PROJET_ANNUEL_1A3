function filterAnimals() {
    const searchInput = document.getElementById('search-input').value.toLowerCase();
    const speciesSelect = document.getElementById('species-select').value;
    const animalsContainer = document.getElementById('animals-container');
    const animals = animalsContainer.getElementsByClassName('profil-animal');

    Array.from(animals).forEach(animal => {
        const name = animal.dataset.name.toLowerCase();
        const species = animal.dataset.species;

        const matchesName = name.includes(searchInput);
        const matchesSpecies = speciesSelect === 'Toutes' || species === speciesSelect;

        if (matchesName && matchesSpecies) {
            animal.style.display = 'block';
        } else {
            animal.style.display = 'none';
        }
    });
}

function resetFilter() {
    document.getElementById('search-input').value = '';
    document.getElementById('species-select').value = 'Toutes';
    filterAnimals();
}
