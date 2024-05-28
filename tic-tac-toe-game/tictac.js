let currentPlayer = 'X';
let gameActive = true;
let xWins = 0;
let oWins = 0;

// making move function 
function makeMove(cell) {
    if (cell.textContent || !gameActive) return;

    cell.textContent = currentPlayer;
    setTimeout(() => {
        checkForWin();
        if (gameActive) { 
            currentPlayer = currentPlayer === 'X' ? 'O' : 'X';
        }
    }, 0);
}

//chekcing for win by makign array of how win looks like 
function checkForWin() {
    const winConditions = [
        [0, 1, 2], [3, 4, 5], [6, 7, 8], 
        [0, 3, 6], [1, 4, 7], [2, 5, 8], 
        [0, 4, 8], [2, 4, 6]            
    ];
    const cells = document.querySelectorAll('.game-cell');
    let won = false;

    winConditions.forEach(condition => {
        const [a, b, c] = condition;
        if (cells[a].textContent && cells[a].textContent === cells[b].textContent && cells[a].textContent === cells[c].textContent) {
            alert(`${currentPlayer} has won!`);
            gameActive = false;
            won = true;
            // highlight winning cellss
            cells[a].classList.add('winning-cell');
            cells[b].classList.add('winning-cell');
            cells[c].classList.add('winning-cell');
            updateScoreboard(currentPlayer);
        }
    });

    if (!won && Array.from(cells).every(cell => cell.textContent)) {
        gameActive = false;
    }
}

//scorebaord update 
function updateScoreboard(winner) {
    if (winner === 'X') { // x wins
        xWins++;
        document.getElementById('xWins').textContent = xWins;
    } else if (winner === 'O') { // o wins 
        oWins++;
        document.getElementById('oWins').textContent = oWins;
    } 
}

// resetting when clicking replay (in html sec)
function resetGame() {
    const cells = document.querySelectorAll('.game-cell');
    cells.forEach(cell => {
        cell.textContent = ''; 
        cell.classList.remove('winning-cell'); 
    });
    gameActive = true; 
    currentPlayer = 'X';
}



