let classificationContainers = document.querySelectorAll(".classification_container");
let classificationCards = document.querySelectorAll(".classification_card");
let numCards = classificationCards.length;

function moveUp(cardId){
    classificationCards.forEach(function(card,index){
        if(card.id == cardId){
            if(index!=0){
                classificationContainers[index-1].append(classificationCards[index]);
                classificationContainers[index].append(classificationCards[index-1]);
                classificationCards = document.querySelectorAll(".classification_card");

                classificationCards.forEach(function(c, index){
                    let l = c.querySelector('.index_info').innerHTML = index;
                });
                return true;
            }
        }
    });

}

function moveDown(cardId){
    classificationCards.forEach(function(card,index){
        if(card.id == cardId){
            if(index < numCards-1){
                classificationContainers[index].append(classificationCards[index+1]);
                classificationContainers[index+1].append(classificationCards[index]);
                classificationCards = document.querySelectorAll(".classification_card");

                classificationCards.forEach(function(c, index){
                    let l = c.querySelector('.index_info').innerHTML = index;
                });
                return true;

            }
        }
    });
}









