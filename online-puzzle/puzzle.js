const puzzleSets = ["puzzle1", "puzzle2", "puzzle3"];

let timer;

function shuffleArray(array) {
  for (let i = array.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [array[i], array[j]] = [array[j], array[i]];
  }
}

function dragStart(event) {
  let wrapperId = event.target.parentElement.id; 
  event.dataTransfer.setData("text/plain", wrapperId);
  event.dataTransfer.effectAllowed = "move";
  document.getElementById(wrapperId).classList.add("dragging");
}

function stopTimer() {
  clearInterval(timer);
  const timeTaken = document.getElementById("time").textContent.split(": ")[1];
  alert(`Puzzle solved in ${timeTaken} seconds!`);
}

function verifyPuzzle() {
  let correctPieces = 0;
  const totalPieces = 12;
  for (let i = 1; i <= totalPieces; i++) {
    const piece = document.getElementById(`piece-wrapper-${i}`);
    if (piece) {
      const posX = parseInt(piece.style.left, 10);
      const posY = parseInt(piece.style.top, 10);
      const row = Math.floor((i - 1) / 4);
      const col = (i - 1) % 4;
      const expectedX = col * 100;
      const expectedY = row * 100;
      if (posX === expectedX && posY === expectedY) {
        correctPieces++;
      }
    }
  }
  const outcomeMessage = document.getElementById("puzzleOutcome");
  console.log('correct' + correctPieces);
  console.log('total' + totalPieces);
  if (correctPieces === totalPieces) {
    outcomeMessage.textContent = "Congratulations! You got it!";
    outcomeMessage.style.color = "green";
  } else {
    outcomeMessage.textContent = "Better luck next time.";
    outcomeMessage.style.color = "red";
  }
}

window.addEventListener("load", () => {
  const selectedSetIndex = Math.floor(Math.random() * puzzleSets.length);
  const selectedSet = puzzleSets[selectedSetIndex];
  const pieceNumbers = Array.from({ length: 12 }, (_, i) => i + 1);
  shuffleArray(pieceNumbers);
  const piecesContainer = document.getElementById("puzzlePieces");
  pieceNumbers.forEach((number) => {
    const pieceWrapper = document.createElement("div");
    pieceWrapper.classList.add("puzzlePieceWrapper");
    pieceWrapper.id = `piece-wrapper-${number}`;
    const imgElement = document.createElement("img");
    imgElement.src = `${selectedSet}/img${selectedSetIndex + 1}-${number}.jpg`;
    imgElement.classList.add("puzzlePiece");
    imgElement.draggable = true;
    imgElement.addEventListener("dragstart", dragStart);    // numberOverlay.classList.add("numberOverlay");
    // numberOverlay.textContent = number;
    pieceWrapper.appendChild(imgElement);
    piecesContainer.appendChild(pieceWrapper);
  });

  const backgroundGrid = document.getElementById("backgroundGrid");
  backgroundGrid.addEventListener("dragover", (event) =>
    event.preventDefault()
  );


  let timeElapsed = 0;
  const timeDisplay = document.getElementById("time");
  timer = setInterval(() => {
    timeElapsed++;
    timeDisplay.textContent = `Time: ${timeElapsed}`;
  }, 1000);

  const doneButton = document.getElementById("doneButton");
  doneButton.addEventListener("click", () => {
    stopTimer();
    verifyPuzzle();
  });

  document
    .getElementById("backgroundGrid")
    .addEventListener("drop", (event) => {
      event.preventDefault();
      const wrapperId = event.dataTransfer.getData("text/plain");
      const draggedWrapper = document.getElementById(wrapperId);
      const gridRect = event.currentTarget.getBoundingClientRect();
      const x = event.clientX - gridRect.left;
      const y = event.clientY - gridRect.top;
      const column = Math.floor(x / 100);
      const row = Math.floor(y / 100);
      const snappedX = column * 100;
      const snappedY = row * 100;
      draggedWrapper.style.position = "absolute";
      draggedWrapper.style.left = `${snappedX}px`;
      draggedWrapper.style.top = `${snappedY}px`;
      event.currentTarget.appendChild(draggedWrapper);
      draggedWrapper.classList.remove("dragging");
    });

  document
    .getElementById("backgroundGrid")
    .addEventListener("dragover", (event) => event.preventDefault());
});
