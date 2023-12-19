let classificationContainers = document.querySelectorAll(
    ".classification_container",
);
let classificationCards = document.querySelectorAll(".classification_card");
let numCards = classificationCards.length;
let dragged = null;
let previousContainer = null;

/**
 * Atualiza os índices das cartas com base na posição.
 */
function updateIndexes() {
    classificationCards.forEach(function (c, index) {
        let l = (c.querySelector(".index_info").innerHTML = index);
    });
}

/**
 * Move a card para cima na lista.
 * @param {string} cardId - O ID do card a ser movido.
 * @returns {boolean} - Retorna true se o card foi movido com sucesso.
 */
function moveUp(cardId) {
    classificationCards.forEach(function (card, index) {
        if (card.id == cardId) {
            if (index != 0) {
                classificationContainers[index - 1].append(
                    classificationCards[index],
                );
                classificationContainers[index].append(
                    classificationCards[index - 1],
                );
                classificationCards = document.querySelectorAll(
                    ".classification_card",
                );
                updateIndexes();
                updateOrder();
                return true;
            }
        }
    });
}

/**
 * Move a card para baixo na lista.
 * @param {string} cardId - O ID do card a ser movido.
 * @returns {boolean} - Retorna true se o card foi movido com sucesso.
 */
function moveDown(cardId) {
    classificationCards.forEach(function (card, index) {
        if (card.id == cardId) {
            if (index < numCards - 1) {
                classificationContainers[index].append(
                    classificationCards[index + 1],
                );
                classificationContainers[index + 1].append(
                    classificationCards[index],
                );
                classificationCards = document.querySelectorAll(
                    ".classification_card",
                );
                updateIndexes();
                updateOrder();
                return true;
            }
        }
    });
}

classificationContainers.forEach(function (container, index) {
    container.addEventListener("dragover", function (e) {
        e.preventDefault();
    });

    container.addEventListener("drop", function () {
        if (previousContainer != container) {
            thisCard = container.firstElementChild;
            secondCard = thisCard;
            previousContainer.appendChild(thisCard);
            container.appendChild(dragged);
            classificationCards = document.querySelectorAll(
                ".classification_card",
            );
            updateIndexes();
            updateOrder();
        }
    });
});

classificationCards.forEach(function (card, index) {
    card.addEventListener("dragstart", function () {
        dragged = card;
        previousContainer = card.parentNode;
        dragged.classList.add("isDragging");
    });

    card.addEventListener("dragend", function () {
        card.classList.remove("isDragging");
    });
});

/**
 * Atualiza a ordem dos cards no servidor.
 */
function updateOrder() {
    let list = [];
    classificationCards.forEach(function (card, index) {
        card.classList.add("updating-order");
        list.push(card.querySelector("input").value);
    });
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: "classificacoes/update/ordem",
        method: "PUT",
        data: { idList: JSON.stringify(list) },
        dataType: "json",
        success: function (result) {
            classificationCards.forEach(function (card) {
                card.classList.remove("updating-order");
            });
        },
        error(e) {
            alert("Erro ao alterar a ordem");
        },
    });
}
