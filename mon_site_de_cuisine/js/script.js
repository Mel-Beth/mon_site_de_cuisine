function validateAndRedirect() {
  // Récupérer les valeurs des champs
  var pseudo = document.querySelector('input[name="pseudo"]').value;
  var mdp = document.querySelector('input[name="mdp"]').value;

  // Vérifier si les champs sont vides
  if (pseudo === "" || mdp === "") {
    // Afficher un message d'erreur
    alert("Veuillez remplir tous les champs avant de vous connecter.");
    return false; // Empêche la redirection
  }

  // Si les champs sont remplis, soumettre le formulaire
  document.getElementById("loginForm").submit();
}

function redirectToRegister() {
  window.location.href = "register.php"; // Redirige vers la page d'inscription
}

document.getElementById('search-input').addEventListener('input', function() {
    const term = this.value;

    if (term.length > 0) { // Commence à chercher après 3 caractères
        fetch('suggestions.php?term=' + encodeURIComponent(term))
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau lors de la récupération des suggestions');
                }
                return response.json();
            })
            .then(data => {
                const suggestionsBox = document.getElementById('suggestions');
                suggestionsBox.innerHTML = ''; // Réinitialiser les suggestions
                suggestionsBox.classList.remove('show'); // Masquer les suggestions par défaut

                if (data.length > 0) {
                    data.forEach(item => {
                        const suggestionItem = document.createElement('div');
                        suggestionItem.textContent = item.nom;
                        suggestionItem.classList.add('suggestion-item');
                        suggestionItem.onclick = function() {
                            document.getElementById('search-input').value = item.nom; // Mettre le nom dans le champ
                            suggestionsBox.innerHTML = ''; // Réinitialiser les suggestions
                            suggestionsBox.classList.remove('show'); // Masquer les suggestions
                        };
                        suggestionsBox.appendChild(suggestionItem);
                    });
                    suggestionsBox.classList.add('show'); // Afficher les suggestions si des données sont présentes
                }
            })
            .catch(error => console.error('Erreur lors de la récupération des suggestions:', error));
    } else {
        document.getElementById('suggestions').innerHTML = ''; // Réinitialiser si moins de 3 caractères
        document.getElementById('suggestions').classList.remove('show'); // Masquer les suggestions
    }
});

// Fermer les suggestions lorsque l'utilisateur clique ailleurs
document.addEventListener('click', function(event) {
    const suggestionsBox = document.getElementById('suggestions');
    const searchInput = document.getElementById('search-input');

    // Vérifier si le clic a été effectué en dehors du champ de recherche et de la boîte de suggestions
    if (!searchInput.contains(event.target) && !suggestionsBox.contains(event.target)) {
        suggestionsBox.innerHTML = ''; // Réinitialiser les suggestions
        suggestionsBox.classList.remove('show'); // Masquer les suggestions
    }
});


    document.getElementById('addInstruction').addEventListener('click', function() {
        const container = document.getElementById('instructionsContainer');
        const instructionCount = container.children.length + 1; // Compte le nombre d'instructions existantes
        const newInstructionDiv = document.createElement('div');
        newInstructionDiv.classList.add('instruction');
        newInstructionDiv.innerHTML = `
            <input type="text" name="instructions[]" placeholder="Instruction ${instructionCount}" required>
            <button type="button" class="remove-instruction">Supprimer</button>
        `;
        container.appendChild(newInstructionDiv);
    });

    document.getElementById('instructionsContainer').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-instruction')) {
            e.target.parentElement.remove();
            // Mettre à jour les numéros d'ordre
            const instructions = document.querySelectorAll('#instructionsContainer .instruction');
            instructions.forEach((instruction, index) => {
                instruction.querySelector('input').setAttribute('placeholder', `Instruction ${index + 1}`);
            });
        }
    });
