<?php
/**
 * Created by PhpStorm.
 * User: elhazzat
 * Date: 2/12/15
 * Time: 5:04 PM
 */
require 'format.inc.php';
require 'lib/game.inc.php';
$view = new SudokuView($sudoku);
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
<?php echo present_header("Sudoku Game "); ?>


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

    var gameNotes

$(document).ready(function() {

    var clicked_x;
    var clicked_y;

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

    gameNotes = [
        [[], [], [],   [], [], [],    [], [], []],
        [[], [], [],   [], [], [],    [], [], []],
        [[], [], [],   [], [], [],    [], [], []],

        [[], [], [],   [], [], [],    [], [], []],
        [[], [], [],   [], [], [],    [], [], []],
        [[], [], [],   [], [], [],    [], [], []],

        [[], [], [],   [], [], [],    [], [], []],
        [[], [], [],   [], [], [],    [], [], []],
        [[], [], [],   [], [], [],    [], [], []],
    ];

    // render view
    function update(){
        for (y = 0; y < 9; y++) {
            for (x = 0; x < 9; x++) {
                // if cell has value
                if (gamePlaying[y][x] != 0) {
                    $("#cell-" + y + "-" + x).text(gamePlaying[y][x]);
                }

                // if cell has notes

            }
        }

        if (gamePlaying == gameSolution){
            alert("YOU WIN!");
        }
    };

    update();

    $(".cell").click(function (event) {
        // get cell x,y
        a = event.target.id.split("-");
        y = parseInt(a[1]);
        x = parseInt(a[2]);

        // if editable
        if (gameStart[y][x] == 0) {
            $( "#dialog").dialog( "open" );
            $("#guess").val( "" )
            $("#note").val( "" )
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
        if (gamePlaying[y][x] == gameSolution[y][x]) {
            $("#cell-" + y + "-" + x).addClass("guess-right");
            $("#cell-" + y + "-" + x).removeClass("guess-wrong");
        } else {
            $("#cell-" + y + "-" + x).addClass("guess-wrong");
            $("#cell-" + y + "-" + x).removeClass("guess-right");
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
    $( "#dialog" ).dialog({autoOpen: false});
});
</script>
</html>



