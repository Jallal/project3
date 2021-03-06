<?php
/**
 * Created by PhpStorm.
 * User: elhazzat
 * Date: 2/12/15
 * Time: 5:04 PM
 */
session_start();
?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Sudoku game</title>
    <link rel="stylesheet" href="Sudoku.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
</head>

<body>
<header>
    <nav><p><a href="#" id="give-up">Give up</a>&nbsp;<a href="#" id="cheat">Cheat Mode</a></p></nav>
    <h1><?php echo $_SESSION['name']; ?>'s Sudoku Game</h1>
</header>


<div class="cells">
    <table>
        <colgroup>
            <col>
            <col>
            <col>
        </colgroup>

        <colgroup>
            <col>
            <col>
            <col>
        </colgroup>

        <colgroup>
            <col>
            <col>
            <col>
        </colgroup>

        <tbody></tbody>

        <tbody>
        <tr>
            <?php
            for ($y = 0; $y < 9; $y++) {
                if (($y) % 3 == 0){
                    echo "</tbody><tbody>";
                }

                echo "<tr>";
                for ($x = 0; $x < 9; $x++) {
                    echo <<<HTML

                <td>
                    <a id="cell-$y-$x" class="cell">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>

                </td>
HTML;
                }
                echo "</tr>";
            }

            ?>

        </tbody>
    </table>


</div>


<div id="dialog" title="Basic dialog">
    <form id="guessform">
        Enter value for cell, 0 to make the cell blank:<br>
        <input id="guess" type="text" placeholder="1-9" value="">
        <br><br>
        <input type="submit"  name ="submit_button" value="Add Guess">
    </form>
        <br>Enter a note for cell:<br>
        <form id="notesform">
            <input id="note" type="text" placeholder="1-9" value="">

            <br>
            <br>
            <input type="submit"  name ="note_button" value="Add Note">

        </form>
</div>


</body>


<script>

$(document).ready(function() {

    var clicked_x;
    var clicked_y;

    var cheatGame = [
        [0, 0, 0, 0, 5, 0, 0, 1, 9],
        [0, 0, 0, 0, 2, 0, 6, 3, 5],
        [0, 0, 9, 1, 0, 0, 7, 0, 0],
        [0, 0, 8, 0, 4, 0, 9, 0, 1],
        [6, 0, 0, 0, 0, 0, 0, 0, 7],
        [1, 0, 5, 0, 9, 0, 8, 0, 0],
        [0, 0, 6, 0, 0, 5, 1, 0, 0],
        [4, 5, 2, 0, 1, 0, 0, 0, 0],
        [3, 7, 0, 0, 8, 0, 0, 0, 0]
    ];

    var cheatStart = [
        [0, 0, 0, 0, 5, 0, 0, 1, 9],
        [0, 0, 0, 0, 2, 0, 6, 3, 5],
        [0, 0, 9, 1, 0, 0, 7, 0, 0],
        [0, 0, 8, 0, 4, 0, 9, 0, 1],
        [6, 0, 0, 0, 0, 0, 0, 0, 7],
        [1, 0, 5, 0, 9, 0, 8, 0, 0],
        [0, 0, 6, 0, 0, 5, 1, 0, 0],
        [4, 5, 2, 0, 1, 0, 0, 0, 0],
        [3, 7, 0, 0, 8, 0, 0, 0, 0]
    ];

    var cheatSolution = [
        [2, 6, 3, 7, 5, 8, 4, 1, 9],
        [8, 1, 7, 4, 2, 9, 6, 3, 5],
        [5, 4, 9, 1, 6, 3, 7, 8, 2],
        [7, 3, 8, 5, 4, 2, 9, 6, 1],
        [6, 9, 4, 8, 3, 1, 2, 5, 7],
        [1, 2, 5, 6, 9, 7, 8, 4, 3],
        [9, 8, 6, 3, 7, 5, 1, 2, 4],
        [4, 5, 2, 9, 1, 6, 3, 7, 8],
        [3, 7, 1, 2, 8, 4, 5, 9, 6]
    ];

    var gameSolution = [
        [8, 5, 3, 9, 1, 6, 4, 2, 7],
        [7, 9, 1, 2, 4, 3, 5, 6, 8],
        [2, 6, 4, 5, 8, 7, 9, 3, 1],
        [5, 1, 8, 4, 6, 9, 3, 7, 2],
        [9, 7, 2, 8, 3, 1, 6, 5, 4],
        [4, 3, 6, 7, 5, 2, 8, 1, 9],
        [3, 2, 7, 6, 9, 4, 1, 8, 5],
        [1, 8, 9, 3, 2, 5, 7, 4, 6],
        [6, 4, 5, 1, 7, 8, 2, 9, 3]
    ];

    var gameStart = [
        [0, 0, 0,   9, 0, 0,    0, 0, 0],
        [7, 0, 1,   0, 0, 3,    5, 6, 0],
        [0, 6, 4,   0, 0, 0,    0, 3, 0],

        [5, 1, 0,   4, 6, 0,    0, 0, 2],
        [9, 0, 0,   0, 3, 0,    0, 0, 4],
        [4, 0, 0,   0, 5, 2,    0, 1, 9],

        [0, 2, 0,   0, 0, 0,    1, 8, 0],
        [0, 8, 9,   3, 0, 0,    7, 0, 6],
        [0, 0, 0,   0, 0, 8,    0, 0, 0]
    ];

    var gamePlaying = [
        [0, 0, 0,   9, 0, 0,    0, 0, 0],
        [7, 0, 1,   0, 0, 3,    5, 6, 0],
        [0, 6, 4,   0, 0, 0,    0, 3, 0],

        [5, 1, 0,   4, 6, 0,    0, 0, 2],
        [9, 0, 0,   0, 3, 0,    0, 0, 4],
        [4, 0, 0,   0, 5, 2,    0, 1, 9],

        [0, 2, 0,   0, 0, 0,    1, 8, 0],
        [0, 8, 9,   3, 0, 0,    7, 0, 6],
        [0, 0, 0,   0, 0, 8,    0, 0, 0]
    ];

    var gameNotes = [
        [[], [], [],   [], [], [],    [], [], []],
        [[], [], [],   [], [], [],    [], [], []],
        [[], [], [],   [], [], [],    [], [], []],

        [[], [], [],   [], [], [],    [], [], []],
        [[], [], [],   [], [], [],    [], [], []],
        [[], [], [],   [], [], [],    [], [], []],

        [[], [], [],   [], [], [],    [], [], []],
        [[], [], [],   [], [], [],    [], [], []],
        [[], [], [],   [], [], [],    [], [], []]
    ];

    // render view
    function update(){
        for (y = 0; y < 9; y++) {
            for (x = 0; x < 9; x++) {
                // if cell has value
                if (gamePlaying[y][x] != 0) {
                    $("#cell-" + y + "-" + x).text(gamePlaying[y][x]);
                } else if(gameNotes[y][x].length != 0) {
                    var text = "";
                    for(var i = 0; i < gameNotes[y][x].length; i++) {
                        text += gameNotes[y][x][i];
                        if (i != gameNotes[y][x].length -1) {
                            text += ", ";
                        }
                    }
                    $("#cell-" + y + "-" + x).text(text);
                }

            }
        }

        if (gamePlaying == gameSolution){
            alert("YOU WIN!");
        }
    };

    function clearBoard(){
        for (y = 0; y < 9; y++) {
            for (x = 0; x < 9; x++) {
                $("#cell-" + y + "-" + x).html("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
            }
        }
    };

    update();

    $(document).ready ( function () {
        $(document).on("click", ".cell", function (event) {
            // get cell x,y
            a = event.target.id.split("-");
            y = parseInt(a[1]);
            x = parseInt(a[2]);

            // if editable
            if (gameStart[y][x] == 0) {
                $( "#dialog").dialog( "open" );
                $("#guess").val( "" );
                $("#note").val( "" );
                clicked_x = x;
                clicked_y = y;
            }
        });
        $(document).on("click", ".notes", function (event) {
            // get cell x,y
            var findIt = $(event.target);
            a = findIt.attr('id').split("-");
            y = parseInt(a[1]);
            console.log(y);
            x = parseInt(a[2]);
            console.log(x);

            // if editable
            if (gameStart[y][x] == 0) {
                $( "#dialog").dialog( "open" );
                $("#guess").val( "" );
                $("#note").val( "" );
                clicked_x = x;
                clicked_y = y;
            }
        });
        $( "#guessform" ).submit(function(event){
            event.preventDefault();
            $( "#dialog").dialog( "close" );

            guess = parseInt($("#guess").val());

            gamePlaying[y][x] = guess;
            // color correctly
            if (!$("#cell-" + y + "-" + x).hasClass('cell')) {
                $("#cell-" + y + "-" + x).addClass('cell');
            }
            if (guess != 0) {
                if (gamePlaying[y][x] == gameSolution[y][x]) {
                    $("#cell-" + y + "-" + x).addClass("guess-right");
                    $("#cell-" + y + "-" + x).removeClass("guess-wrong");
                } else {
                    $("#cell-" + y + "-" + x).addClass("guess-wrong");
                    $("#cell-" + y + "-" + x).removeClass("guess-right");
                }
            } else {
                $("#cell-" + y + "-" + x).removeClass();
            }


            // render game
            update();

            return false;
        });
        $( "#notesform" ).submit(function(event){
            event.preventDefault();
            $( "#dialog").dialog( "close" );

            note = parseInt($("#note").val());

            gameNotes[y][x].push(note);

            if (gamePlaying[y][x] == 0) {
                $("#cell-" + y + "-" + x).removeClass("cell");
                $("#cell-" + y + "-" + x).addClass("notes");
            }

            update();
            return false;
        });
        $("#give-up").click(function (event) {
            for (y = 0; y < 9; y++) {
                for (x = 0; x < 9; x++) {
                    $("#cell-" + y + "-" + x).text(gameSolution[y][x]);
                }
            }
        });
        $("#cheat").click(function (event) {
            gameStart = cheatStart;
            gamePlaying = cheatGame;
            gameSolution = cheatSolution;
            gameNotes = [
                [[], [], [],   [], [], [],    [], [], []],
                [[], [], [],   [], [], [],    [], [], []],
                [[], [], [],   [], [], [],    [], [], []],

                [[], [], [],   [], [], [],    [], [], []],
                [[], [], [],   [], [], [],    [], [], []],
                [[], [], [],   [], [], [],    [], [], []],

                [[], [], [],   [], [], [],    [], [], []],
                [[], [], [],   [], [], [],    [], [], []],
                [[], [], [],   [], [], [],    [], [], []]
            ];
            clearBoard();
            update();
        });
        $( "#dialog" ).dialog({autoOpen: false});
    });

    /*$(".cell").click(function (event) {

    });*/
});
</script>
</html>



