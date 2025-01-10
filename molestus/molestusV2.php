<?php
session_start();
ob_start();

if(empty($_COOKIE['is_login'])) {
    $_SESSION['is_login'] = 0;
} else {
    $_SESSION['is_login'] = 1;
}

?>

<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <meta name="author" content="Jakub Gajewski">
        <meta name="description" content="Aplikacja internetowa do manipulacji bazą danych">
        <meta name="keywords" content="php, sql, molestus, database">
        <title>Molestus 3000</title>
        <link rel="icon" type="image/x-icon" href="logo_molestus.png">
        <link rel="stylesheet" href="styl.css">
    </head>
    <body>

    <?php

        if (!empty($_POST['login'])) {

            $_SESSION['login'] = $_POST['login'];
            $_SESSION['password'] = $_POST['password'];

            try {
                $login_id = mysqli_connect('localhost', $_SESSION['login'], $_SESSION['password'], '');

                $_SESSION['is_login'] = 1;
                
                mysqli_close($login_id);
            } catch (Exception $e) {
                $_SESSION['is_login'] = 0;
                $_SESSION['login_error'] = "Błąd logowania. Sprawdź poprawność loginu i hasła";
            }
        };

        if($_SESSION['is_login'] === 1){

            if(!empty($_POST['select_db'])){
                if(!empty($_SESSION['select_db'])){
                    $_SESSION['select_db'] = $_POST['select_db'];
                    $_SESSION['select_tb'] = "";
                    $_SESSION['action'] = "Operacja na bazie";
                } 
                else{
                    $_SESSION['select_db'] = $_POST['select_db'];
                    $_SESSION['select_tb'] = "";
                    $_SESSION['action'] = "Operacja na bazie";
                } 
            }

            if(!empty($_POST['select_tb'])){
                $_SESSION['select_tb'] = $_POST['select_tb'];
                $_SESSION['action'] = "Operacja na tabeli";
            };

            if(!empty($_POST['select_action'])){
                $_SESSION['action'] = $_POST['select_action'];
            };

            setcookie("is_login", 1, time() + (1800), "/");

            $login_id = mysqli_connect('localhost', $_SESSION['login'], $_SESSION['password'], '');
            echo<<<END

            <form method="post" action="molestusV2.php">
                <input type="submit" name="select_action" class="first_buttons" value="Wyloguj się">
            </form>

            <form method="post" action="molestusV2.php">
                <input type="submit" name="select_action" class="first_buttons" value="O stronie">
            </form>

            <header>
                <div id="source">
                    <p id="p1">Src:</p> 

            END;

                echo '<p class="move_right">';

                if(!empty($_SESSION['select_db']) && !empty($_SESSION['select_tb'])){
                    echo $_SESSION['select_db'] . ' \\ ' . $_SESSION['select_tb'];
                }else if(!empty($_SESSION['select_db'])){
                    echo $_SESSION['select_db'] . ' \\ ';
                };
                echo "</p>";

            echo<<<END
                </div>
                <div id="action">
                    <form method="post" action="molestusV2.php">
                        <input type="submit" name="select_action" class="select_action_button" value="Dodaj bazę">
                    </form>

                    <form method="post" action="molestusV2.php">
                        <input type="submit" name="select_action" class="select_action_button" value="Dodaj tabelę">
                    </form>

                    <form method="post" action="molestusV2.php">
                        <input type="submit" name="select_action" class="select_action_button" value="SQL">
                    </form>
                </div>
            </header>

            <main>
                <div id="databases_bar">
                    <p>Dostępne bazy danych</p>
            END;

            $show_db = "SHOW databases";
            $show_db_result = mysqli_query($login_id, $show_db);

            while($result = mysqli_fetch_array($show_db_result)){
                echo<<<END

                <form action="molestusV2.php" method="post">
                    <br>
                    <input type="submit" name="select_db" class="select_db_button" value="$result[0]">
                </form>

                END;
            }

            echo<<<END
                </div>

                <div id="tables_bar">
                    <p>Dostępne tabele</p>

            END;

            if(!empty($_SESSION['select_db'])) {
                mysqli_select_db($login_id, $_SESSION['select_db']); 
                $show_tb = "SHOW tables";
                $show_tb_result = mysqli_query($login_id, $show_tb);
            
                if (!$show_tb_result) {
                    throw new Exception(mysqli_error($login_id));
                }
                
                while($result = mysqli_fetch_array($show_tb_result)) {
                    echo<<<END
                        <form action="molestusV2.php" method="post">
                            <br>
                            <input type="submit" name="select_tb" class="select_tb_button" value="$result[0]">
                        </form>
                    END;
                }
            } 
            else{
                echo '<br><p id="non_select_db_information">Nie wybrano bazy danych</p>';
            }

            echo<<<END
                </div>
                <div id="main_bar">
            END;

            if(isset($_SESSION['action'])){
                switch ($_SESSION['action']) {
                    case "Wyloguj się":
                        $cookies = $_COOKIE;
                        foreach ($cookies as $name => $value) {
                            setcookie($name, '', 0, '/');
                        }

                    case "O stronie":
                                
                        echo<<<END
                        <h1>O stronie</h1>
    
                        <h3>Autor:  Jakub Gajewski</h3>
    
                        <h3>Użyte języki:  <span title="HyperText Markup Language">HTML</span>, <span title="Cascading Style Sheets">CSS</span>, <span title="Hypertext Preprocessor">PHP</span></h3>
                                
                        <p id="about_page">Strona została w całości wykonana, używając 3 ww. języków. Jest to wyzwanie podjęte przeze mnie za namową kolegi z klasy 3TF, Mateusza, który dokładnie taki sam projekt przygotowywał rok temu.</p> 
                        <p id="about_page">Nazwa tej strony, <span lang="la">molestus</span>, pochodzi z języka łacińskiego i oznacza irytację/coś irytującego, czyli dokładny opis tego, co towarzyszyło mi podczas tworzenia tej strony.</p>
                        END;
                        break;

                    case "Operacja na bazie":

                        echo<<<END
                            <form method="post" action="molestusV2.php">
                                <input type="submit" name="select_action" class="select_action_button" value="Usuń bazę">
                            </form>
                        END;
                    
                        echo "<h1>{$_SESSION['select_db']}</h1>";
                        echo "<h2>Kodowanie:</h2>";

                        $encoding_db = "SELECT DEFAULT_CHARACTER_SET_NAME, DEFAULT_COLLATION_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = '{$_SESSION['select_db']}'";
                        $encoding_db_result = mysqli_query($login_id, $encoding_db);
                    
                        $result = mysqli_fetch_array($encoding_db_result); 
                        echo '<p class = "move_right">' . $result[0] . '</p>';

                        echo "<h2>Tabele:</h2>";

                        $show_tb = "SHOW tables";
                        $show_tb_result = mysqli_query($login_id, $show_tb);
                
                        if (!$show_tb_result) {
                            throw new Exception(mysqli_error($login_id));
                        }
                    
                        echo "<ul>";
                        while($result = mysqli_fetch_array($show_tb_result)) {
                            echo '<li class = "move_right">' . $result[0] . '</li>';
                        }
                        echo "</ul>";
                        
                        break;

                    case "Usuń bazę":

                        echo<<<END

                        <h1>Czy na pewno chcesz usunąć baze danych '{$_SESSION['select_db']}' </h1>

                        <form method="post" action="molestusV2.php">
                            <input type="text" name="select_action" class="invisible" value="Drop_database">
                            <input type="submit" class="select_action_button" value="Usuń bazę">
                        </form>

                        <br>

                        <form method="post" action="molestusV2.php">
                            <input type="text" name="select_action" class="invisible" value="Operacja na bazie">
                            <input type="submit" class="select_action_button" value="Wróć">
                        </form>

                        END;
                        break;

                    case "Drop_database":

                        $drop_db = "DROP database {$_SESSION['select_db']}";
                        mysqli_query($login_id, $drop_db);
                
                        $_SESSION['select_db'] = "";
                        $_SESSION['select_tb'] = "";
                        $_SESSION['action'] = "default";
                        header("Location: molestusV2.php");
                        exit();
                        break;

                    case "Operacja na tabeli":

                        echo<<<END
                            <div class="display_flex">
                                <form method="post" action="molestusV2.php">
                                    <input type="submit" name="select_action" class="select_action_button" value="Usuń tabele">
                                </form>
                                
                                <form method="post" action="molestusV2.php">
                                    <input type="submit" name="select_action" class="select_action_button" value="Zmien nazwe tabeli">
                                </form>

                                <form method="post" action="molestusV2.php">
                                    <input type="submit" name="select_action" class="select_action_button" value="Dodaj kolumnę">
                                </form>

                                <form method="post" action="molestusV2.php">
                                    <input type="submit" name="select_action" class="select_action_button" value="Usuń kolumnę">
                                </form>

                                <form method="post" action="molestusV2.php">
                                    <input type="submit" name="select_action" class="select_action_button" value="Zmien typ danych/nazwę kolumny">
                                </form>
                            </div>
                        END;
                        
                        echo "<h1>{$_SESSION['select_tb']}</h1>";
                        echo "<h2>Zawartość:</h2>";
        
                        $content_tb = "SELECT * FROM {$_SESSION['select_tb']}";
                        $content_tb_result = mysqli_query($login_id, $content_tb);

                        $tb_primary_key = "SELECT COLUMN_NAME  FROM information_schema.KEY_COLUMN_USAGE  WHERE TABLE_SCHEMA = '{$_SESSION['select_db']}'  AND TABLE_NAME = '{$_SESSION['select_tb']}'  AND CONSTRAINT_NAME = 'PRIMARY'";
                        $tb_primary_key_result = mysqli_query($login_id, $tb_primary_key);
                        $primary_key = mysqli_fetch_assoc($tb_primary_key_result);

                        if ($primary_key) {
                        } else {
                            $primary_key_error = "Brak klucza głównego w tabeli, aby edytowac i kasować rekordy należy go dodać, np. w sekcji MySql poleceniem ALTER TABLE";
                        }

                        if(!empty($primary_key_error)){ echo '<p class="alert">' . $primary_key_error . '</p>';}
                        echo '<table class="data_table">';
                        echo "<tr>";

                        $column_names = mysqli_fetch_fields($content_tb_result);

                        if (!empty($primary_key)) {echo '<th></th>';}

                        foreach ($column_names as $column_name) {
                            echo "<th>" . $column_name->name . "</th>";
                        }

                        echo "</tr>";

                        while($results = mysqli_fetch_array($content_tb_result, MYSQLI_ASSOC)) {
                            echo "<tr>";

                            if (!empty($primary_key)) {
                                echo<<<END
                                <td>

                                <form method="post" action="molestusV2.php">
                                    <input type="text" name="primary_key" class="invisible" value="{$primary_key['COLUMN_NAME']}">
                                    <input type="text" name="entry_to_identify" class="invisible" value="{$results[$primary_key['COLUMN_NAME']]}">
                                    <input type="submit" name="select_action" class="background_yellow" value="Usuń wiersz">
                                </form>

                                <form method="post" action="molestusV2.php">
                                    <input type="text" name="primary_key" class="invisible" value="{$primary_key['COLUMN_NAME']}">
                                    <input type="text" name="entry_to_identify" class="invisible" value="{$results[$primary_key['COLUMN_NAME']]}">
                                    <input type="submit" name="select_action" class="background_yellow" value="Edytuj wiersz">
                                </form>

                                </td>
                                END;
                            }

                            foreach ($results as $result) {
                                echo "<td>" . $result . "</td>";
                            }

                            echo "</tr>";
                        }
                        
                        echo<<<END
                        </table>

                        <br>
                        <form method="post" action="molestusV2.php">
                            <input type="submit" name="select_action" class="select_action_button" value="Dodaj rekord">
                        </form>

                        <h2>Struktura:</h2>
                        END;

                        $describe_tb = "describe {$_SESSION['select_tb']}";
                        $describe_tb_result = mysqli_query($login_id, $describe_tb);

                        echo '<table class="data_table">';
                        echo "<tr>";

                        $column_names = mysqli_fetch_fields($describe_tb_result);
                        foreach ($column_names as $column_name) {
                            echo "<th>" . $column_name->name . "</th>";
                        }

                        echo "</tr>";


                        while($results = mysqli_fetch_array($describe_tb_result, MYSQLI_ASSOC)) {
                            echo "<tr>";

                            foreach ($results as $result) {
                                echo "<td>" . $result . "</td>";
                            }

                            echo "</tr>";
                        }
                        echo "</table>";
                        break;
        
                    case "Usuń tabele":
        
                        echo<<<END
        
                        <h1>Czy na pewno chcesz usunąć tabelę '{$_SESSION['select_tb']}' </h1>
        
                        <form method="post" action="molestusV2.php">
                            <input type="text" name="select_action" class="invisible" value="Drop_table">
                            <input type="submit" class="select_action_button" value="Usuń tabelę">
                        </form>

                        <br>
        
                        <form method="post" action="molestusV2.php">
                            <input type="text" name="select_action" class="invisible" value="Operacja na tabeli">
                            <input type="submit" class="select_action_button" value="Wróć">
                        </form>
        
                        END;
                        break;
        
                    case "Drop_table":
        
                        $drop_tb = "DROP table {$_SESSION['select_tb']}";
                        mysqli_query($login_id, $drop_tb);
                    
                        echo "<h1>Usunięto tabelę {$_SESSION['select_db']} </h1>";
                        $_SESSION['select_tb'] = "";
                        $_SESSION['action'] = "";
            
                        break;

                    case "Usuń wiersz":

                        $_SESSION['tb_primary_key'] = $_POST['primary_key'];
                        $_SESSION['entry_to_identify'] = $_POST['entry_to_identify'];
        
                        if (isset($_SESSION['entry_to_identify'])){
                            echo"<h1>Czy na pewno chcesz usunąć ten wiersz?</h1>";

                            $content_tb = "SELECT * FROM {$_SESSION['select_tb']} WHERE {$_SESSION['tb_primary_key']} = {$_SESSION['entry_to_identify']}";
                            $content_tb_result = mysqli_query($login_id, $content_tb);

                            echo '<table class="data_table">';
                            echo "<tr>";

                            $column_names = mysqli_fetch_fields($content_tb_result);

                            foreach ($column_names as $column_name) {
                                echo "<th>" . $column_name->name . "</th>";
                            }

                            echo "</tr>";

                            $results = mysqli_fetch_array($content_tb_result, MYSQLI_ASSOC);
                            echo "<tr>";

                            foreach ($results as $result) {
                                echo "<td>" . $result . "</td>";
                            }

                            echo "</tr>";
                            
                            
                            echo "</table>";

                            echo<<<END

                            <br>
                            <form method="post" action="molestusV2.php">
                                <input type="text" name="select_action" class="invisible" value="Drop_record">
                                <input type="submit" class="select_action_button" value="Usuń wiersz">
                            </form>
        
                
                            <form method="post" action="molestusV2.php">
                                <input type="text" name="select_action" class="invisible" value="Operacja na tabeli">
                                <input type="submit" class="select_action_button" value="Wróć">
                            </form>
                        
                            END;
                        }
                        else {
                            echo '<p class="alert">Ten rekord nie posiada klucza głównego, który jest wymagany do zidentyfikowania rekordu do usunięcia</p>';
                        }
                        break;
            
                    case "Drop_record":
            
                        $drop_rc = "DELETE FROM {$_SESSION['select_tb']} WHERE {$_SESSION['tb_primary_key']} = {$_SESSION['entry_to_identify']}";
                        mysqli_query($login_id, $drop_rc);
                        
                        echo "<h1>Usunięto wiersz</h1>";
                        $_SESSION['action'] = "";
                        $_SESSION['tb_primary_key'] = "";
                        $_SESSION['entry_to_identify'] = "";
                
                        break;
                    case "Edytuj wiersz":

                        $_SESSION['tb_primary_key'] = $_POST['primary_key'];
                        $_SESSION['entry_to_identify'] = $_POST['entry_to_identify'];

                        if (isset($_SESSION['entry_to_identify'])) {
                            echo "<h1>Edycja rekordu</h1>";

                            $content_tb = "SELECT * FROM {$_SESSION['select_tb']} WHERE {$_SESSION['tb_primary_key']} = {$_SESSION['entry_to_identify']}";
                            $content_tb_result = mysqli_query($login_id, $content_tb);

                            echo '<form method="post" action="molestusV2.php">';
                            echo '<table class="data_table">';
                            echo "<tr>";

                            $column_names = mysqli_fetch_fields($content_tb_result);

                            foreach ($column_names as $column_name) {
                                echo "<th>" . $column_name->name . "</th>";
                            }

                            echo "</tr>";

                            $results = mysqli_fetch_array($content_tb_result, MYSQLI_ASSOC);
                            echo "<tr>";

                            $number_type =['tinyint', 'smallint', 'mediumint', 'int', 'bigint', 'float', 'double', 'decimal'];

                            foreach ($results as $column_name => $result) {

                                $column_type = "SELECT DATA_TYPE, EXTRA FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '{$_SESSION['select_db']}' AND TABLE_NAME = '{$_SESSION['select_tb']}' AND COLUMN_NAME = '{$column_name}'";
                                $column_type_result = mysqli_query($login_id, $column_type);

                                $column_type = mysqli_fetch_array($column_type_result);

                                if ($column_name == $_SESSION['tb_primary_key'] || $column_type[1] == 'AUTO_INCREMENT') {
                                    echo "<td>" . $result . "</td>";
                                }else if(in_array($column_type[0], $number_type)){
                                    echo '<td><input type="number" class="input_in_table" name="' . $column_name . '" value="' . $result . '"></td>';
                                }else if($column_type[0] == 'date'){
                                    echo '<td><input type="date" class="input_in_table" name="' . $column_name . '" value="' . $result . '"></td>';
                                }else if($column_type[0] == 'time'){
                                    echo '<td><input type="time" class="input_in_table" name="' . $column_name . '" value="' . $result . '"></td>';
                                }else if($column_type[0] == 'year'){
                                    echo '<td><input type="number" min="1901" max="2155" pattern="[0-9]{4}" class="input_in_table" name="' . $column_name . '" value="' . $result . '"></td>';
                                }else if($column_type[0] == 'datetime'){
                                    echo '<td><input type="datetime-local" class="input_in_table" name="' . $column_name . '" value="' . $result . '"></td>';
                                }else {
                                    echo '<td><input type="text" class="input_in_table" name="' . $column_name . '" value="' . $result . '"></td>';
                                }
                            }

                            echo <<<END

                            </tr>
                            </table>
                            <br>
                            <input type="hidden" name="select_action" value="update_record">
                            <input type="submit" class="select_action_button" value="Zapisz zmiany">
                            </form>

                            <form method="post" action="molestusV2.php">
                            <input type="hidden" name="select_action" value="Operacja na tabeli">
                            <input type="submit" class="select_action_button" value="Wróć">
                            </form>

                            END;
                        }
                        else {
                            echo '<p class="alert">Ten rekord nie posiada klucza głównego, który jest wymagany do zidentyfikowania rekordu do edycji</p>';
                        }
                        break; 

                    case "update_record":

                        $update_query = "UPDATE {$_SESSION['select_tb']} SET ";
                        $to_update = [];
                        $index = 0;

                        $content_tb = "SELECT * FROM {$_SESSION['select_tb']} WHERE {$_SESSION['tb_primary_key']} = {$_SESSION['entry_to_identify']}";
                        $content_tb_result = mysqli_query($login_id, $content_tb);


                        $results = mysqli_fetch_array($content_tb_result, MYSQLI_ASSOC);

                        foreach ($results as $column_name => $result) {
                            if($column_name == $_SESSION['tb_primary_key']){}
                            else if ($result == $_POST[$column_name] ) {}
                            else {
                                $to_update[$index] = "$column_name = '$_POST[$column_name]'";
                                $index ++;
                            }
                        }

                        if($index > 0){
                            $update_query = $update_query . implode(", ", $to_update) . " WHERE {$_SESSION['tb_primary_key']} = {$_SESSION['entry_to_identify']}";
                            mysqli_query($login_id, $update_query);
                        }
                    
                        echo "<h1>Edytowano rekord</h1>";
                        break;

                    case "Zmien nazwe tabeli":

                        echo "<h1>Zmiana nazwy tabeli {$_SESSION['select_tb']}</h1>";

                        if(isset($_SESSION['error'])){
                            echo '<p class="alert">' . $_SESSION['error'] .'</p>';
                            unset($_SESSION['error']);
                        }

                        echo<<<END
                        <form method="post" action="molestusV2.php">
                            <label for="table_name_to_change">Podaj nazwę tabeli do zmiany: </label>
                            <input type="text" name="table_name_to_change" id="table_name_to_change" value="{$_SESSION['select_tb']}">
                            <br>
                            <input type="hidden" name="select_action" value="table_name_to_change">
                            <input type="submit" value="Zmien nazwę">
                        </form>

                        <br>
                        <form method="post" action="molestusV2.php">
                            <input type="hidden" name="select_action" value="Operacja na tabeli">
                            <input type="submit" class="select_action_button" value="Wróć">
                        </form>

                        END;
                        break;

                    case "table_name_to_change":
                        if(!empty($_POST['table_name_to_change'])){
                            $drop_tb = "ALTER TABLE {$_SESSION['select_tb']} RENAME {$_POST['table_name_to_change']};";
                            mysqli_query($login_id, $drop_tb);
                            
                            $_SESSION['action'] = "Operacja na bazie";
                            header("Location: molestusV2.php");
                            exit();
                        }else{
                            $_SESSION['error'] = "Pole nazwy nie może być puste";
                            $_SESSION['action'] = "Zmien nazwe tabeli";
                            header("Location: molestusV2.php");
                            exit();
                        }
                
                        break;
                    
                    case "Dodaj kolumnę":

                        echo "<h1>Dodawanie kolumny do tabeli '{$_SESSION['select_tb']}'</h1>";

                        if(isset($_SESSION['error'])){
                            echo '<p class="alert">' . $_SESSION['error'] .'</p>';
                            unset($_SESSION['error']);
                        }
                        
                        echo<<<END
                        <br><br><br><br>
                        <form method="post" action="molestusV2.php">
                            <label for="column_name_to_add">Nazwę kolumny do dodania: </label>
                            <input type="text" name="column_name_to_add" id="column_name_to_add">
                            <br><br>

                            <label for="column_type_to_add">Typ danych w kolumnie: </label>
                            <select name="column_type_to_add" id="column_type_to_add">
                                <optgroup>
                                    <option>INT</option>
                                    <option>VARCHAR</option>
                                    <option>CHAR</option>
                                    <option>TEXT</option>
                                    <option>DATE</option>
                                </optgroup>
                                <optgroup>
                                    <option>TINYINT</option>
                                    <option>SMALLINT</option>
                                    <option>MEDIUMINT</option>
                                    <option>INT</option>
                                    <option>BIGINT</option>
                                    <option>DECIMAL</option>
                                    <option>FLOAT</option>
                                    <option>DOUBLE</option>
                                    <option>REAL</option>
                                    <option>BIT</option>
                                    <option>BOOLEAN</option>
                                </optgroup>
                                <optgroup>
                                    <option>DATE</option>
                                    <option>DATETIME</option>
                                    <option>TIMESTAMP</option>
                                    <option>TIME</option>
                                </optgroup>
                                <optgroup>
                                    <option>CHAR</option>
                                    <option>VARCHAR</option>
                                    <option>TINYTEXT</option>
                                    <option>TEXT</option>
                                    <option>MEDIUMTEXT</option>
                                    <option>LONGTEXT</option>
                                    <option>BINARY</option>
                                    <option>VARBINARY</option>
                                    <option>TINYBLOB</option>
                                    <option>BLOB</option>
                                    <option>MEDIUMBLOB</option>
                                    <option>LONGBLOB</option>
                                    <option>ENUM</option>
                                </optgroup>
                            </select>
                            <br><br>

                            <label for="column_width_to_add" title="Długośc jest wymagana w tych typach danych: varchar, char, binary, varbinary">Długość (jeśli potrzebna): </label>
                            <input type="number" min="1" name="column_width_to_add" id="column_width_to_add">
                            <br><br>

                            <label for="column_default_to_add">Wartość domyslna: </label>
                            <input type="text" name="column_default_to_add" id="column_default_to_add">
                            <br><br>

                            <label for="column_null_to_add">Czy null: </label>
                            <select name="column_null_to_add" id="column_null_to_add">
                                <option>NULL</option>
                                <option>NOT NULL</option>
                            </select>
                            <br><br>

                            <input type="hidden" name="select_action" value="add_column">
                            <input type="submit" class="select_action_button" value="Dodaj kolumnę">
                        </form>
        
                        <br>
                        <form method="post" action="molestusV2.php">
                            <input type="hidden" name="select_action" value="Operacja na tabeli">
                            <input type="submit" class="select_action_button" value="Wróć">
                        </form>
        
                        END;
                        break;

                    case "add_column":

                        if(!empty($_POST['column_name_to_add'])){

                            $add_query = "ALTER TABLE {$_SESSION['select_tb']} ADD COLUMN ";

                            $add_query = $add_query . $_POST['column_name_to_add'] . " " . $_POST['column_type_to_add'];

                            if(!empty($_POST['column_width_to_add'])){
                                if($_POST['column_type_to_add'] == 'VARCHAR' || $_POST['column_type_to_add'] == 'VARBINARY'){
                                    if($_POST['column_width_to_add'] >= 65535){ $add_query = $add_query . "(65535)";}
                                }
                                else if($_POST['column_type_to_add'] == 'CHAR' || $_POST['column_type_to_add'] == 'BINARY'){ 
                                    if($_POST['column_width_to_add'] >= 255){ $add_query = $add_query . "(255)"; }
                                }
                                else{$add_query = $add_query . "(" . $_POST['column_width_to_add'] . ")";}
                            }
                            else{
                                if($_POST['column_type_to_add'] == 'VARCHAR' || $_POST['column_type_to_add'] == 'VARBINARY' || $_POST['column_type_to_add'] == 'CHAR' || $_POST['column_type_to_add'] == 'BINARY'){
                                    $add_query = $add_query . "(20)";
                                }
                            }

                            $add_query = $add_query . " " . $_POST['column_null_to_add'];

                            if(!empty($_POST['column_default_to_add'])){
                                $add_query = $add_query . " DEFAULT '" . $_POST['column_default_to_add'] . "'";
                            }

                            mysqli_query($login_id, $add_query);

                            echo "<h1>Dodano kolumnę</h1>";
                        }
                        else{
                            $_SESSION['error'] = "Pole nazwy nie może być puste";
                            $_SESSION['action'] = "Dodaj kolumnę";
                            header("Location: molestusV2.php");
                            exit();
                        }
                        
                        break;
                    case "Usuń kolumnę":

                        echo<<<END
                        <h1>Usuwanie kolumny z tabeli '{$_SESSION['select_tb']}'</h1>
                        <br><br><br><br>

                        <form method="post" action="molestusV2.php">
                            <label for="column_name_to_remove">Nazwa kolumny do usunięcia: </label>
                            <select name="column_name_to_remove" id="column_name_to_remove">
                        END;

                            $select_table = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '{$_SESSION['select_db']}' AND TABLE_NAME = '{$_SESSION['select_tb']}'";
                            $select_table_results = mysqli_query($login_id, $select_table);

                            $tb_primary_key = "SELECT COLUMN_NAME  FROM information_schema.KEY_COLUMN_USAGE  WHERE TABLE_SCHEMA = '{$_SESSION['select_db']}'  AND TABLE_NAME = '{$_SESSION['select_tb']}'  AND CONSTRAINT_NAME = 'PRIMARY'";
                            $tb_primary_key_result = mysqli_query($login_id, $tb_primary_key);
                            $primary_key = mysqli_fetch_array($tb_primary_key_result);

                            while($result = mysqli_fetch_array($select_table_results)){
                                if($result[0] == $primary_key[0]){}
                                else{
                                    echo '<option value="' . $result[0] . '">' . $result[0] . '</option>';
                                }
                            }

                        echo<<<END
                            </select>
                            <br>
                            <br>
                            <input type="hidden" name="select_action" value="Remove_column_ask">
                            <input type="submit" class="select_action_button" value="Usuń kolumnę">
                        </form>

                        <br>
                        <form method="post" action="molestusV2.php">
                            <input type="hidden" name="select_action" value="Operacja na tabeli">
                            <input type="submit" class="select_action_button" value="Wróć">
                        </form>
                            
                        END;
                        break;

                    case "Remove_column_ask":

                        $_SESSION['column_name_to_remove'] = $_POST['column_name_to_remove'];

                        echo<<<END
                        <h1>Czy na pewno chcesz usunąć kolumnę '{$_SESSION['column_name_to_remove']}' z tabeli '{$_SESSION['select_tb']}'?</h1>
                        <br><br><br><br>
        
                        <form method="post" action="molestusV2.php">
                            <input type="hidden" name="select_action" value="Remove_column">
                            <input type="submit" class="select_action_button" value="Usuń">
                        </form>
                        <br>

                        <form method="post" action="molestusV2.php">
                            <input type="hidden" name="select_action" value="Operacja na tabeli">
                            <input type="submit" class="select_action_button" value="Wróć">
                        </form>

                        END;
        
                        break;
                    
                    case "Remove_column":
                        
                        $remove_column = "ALTER TABLE {$_SESSION['select_tb']} DROP COLUMN {$_SESSION['column_name_to_remove']}";

                        mysqli_query($login_id, $remove_column);

                        unset($_SESSION['column_name_to_remove']);
                        
                        echo "<h1>Usunięto kolumnę '{$_SESSION['column_name_to_remove']}' z tabeli '{$_SESSION['select_tb']}'</h1>";
            
                        break;
                    
                    case "Zmien typ danych/nazwę kolumny":

                        echo<<<END
                        <h1>Zmiana typu danych/nazwy kolumny z tabeli '{$_SESSION['select_tb']}'</h1>
                        <br><br><br><br>
        
                        <form method="post" action="molestusV2.php">
                            <label for="column_name_to_change">Nazwa kolumny: </label>
                            <select name="column_name_to_change" id="column_name_to_change">
                        END;
        
                            $select_table = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '{$_SESSION['select_db']}' AND TABLE_NAME = '{$_SESSION['select_tb']}'";
                            $select_table_results = mysqli_query($login_id, $select_table);
        
                            $tb_primary_key = "SELECT COLUMN_NAME  FROM information_schema.KEY_COLUMN_USAGE  WHERE TABLE_SCHEMA = '{$_SESSION['select_db']}'  AND TABLE_NAME = '{$_SESSION['select_tb']}'  AND CONSTRAINT_NAME = 'PRIMARY'";
                            $tb_primary_key_result = mysqli_query($login_id, $tb_primary_key);
                            $primary_key = mysqli_fetch_array($tb_primary_key_result);
        
                            while($result = mysqli_fetch_array($select_table_results)){
                                if($result[0] == $primary_key[0]){}
                                else{
                                    echo '<option value="' . $result[0] . '">' . $result[0] . '</option>';
                                }
                            }
        
                        echo<<<END
                            </select>
                            <br>
                            <br>
                            <input type="hidden" name="select_action" value="Change_column_type_name_values">
                            <input type="submit" class="select_action_button" value="Edytuj">
                        </form>
        
                        <br>
                        <form method="post" action="molestusV2.php">
                            <input type="hidden" name="select_action" value="Operacja na tabeli">
                            <input type="submit" class="select_action_button" value="Wróć">
                        </form>
                                
                        END;
                        break;

                    case "Change_column_type_name_values":

                        $_SESSION['old_column_name'] = $_POST['column_name_to_change'];
                        $select_type_name = "SELECT DATA_TYPE, CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '{$_SESSION['select_db']}' AND TABLE_NAME = '{$_SESSION['select_tb']}' AND COLUMN_NAME = '{$_POST['column_name_to_change']}'";
                        $select_type_name_results = mysqli_query($login_id, $select_type_name);
            
                        $result = mysqli_fetch_array($select_type_name_results);

                        echo <<<END
                        <h1>Zmiana typu danych/nazwy kolumny z tabeli '{$_SESSION['select_tb']}'</h1>
                        <br><br><br><br>
                        END;

                        if(isset($_SESSION['error'])){
                            echo '<p class="alert">' . $_SESSION['error'] .'</p>';
                            unset($_SESSION['error']);
                        }

                        $column_type = strtoupper($result[0]);

                        echo<<<END
                        <form method="post" action="molestusV2.php">
                            <label for="column_name_to_change">Nazwa kolumny: </label>
                            <input type="text" id="column_name_to_change" name="column_name_to_change" value="{$_POST['column_name_to_change']}">
                            <br><br>

                            <label for="column_type_to_change">Typ danych kolumny: </label>
                            <select name="column_type_to_change" id="column_type_to_change">
                                <optgroup>
                                    <option>$column_type</option>
                                    <option>INT</option>
                                    <option>VARCHAR</option>
                                    <option>CHAR</option>
                                    <option>TEXT</option>
                                    <option>DATE</option>
                                </optgroup>
                                <optgroup>
                                    <option>TINYINT</option>
                                    <option>SMALLINT</option>
                                    <option>MEDIUMINT</option>
                                    <option>INT</option>
                                    <option>BIGINT</option>
                                    <option>DECIMAL</option>
                                    <option>FLOAT</option>
                                    <option>DOUBLE</option>
                                    <option>REAL</option>
                                    <option>BIT</option>
                                    <option>BOOLEAN</option>
                                </optgroup>
                                <optgroup>
                                    <option>DATE</option>
                                    <option>DATETIME</option>
                                    <option>TIMESTAMP</option>
                                    <option>TIME</option>
                                </optgroup>
                                <optgroup>
                                    <option>CHAR</option>
                                    <option>VARCHAR</option>
                                    <option>TINYTEXT</option>
                                    <option>TEXT</option>
                                    <option>MEDIUMTEXT</option>
                                    <option>LONGTEXT</option>
                                    <option>BINARY</option>
                                    <option>VARBINARY</option>
                                    <option>TINYBLOB</option>
                                    <option>BLOB</option>
                                    <option>MEDIUMBLOB</option>
                                    <option>LONGBLOB</option>
                                    <option>ENUM</option>
                                </optgroup>
                            </select>
                            <br><br>
                        END;
                                
                            if(!empty($result[1])){
                                echo<<<END
                                <label for="column_width_to_change" title="Długość jest wymagana w tych typach danych: varchar, char, binary, varbinary">Długość (jeśli potrzebna): </label>
                                <input type="number" min="1" name="column_width_to_change" id="column_width_to_change" value="$result[1]">
                                <br><br>
                                END;
                            }else{
                                echo<<<END
                                <label for="column_width_to_change" title="Długość jest wymagana w tych typach danych: varchar, char, binary, varbinary">Długość (jeśli potrzebna): </label>
                                <input type="number" min="1" name="column_width_to_change" id="column_width_to_change">
                                <br><br>
                                END;
                            }
        
                            echo<<<END
                                <input type="hidden" name="select_action" value="Change_column_type_name">
                                <input type="submit" class="select_action_button" value="Zmień dane">
                            </form>
                
                            <br>
                            <form method="post" action="molestusV2.php">
                                <input type="hidden" name="select_action" value="Operacja na tabeli">
                                <input type="submit" class="select_action_button" value="Wróć">
                            </form>
                            END;
                        break;
                    case "Change_column_type_name":

                        $new_column_type = $_POST['column_type_to_change'];

                        if(!empty($new_column_type) && !empty($_POST['column_name_to_change'])){

                            $column_width = $_POST['column_width_to_change'];

                            if(!empty($column_width)){
                                if($new_column_type == 'VARCHAR' || $new_column_type == 'VARBINARY'){
                                    if($column_width >= 65535){ $new_column_type = $new_column_type . "(65535)";}
                                    else{$new_column_type = $new_column_type . "(" . $column_width . ")";}
                                }
                                else if($new_column_type == 'CHAR' || $new_column_type == 'BINARY'){ 
                                    if($column_width >= 255){ $new_column_type = $new_column_type . "(255)"; }
                                    else{$new_column_type = $new_column_type . "(" . $column_width . ")";}
                                }
                                else{$new_column_type = $new_column_type . "(" . $column_width . ")";}
                            }
                            else{
                                if($new_column_type == 'VARCHAR' || $new_column_type == 'VARBINARY' || $new_column_type == 'CHAR' || $new_column_type == 'BINARY'){
                                $new_column_type = $new_column_type . "(20)";
                                }
                            }
                            
                            $change_column_name = "ALTER TABLE {$_SESSION['select_tb']} CHANGE {$_SESSION['old_column_name']} {$_POST['column_name_to_change']} {$new_column_type}";
                            mysqli_query($login_id, $change_column_name);

                            echo "<h1>Zmieniono dane<h1>";
                        }else{
                            $_SESSION['error'] = "Pola nie mogą być puste";
                            $_SESSION['action'] = "Change_column_type_name_values";
                            header("Location: molestusV2.php");
                            exit(); 
                        }
                        break;

                    case "Dodaj rekord":
        
                        $select_information = "SELECT DATA_TYPE, COLUMN_NAME, EXTRA FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '{$_SESSION['select_db']}' AND TABLE_NAME = '{$_SESSION['select_tb']}'";
                        $select_information_results = mysqli_query($login_id, $select_information);

                        $number_type =['tinyint', 'smallint', 'mediumint', 'int', 'bigint', 'float', 'double', 'decimal'];
        
                        echo "<h1>Dodawanie rekordu do tabeli '". $_SESSION['select_tb'] ."'</h1>";

                        if(isset($_SESSION['error'])){
                            echo '<p class="alert">' . $_SESSION['error'] .'</p>';
                            unset($_SESSION['error']);
                        }

                        echo '<form method="post" action="molestusV2.php">';
                        echo '<table class="data_table">';
                        echo "<tr>";
        
                        $_SESSION['select_information'] = [];
                        while($result = mysqli_fetch_array($select_information_results)){
                            $_SESSION['select_information'][] = $result;

                            if ($result['2'] == 'auto_increment') {
                            } else{
                                echo "<th>" . $result['COLUMN_NAME'] . "</th>";
                            }
                        }
        
                        echo "</tr>";
                        echo "<tr>";

                        foreach ($_SESSION['select_information'] as $result) {
                            if ($result['EXTRA'] == 'auto_increment') {} 
                            elseif (in_array($result['DATA_TYPE'], $number_type)) {
                                echo '<td><input min="0" type="number" class="input_in_table" name="' . $result['COLUMN_NAME'] . '" ></td>';
                            } elseif ($result['DATA_TYPE'] == 'date') {
                                echo '<td><input type="date" class="input_in_table" name="' . $result['COLUMN_NAME'] . '"></td>';
                            } elseif ($result['DATA_TYPE'] == 'time') {
                                echo '<td><input type="time" class="input_in_table" name="' . $result['COLUMN_NAME'] . '"></td>';
                            } elseif ($result['DATA_TYPE'] == 'year') {
                                echo '<td><input type="number" min="1901" max="2155" pattern="[0-9]{4}" class="input_in_table" name="' . $result['COLUMN_NAME'] . '"></td>';
                            } elseif ($result['DATA_TYPE'] == 'datetime') {
                                echo '<td><input type="datetime-local" class="input_in_table" name="' . $result['COLUMN_NAME'] . '"></td>';
                            } else {
                                echo '<td><input type="text" class="input_in_table" name="' . $result['COLUMN_NAME'] . '"></td>';
                            }
                        }
        
                        echo <<<END
        
                        </tr>
                        </table>
                        <br>
                        <br>
                        <input type="hidden" name="select_action" value="add_record">
                        <input type="submit" class="select_action_button" value="Dodaj rekord">
                        </form>
                        <br>

                        <form method="post" action="molestusV2.php">
                        <input type="hidden" name="select_action" value="Operacja na tabeli">
                        <input type="submit" class="select_action_button" value="Wróć">
                        </form>
        
                        END;
                        break;

                    case "add_record":

                        $primary_values = [];
                        $true = 0;

                        $tb_primary_key = "SELECT COLUMN_NAME  FROM information_schema.KEY_COLUMN_USAGE  WHERE TABLE_SCHEMA = '{$_SESSION['select_db']}'  AND TABLE_NAME = '{$_SESSION['select_tb']}'  AND CONSTRAINT_NAME = 'PRIMARY'";
                        $tb_primary_key_result = mysqli_query($login_id, $tb_primary_key);
                        $primary_key = mysqli_fetch_array($tb_primary_key_result);

                        if(!empty($primary_key[0])){
                            $tb_extra = "SELECT EXTRA  FROM information_schema.KEY_COLUMN_USAGE  WHERE TABLE_SCHEMA = '{$_SESSION['select_db']}'  AND TABLE_NAME = '{$_SESSION['select_tb']}'  AND COLUMN_NAME = '{$primary_key[0]}'";
                            $tb_extra_result = mysqli_query($login_id, $tb_primary_key);
                            $tb_extra = mysqli_fetch_array($tb_primary_key_result);
                            echo $tb_extra;
                        }

                        if(empty($primary_key[0])){
                            $true = 1;
                        }else if(!isset($_POST[$primary_key[0]])){
                            $true = 1;
                        }else if(empty($_POST[$primary_key[0]]) && $tb_extra[0] != 'auto_increment') {
                            $_SESSION['error'] = "Pole klucza głównego nie może być puste";
                            $_SESSION['action'] = "Dodaj rekord";
                            header("Location: molestusV2.php");
                            exit();                           
                        } else {
                            $tb_primary_values = "SELECT $primary_key[0] FROM " . $_SESSION['select_tb'];
                            $tb_primary_values_result = mysqli_query($login_id, $tb_primary_values);

                            while($result = mysqli_fetch_array($tb_primary_values_result)){
                                $primary_values[] = $result[0];
                            }

                            if(in_array($_POST[$primary_key[0]], $primary_values)){
                                $_SESSION['error'] = "Już istnieje pole klucza główngo o takich danych";
                                $_SESSION['action'] = "Dodaj rekord";
                                header("Location: molestusV2.php");
                                exit();
                            }else{
                                $true = 1;
                            }
                        }

                        if($true == 1){

                            $update_query = "INSERT INTO {$_SESSION['select_tb']} (";
                            $to_update = [];
            
                            foreach ($_SESSION['select_information'] as $result) {
                                if (empty($_POST[$result['COLUMN_NAME']])) {} 
                                else{
                                    $to_update[$result['COLUMN_NAME']] = $_POST[$result['COLUMN_NAME']];
                                }
                            }

                            foreach ($to_update as $column_name => $value) {
                                $update_query = $update_query . $column_name . ",";
                            }

                            if (substr($update_query, -1) === ',') {
                                $update_query = substr($update_query, 0, -1);
                            } 

                            $update_query = $update_query . ") VALUES (";

                            foreach ($to_update as $column_name => $value) {
                                $update_query = $update_query . "'" . $value . "',";
                            }

                            if (substr($update_query, -1) === ',') {
                                $update_query = substr($update_query, 0, -1);
                            } 

                            $update_query = $update_query . ")";
            
                            mysqli_query($login_id, $update_query);
                            
                            echo "<h1>Dodano rekord</h1>";
                        }

                        break;

                    case "Dodaj bazę":

                        echo<<<END
                        <h1>Dodawanie nowej baz danych</h1>
                        <br><br>
                        END;

                        if(isset($_SESSION['error'])){
                            echo '<p class="alert">' . $_SESSION['error'] .'</p>';
                            unset($_SESSION['error']);
                        }

                        echo<<<END
                        <br><br>
                        <form method="post" action="molestusV2.php">
                            <label for="new_database_name">Nazwa bazy danych: </label>
                            <input type="text" name="new_database_name" id="new_database_name" placeholder="Nowa_baza">
                            <br>
                            <br>
                            <label for="new_database_coding_method">Nazwa kolumny: </label>
                            <select name="new_database_coding_method" id="new_database_coding_method">
                                <option>Collation</option>
                                <optgroup label="armscii8">
                                    <option>armscii8_bin</option>
                                    <option>armscii8_general_ci</option>
                                    <option>armscii8_general_nopad_ci</option>
                                    <option>armscii8_nopad_bin</option>
                                </optgroup>
                                <optgroup label="ascii">
                                    <option>ascii_bin</option>
                                    <option>ascii_general_ci</option>
                                    <option>ascii_general_nopad_ci</option>
                                    <option>ascii_nopad_bin</option>
                                </optgroup>
                                <optgroup label="big5">
                                    <option>big5_bin</option>
                                    <option>big5_chinese_ci</option>
                                    <option>big5_chinese_nopad_ci</option>
                                    <option>big5_nopad_bin</option>
                                </optgroup>
                                <optgroup label="binary">
                                    <option>binary</option>
                                </optgroup>
                                <optgroup label="cp1250">
                                    <option>cp1250_bin</option>
                                    <option>cp1250_croatian_ci</option>
                                    <option>cp1250_czech_cs</option>
                                    <option>cp1250_general_ci</option>
                                    <option>cp1250_general_nopad_ci</option>
                                    <option>cp1250_nopad_bin</option>
                                    <option>cp1250_polish_ci</option>
                                </optgroup>
                                <optgroup label="cp1251">
                                    <option>cp1251_bin</option>
                                    <option>cp1251_bulgarian_ci</option>
                                    <option>cp1251_general_ci</option>
                                    <option>cp1251_general_cs</option>
                                    <option>cp1251_general_nopad_ci</option>
                                    <option>cp1251_nopad_bin</option>
                                    <option>cp1251_ukrainian_ci</option>
                                </optgroup>
                                <optgroup label="cp1256">
                                    <option>cp1256_bin</option>
                                    <option>cp1256_general_ci</option>
                                    <option>cp1256_general_nopad_ci</option>
                                    <option>cp1256_nopad_bin</option>
                                </optgroup>
                                <optgroup label="cp1257">
                                    <option>cp1257_bin</option>
                                    <option>cp1257_general_ci</option>
                                    <option>cp1257_general_nopad_ci</option>
                                    <option>cp1257_lithuanian_ci</option>
                                    <option>cp1257_nopad_bin</option>
                                </optgroup>
                                <optgroup label="cp850">
                                    <option>cp850_bin</option>
                                    <option>cp850_general_ci</option>
                                    <option>cp850_general_nopad_ci</option>
                                    <option>cp850_nopad_bin</option>
                                </optgroup>
                                <optgroup label="cp852">
                                    <option>cp852_bin</option>
                                    <option>cp852_general_ci</option>
                                    <option>cp852_general_nopad_ci</option>
                                    <option>cp852_nopad_bin</option>
                                </optgroup>
                                <optgroup label="cp866">
                                    <option>cp866_bin</option>
                                    <option>cp866_general_ci</option>
                                    <option>cp866_general_nopad_ci</option>
                                    <option>cp866_nopad_bin</option>
                                </optgroup>
                                <optgroup label="cp932">
                                    <option>cp932_bin</option>
                                    <option>cp932_japanese_ci</option>
                                    <option>cp932_japanese_nopad_ci</option>
                                    <option>cp932_nopad_bin</option>
                                </optgroup>
                                <optgroup label="dec8">
                                    <option>dec8_bin</option>
                                    <option>dec8_nopad_bin</option>
                                    <option>dec8_swedish_ci</option>
                                    <option>dec8_swedish_nopad_ci</option>
                                </optgroup>
                                <optgroup label="eucjpms">
                                    <option>eucjpms_bin</option>
                                    <option>eucjpms_japanese_ci</option>
                                    <option>eucjpms_japanese_nopad_ci</option>
                                    <option>eucjpms_nopad_bin</option>
                                </optgroup>
                                <optgroup label="euckr">
                                    <option>euckr_bin</option>
                                    <option>euckr_korean_ci</option>
                                    <option>euckr_korean_nopad_ci</option>
                                    <option>euckr_nopad_bin</option>
                                </optgroup>
                                <optgroup label="gb2312">
                                    <option>gb2312_bin</option>
                                    <option>gb2312_chinese_ci</option>
                                    <option>gb2312_chinese_nopad_ci</option>
                                    <option>gb2312_nopad_bin</option>
                                </optgroup>
                                <optgroup label="gbk">
                                    <option>gbk_bin</option>
                                    <option>gbk_chinese_ci</option>
                                    <option>gbk_chinese_nopad_ci</option>
                                    <option>gbk_nopad_bin</option>
                                </optgroup>
                                <optgroup label="geostd8">
                                    <option>geostd8_bin</option>
                                    <option>geostd8_general_ci</option>
                                    <option>geostd8_general_nopad_ci</option>
                                    <option>geostd8_nopad_bin</option>
                                </optgroup>
                                <optgroup label="greek">
                                    <option>greek_bin</option>
                                    <option>greek_general_ci</option>
                                    <option>greek_general_nopad_ci</option>
                                    <option>greek_nopad_bin</option>
                                </optgroup>
                                <optgroup label="hebrew">
                                    <option>hebrew_bin</option>
                                    <option>hebrew_general_ci</option>
                                    <option>hebrew_general_nopad_ci</option>
                                    <option>hebrew_nopad_bin</option>
                                </optgroup>
                                <optgroup label="hp8">
                                    <option>hp8_bin</option>
                                    <option>hp8_english_ci</option>
                                    <option>hp8_english_nopad_ci</option>
                                    <option>hp8_nopad_bin</option>
                                </optgroup>
                                <optgroup label="keybcs2">
                                    <option>keybcs2_bin</option>
                                    <option>keybcs2_general_ci</option>
                                    <option>keybcs2_general_nopad_ci</option>
                                    <option>keybcs2_nopad_bin</option>
                                </optgroup>
                                <optgroup label="koi8r">
                                    <option>koi8r_bin</option>
                                    <option>koi8r_general_ci</option>
                                    <option>koi8r_general_nopad_ci</option>
                                    <option>koi8r_nopad_bin</option>
                                </optgroup>
                                <optgroup label="koi8u">
                                    <option>koi8u_bin</option>
                                    <option>koi8u_general_ci</option>
                                    <option>koi8u_general_nopad_ci</option>
                                    <option>koi8u_nopad_bin</option>
                                </optgroup>
                                <optgroup label="latin1">
                                    <option>latin1_bin</option>
                                    <option>latin1_danish_ci</option>
                                    <option>latin1_general_ci</option>
                                    <option>latin1_general_cs</option>
                                    <option>latin1_german1_ci</option>
                                    <option>latin1_german2_ci</option>
                                    <option>latin1_nopad_bin</option>
                                    <option>latin1_spanish_ci</option>
                                    <option>latin1_swedish_ci</option>
                                    <option>latin1_swedish_nopad_ci</option>
                                </optgroup>
                                <optgroup label="latin2">
                                    <option>latin2_bin</option>
                                    <option>latin2_croatian_ci</option>
                                    <option>latin2_czech_cs</option>
                                    <option>latin2_general_ci</option>
                                    <option>latin2_general_nopad_ci</option>
                                    <option>latin2_hungarian_ci</option>
                                    <option>latin2_nopad_bin</option>
                                </optgroup>
                                <optgroup label="latin5">
                                    <option>latin5_bin</option>
                                    <option>latin5_nopad_bin</option>
                                    <option>latin5_turkish_ci</option>
                                    <option>latin5_turkish_nopad_ci</option>
                                </optgroup>
                                <optgroup label="latin7">
                                    <option>latin7_bin</option>
                                    <option>latin7_estonian_cs</option>
                                    <option>latin7_general_ci</option>
                                    <option>latin7_general_cs</option>
                                    <option>latin7_general_nopad_ci</option>
                                    <option>latin7_nopad_bin</option>
                                </optgroup>
                                <optgroup label="macce">
                                    <option>macce_bin</option>
                                    <option>macce_general_ci</option>
                                    <option>macce_general_nopad_ci</option>
                                    <option>macce_nopad_bin</option>
                                </optgroup>
                                <optgroup label="macroman">
                                    <option>macroman_bin</option>
                                    <option>macroman_general_ci</option>
                                    <option>macroman_general_nopad_ci</option>
                                    <option>macroman_nopad_bin</option>
                                </optgroup>
                                <optgroup label="sjis">
                                    <option>sjis_bin</option>
                                    <option>sjis_japanese_ci</option>
                                    <option>sjis_japanese_nopad_ci</option>
                                    <option>sjis_nopad_bin</option>
                                </optgroup>
                                <optgroup label="swe7">
                                    <option>swe7_bin</option>
                                    <option>swe7_nopad_bin</option>
                                    <option>swe7_swedish_ci</option>
                                    <option>swe7_swedish_nopad_ci</option>
                                </optgroup>
                                <optgroup label="tis620">
                                    <option>tis620_bin</option>
                                    <option>tis620_nopad_bin</option>
                                    <option>tis620_thai_ci</option>
                                    <option>tis620_thai_nopad_ci</option>
                                </optgroup>
                                <optgroup label="ucs2">
                                    <option>ucs2_bin</option>
                                    <option>ucs2_croatian_ci</option>
                                    <option>ucs2_croatian_mysql561_ci</option>
                                    <option>ucs2_czech_ci</option>
                                    <option>ucs2_danish_ci</option>
                                    <option>ucs2_esperanto_ci</option>
                                    <option>ucs2_estonian_ci</option>
                                    <option>ucs2_general_ci</option>
                                    <option>ucs2_general_mysql500_ci</option>
                                    <option>ucs2_general_nopad_ci</option>
                                    <option>ucs2_german2_ci</option>
                                    <option>ucs2_hungarian_ci</option>
                                    <option>ucs2_icelandic_ci</option>
                                    <option>ucs2_latvian_ci</option>
                                    <option>ucs2_lithuanian_ci</option>
                                    <option>ucs2_myanmar_ci</option>
                                    <option>ucs2_nopad_bin</option>
                                    <option>ucs2_persian_ci</option>
                                    <option>ucs2_polish_ci</option>
                                    <option>ucs2_roman_ci</option>
                                    <option>ucs2_romanian_ci</option>
                                    <option>ucs2_sinhala_ci</option>
                                    <option>ucs2_slovak_ci</option>
                                    <option>ucs2_slovenian_ci</option>
                                    <option>ucs2_spanish2_ci</option>
                                    <option>ucs2_spanish_ci</option>
                                    <option>ucs2_swedish_ci</option>
                                    <option>ucs2_thai_520_w2</option>
                                    <option>ucs2_turkish_ci</option>
                                    <option>ucs2_unicode_520_ci</option>
                                    <option>ucs2_unicode_520_nopad_ci</option>
                                    <option>ucs2_unicode_ci</option>
                                    <option>ucs2_unicode_nopad_ci</option>
                                    <option>ucs2_vietnamese_ci</option>
                                </optgroup>
                                <optgroup label="ujis">
                                    <option>ujis_bin</option>
                                    <option>ujis_japanese_ci</option>
                                    <option>ujis_japanese_nopad_ci</option>
                                    <option>ujis_nopad_bin</option>
                                </optgroup>
                                <optgroup label="utf16">
                                    <option>utf16_bin</option>
                                    <option>utf16_croatian_ci</option>
                                    <option>utf16_croatian_mysql561_ci</option>
                                    <option>utf16_czech_ci</option>
                                    <option>utf16_danish_ci</option>
                                    <option>utf16_esperanto_ci</option>
                                    <option>utf16_estonian_ci</option>
                                    <option>utf16_general_ci</option>
                                    <option>utf16_general_nopad_ci</option>
                                    <option>utf16_german2_ci</option>
                                    <option>utf16_hungarian_ci</option>
                                    <option>utf16_icelandic_ci</option>
                                    <option>utf16_latvian_ci</option>
                                    <option>utf16_lithuanian_ci</option>
                                    <option>utf16_myanmar_ci</option>
                                    <option>utf16_nopad_bin</option>
                                    <option>utf16_persian_ci</option>
                                    <option>utf16_polish_ci</option>
                                    <option>utf16_roman_ci</option>
                                    <option>utf16_romanian_ci</option>
                                    <option>utf16_sinhala_ci</option>
                                    <option>utf16_slovak_ci</option>
                                    <option>utf16_slovenian_ci</option>
                                    <option>utf16_spanish2_ci</option>
                                    <option>utf16_spanish_ci</option>
                                    <option>utf16_swedish_ci</option>
                                    <option>utf16_thai_520_w2</option>
                                    <option>utf16_turkish_ci</option>
                                    <option>utf16_unicode_520_ci</option>
                                    <option>utf16_unicode_520_nopad_ci</option>
                                    <option>utf16_unicode_ci</option>
                                    <option>utf16_unicode_nopad_ci</option>
                                    <option>utf16_vietnamese_ci</option>
                                </optgroup>
                                <optgroup label="utf16le">
                                    <option>utf16le_bin</option>
                                    <option>utf16le_general_ci</option>
                                    <option>utf16le_general_nopad_ci</option>
                                    <option>utf16le_nopad_bin</option>
                                </optgroup>
                                <optgroup label="utf32">
                                    <option>utf32_bin</option>
                                    <option>utf32_croatian_ci</option>
                                    <option>utf32_croatian_mysql561_ci</option>
                                    <option>utf32_czech_ci</option>
                                    <option>utf32_danish_ci</option>
                                    <option>utf32_esperanto_ci</option>
                                    <option>utf32_estonian_ci</option>
                                    <option>utf32_general_ci</option>
                                    <option>utf32_general_nopad_ci</option>
                                    <option>utf32_german2_ci</option>
                                    <option>utf32_hungarian_ci</option>
                                    <option>utf32_icelandic_ci</option>
                                    <option>utf32_latvian_ci</option>
                                    <option>utf32_lithuanian_ci</option>
                                    <option>utf32_myanmar_ci</option>
                                    <option>utf32_nopad_bin</option>
                                    <option>utf32_persian_ci</option>
                                    <option>utf32_polish_ci</option>
                                    <option>utf32_roman_ci</option>
                                    <option>utf32_romanian_ci</option>
                                    <option>utf32_sinhala_ci</option>
                                    <option>utf32_slovak_ci</option>
                                    <option>utf32_slovenian_ci</option>
                                    <option>utf32_spanish2_ci</option>
                                    <option>utf32_spanish_ci</option>
                                    <option>utf32_swedish_ci</option>
                                    <option>utf32_thai_520_w2</option>
                                    <option>utf32_turkish_ci</option>
                                    <option>utf32_unicode_520_ci</option>
                                    <option>utf32_unicode_520_nopad_ci</option>
                                    <option>utf32_unicode_ci</option>
                                    <option>utf32_unicode_nopad_ci</option>
                                    <option>utf32_vietnamese_ci</option>
                                </optgroup>
                                <optgroup label="utf8">
                                    <option>utf8_bin</option>
                                    <option>utf8_croatian_ci</option>
                                    <option>utf8_croatian_mysql561_ci</option>
                                    <option>utf8_czech_ci</option>
                                    <option>utf8_danish_ci</option>
                                    <option>utf8_esperanto_ci</option>
                                    <option>utf8_estonian_ci</option>
                                    <option>utf8_general_ci</option>
                                    <option>utf8_general_mysql500_ci</option>
                                    <option>utf8_general_nopad_ci</option>
                                    <option>utf8_german2_ci</option>
                                    <option>utf8_hungarian_ci</option>
                                    <option>utf8_icelandic_ci</option>
                                    <option>utf8_latvian_ci</option>
                                    <option>utf8_lithuanian_ci</option>
                                    <option>utf8_myanmar_ci</option>
                                    <option>utf8_nopad_bin</option>
                                    <option>utf8_persian_ci</option>
                                    <option>utf8_polish_ci</option>
                                    <option>utf8_roman_ci</option>
                                    <option>utf8_romanian_ci</option>
                                    <option>utf8_sinhala_ci</option>
                                    <option>utf8_slovak_ci</option>
                                    <option>utf8_slovenian_ci</option>
                                    <option>utf8_spanish2_ci</option>
                                    <option>utf8_spanish_ci</option>
                                    <option>utf8_swedish_ci</option>
                                    <option>utf8_thai_520_w2</option>
                                    <option>utf8_turkish_ci</option>
                                    <option>utf8_unicode_520_ci</option>
                                    <option>utf8_unicode_520_nopad_ci</option>
                                    <option>utf8_unicode_ci</option>
                                    <option>utf8_unicode_nopad_ci</option>
                                    <option>utf8_vietnamese_ci</option>
                                </optgroup>
                                <optgroup label="utf8mb4">
                                    <option>utf8mb4_bin</option>
                                    <option>utf8mb4_croatian_ci</option>
                                    <option>utf8mb4_croatian_mysql561_ci</option>
                                    <option>utf8mb4_czech_ci</option>
                                    <option>utf8mb4_danish_ci</option>
                                    <option>utf8mb4_esperanto_ci</option>
                                    <option>utf8mb4_estonian_ci</option>
                                    <option  selected>utf8mb4_general_ci</option>
                                    <option>utf8mb4_general_nopad_ci</option>
                                    <option>utf8mb4_german2_ci</option>
                                    <option>utf8mb4_hungarian_ci</option>
                                    <option>utf8mb4_icelandic_ci</option>
                                    <option>utf8mb4_latvian_ci</option>
                                    <option>utf8mb4_lithuanian_ci</option>
                                    <option>utf8mb4_myanmar_ci</option>
                                    <option>utf8mb4_nopad_bin</option>
                                    <option>utf8mb4_persian_ci</option>
                                    <option>utf8mb4_polish_ci</option>
                                    <option>utf8mb4_roman_ci</option>
                                    <option>utf8mb4_romanian_ci</option>
                                    <option>utf8mb4_sinhala_ci</option>
                                    <option>utf8mb4_slovak_ci</option>
                                    <option>utf8mb4_slovenian_ci</option>
                                    <option>utf8mb4_spanish2_ci</option>
                                    <option>utf8mb4_spanish_ci</option>
                                    <option>utf8mb4_swedish_ci</option>
                                    <option>utf8mb4_thai_520_w2</option>
                                    <option>utf8mb4_turkish_ci</option>
                                    <option>utf8mb4_unicode_520_ci</option>
                                    <option>utf8mb4_unicode_520_nopad_ci</option>
                                    <option>utf8mb4_unicode_ci</option>
                                    <option>utf8mb4_unicode_nopad_ci</option>
                                    <option>utf8mb4_vietnamese_ci</option>
                                </optgroup>
                            </select>
                            <br>
                            <br>
                            <input type="hidden" name="select_action" value="add_database">
                            <input type="submit" class="select_action_button" value="Stwórz nowa bazę">
                        </form>
            
                        <br>
                        <form method="post" action="molestusV2.php">
                            <input type="hidden" name="select_action" value="default">
                            <input type="submit" class="select_action_button" value="Wróć">
                        </form>
                                    
                        END;
                        break;

                    case "add_database":
            
                            $databases = [];

                            $show_databases = "SHOW databases";
                            $show_databases_result = mysqli_query($login_id, $show_databases);
            
                            while($result = mysqli_fetch_array($show_databases_result)){
                                $databases[] = $result[0];
                            }

                            if(empty($_POST['new_database_name'])){
                                $_SESSION['error'] = "Pole nazwy nie może być puste";
                                $_SESSION['action'] = "Dodaj bazę";
                                header("Location: molestusV2.php");
                                exit();
                            }else if(in_array($_POST['new_database_name'], $databases)){
                                $_SESSION['error'] = "Baza danych o takiej nazwie już istnieje";
                                $_SESSION['action'] = "Dodaj bazę";
                                header("Location: molestusV2.php");
                                exit();
                            }else{

                                $floor_position = strpos($_POST['new_database_coding_method'], '_');
                                $character = substr($_POST['new_database_coding_method'], 0, $floor_position);
                                
                                $create_db = "CREATE DATABASE {$_POST['new_database_name']} CHARACTER SET {$character} COLLATE {$_POST['new_database_coding_method']}";
                                $create_db = mysqli_query($login_id, $create_db);
                                $_SESSION['action'] = "default";
                                header("Location: molestusV2.php");
                                exit();

                            }

                        break;
                    case "Dodaj tabelę":

                        echo<<<END
                        <h1>Dodawanie nowej tabeli</h1>
                        <br><br>
                        END;
    
                        if(isset($_SESSION['error'])){
                            echo '<p class="alert">' . $_SESSION['error'] .'</p>';
                            unset($_SESSION['error']);
                        }

                        if (isset($_POST['add_row'])) {
                            $row_count = count($_POST['column_name_to_add']) + 1;
                        } else {
                            $row_count = 1;
                        }

                        echo '
                        <form method="post" action="molestusV2.php">
                            <label for="table_name_to_add">Nazwa nowej tabeli: </label>
                            <input type="text" name="table_name_to_add" id="table_name_to_add" value="' . (isset($_POST['table_name_to_add']) ? $_POST['table_name_to_add'] : '') . '">
                            <br><br>
                            <button type="submit" class="select_action_button" name="add_row">Dodaj kolejny wiersz</button>
                            <br><br>
                            <table class="data_table">
                            <tr>
                                <th>Nazwa kolumny</th>
                                <th>Typ danych</th>
                                <th title="Długośc jest wymagana w tych typach danych: varchar, char, binary, varbinary">Długość (jeśli potrzebna)</th>
                                <th>Wartość domyslna</th>
                                <th>Czy null</th>
                                <th>Dodatkowe</th>
                            </tr>
                        ';

                        for ($i = 0; $i < $row_count; $i++) {

                            echo '
                            <tr>
                                <td>
                                    <input type="text" class="input_in_table" name="column_name_to_add[]" id="column_name_to_add" value="' . (isset($_POST['column_name_to_add'][$i]) ? $_POST['column_name_to_add'][$i] : '') . '">
                                </td>
                            
                                <td>
                                    <select name="column_type_to_add[]" class="input_in_table" id="column_type_to_add">
                                        <optgroup>
                                            <option>' . (isset($_POST['column_type_to_add'][$i]) ? $_POST['column_type_to_add'][$i] : 'INT') . '</option>
                                            <option>INT</option>
                                            <option>VARCHAR</option>
                                            <option>CHAR</option>
                                            <option>TEXT</option>
                                            <option>DATE</option>
                                        </optgroup>
                                        <optgroup>
                                            <option>TINYINT</option>
                                            <option>SMALLINT</option>
                                            <option>MEDIUMINT</option>
                                            <option>INT</option>
                                            <option>BIGINT</option>
                                            <option>DECIMAL</option>
                                            <option>FLOAT</option>
                                            <option>DOUBLE</option>
                                            <option>REAL</option>
                                            <option>BIT</option>
                                            <option>BOOLEAN</option>
                                        </optgroup>
                                        <optgroup>
                                            <option>DATE</option>
                                            <option>DATETIME</option>
                                            <option>TIMESTAMP</option>
                                            <option>TIME</option>
                                        </optgroup>
                                        <optgroup>
                                            <option>CHAR</option>
                                            <option>VARCHAR</option>
                                            <option>TINYTEXT</option>
                                            <option>TEXT</option>
                                            <option>MEDIUMTEXT</option>
                                            <option>LONGTEXT</option>
                                            <option>BINARY</option>
                                            <option>VARBINARY</option>
                                            <option>TINYBLOB</option>
                                            <option>BLOB</option>
                                            <option>MEDIUMBLOB</option>
                                            <option>LONGBLOB</option>
                                            <option>ENUM</option>
                                        </optgroup>
                                    </select>
                                </td>

                                <td>
                                    <input type="number" class="input_in_table" min="1" name="column_width_to_add[]" value="' . (isset($_POST['column_width_to_add'][$i]) ? $_POST['column_width_to_add'][$i] : '') . '">
                                </td>

                                <td>
                                    <input type="text" class="input_in_table" name="column_default_to_add[]" value="' . (isset($_POST['column_default_to_add'][$i]) ? $_POST['column_default_to_add'][$i] : '') . '">
                                </td>

                                <td>
                                    <select name="column_null_to_add[]" class="input_in_table" value="' . (isset($_POST['column_null_to_add'][$i]) ? $_POST['column_null_to_add'][$i] : '') . '">
                                        <option>NULL</option>
                                        <option>NOT NULL</option>
                                    </select>
                                </td>

                                <td>
                                    <select name="column_key_to_add[]" class="input_in_table">
                                        <option>' . (isset($_POST['column_key_to_add'][$i]) ? $_POST['column_key_to_add'][$i] : '') . '</option>
                                        <option>PRIMARY KEY</option>
                                        <option>UNIQUE</option>
                                    </select>
                                </td>
                            </tr>
                            ';
                        }
                        
                        echo<<<END
                            </table>
                            <br>
                            <input type="submit" class="select_action_button" name="select_action" value="Utwórz tabelę">
                        </form><br>
                        
                        <form method="post" action="molestusV2.php">
                            <input type="hidden" name="select_action" value="default">
                            <input type="submit" class="select_action_button" value="Wróć">
                        </form>
                        END;
      
                        break;

                    case "Utwórz tabelę":
                        if(!empty($_SESSION['select_db'])){
                            if (!empty($_POST['table_name_to_add'])) {
                                $create_table_query = "CREATE TABLE {$_POST['table_name_to_add']} (";
                                $row_count = count($_POST['column_name_to_add']);
                                $is_primary_key = false;
                                $column_names = [];
                                $tables_names = [];

                                $show_tables = "SHOW tables";
                                $show_tables_result = mysqli_query($login_id, $show_tables);
                
                                while($result = mysqli_fetch_array($show_tables_result)){
                                    $tables_names[] = $result[0];
                                }
                            
                                if(!in_array($_POST['table_name_to_add'], $tables_names)){
                                    for ($i = 0; $i < $row_count; $i++) {
                                        $column_name = isset($_POST['column_name_to_add'][$i]) ? $_POST['column_name_to_add'][$i] : '';
                                        $column_type = isset($_POST['column_type_to_add'][$i]) ? $_POST['column_type_to_add'][$i] : '';
                                        $column_width = isset($_POST['column_width_to_add'][$i]) ? $_POST['column_width_to_add'][$i] : '';
                                        $column_default = isset($_POST['column_default_to_add'][$i]) ? $_POST['column_default_to_add'][$i] : '';
                                        $column_null = isset($_POST['column_null_to_add'][$i]) ? $_POST['column_null_to_add'][$i] : '';
                                        $column_key = isset($_POST['column_key_to_add'][$i]) ? $_POST['column_key_to_add'][$i] : '';
                                
                                        if (!empty($column_name)) {
                                            $create_table_query .= "$column_name $column_type";
                                            $column_names[] = $column_name;
                                
                                            if (!empty($column_width) && !in_array($column_type, array('DATE', 'DATETIME', 'TIMESTAMP', 'TIME'))) {
                                                $create_table_query .= "(" . $column_width . ")";
                                            }
                                
                                            if (empty($column_key)) {
                                                $create_table_query .= " $column_null";
                                            }
                                
                                            if (!empty($column_default) && empty($column_key)) {
                                                $create_table_query .= " DEFAULT '" . $column_default . "'";
                                            }
                                
                                            if (!empty($column_key) && !$is_primary_key) {
                                                $create_table_query .= " $column_key";
                                                if ($column_key == "PRIMARY KEY") {
                                                    $is_primary_key = true;
                                                }
                                            }
                                
                                            $create_table_query .= ", ";
                                        }
                                    }
                                
                                    $create_table_query = rtrim($create_table_query, ', ');
                                
                                    $create_table_query .= ")";

                                    if (count($column_names) == 0) {
                                        $_SESSION['error'] = "Tabela musi mieć co najmniej 1 kolumnę";
                                        $_SESSION['action'] = "Dodaj tabelę";
                                        header("Location: molestusV2.php");
                                        exit();
                                    }else{
                                        mysqli_query($login_id, $create_table_query);
                                        echo "<h1>Stworzono nowa tabelę {$_POST['table_name_to_add']} w bazie {$_SESSION['select_db']}</h1>";
                                    }
                                }else{
                                    $_SESSION['error'] = "Tabela o takiej nazwie już istnieje";
                                    $_SESSION['action'] = "Dodaj tabelę";
                                    header("Location: molestusV2.php");
                                    exit();
                                }
                                
                            } else {
                                $_SESSION['error'] = "Nie podano nazwy tabeli";
                                $_SESSION['action'] = "Dodaj tabelę";
                                header("Location: molestusV2.php");
                                exit();
                            }
                        }else {
                            $_SESSION['error'] = "Nie wybrano baz danych";
                            $_SESSION['action'] = "Dodaj tabelę";
                            header("Location: molestusV2.php");
                            exit();
                        }
                        break;

                    case "SQL":

                        echo<<<END
                        <h1>Wykonywanie poleceń SQL</h1>
                        END;
                        
                        if(isset($_SESSION['error'])){
                            echo '<p class="alert">' . $_SESSION['error'] .'</p>';
                            unset($_SESSION['error']);
                        }

                        echo<<<END
                        <form method="post" action="molestusV2.php">
                            <textarea class="sql_code" id="sql_code" name="sql_code" placeholder="SELECT * FROM ..."></textarea>
                            <br>
                            <input type="hidden" name="select_action" value="sql_execute">
                            <input type="submit" class="select_action_button" value="Wykonaj zapytanie">
                        </form>

                        <br>

                        <div class="sql_code">
                        END;

                        if(isset($_SESSION['sql_query'])){
                            if(count($_SESSION['sql_query']) != 0){
                                for($i = count($_SESSION['sql_query']) -1; $i >= 0; $i--){
                                    echo $_SESSION['sql_query'][$i];
                                    echo "<br>";
                                    echo "<br>";
                                }
                            }
                        }

                        echo "</div>";

                        break;

                    case "sql_execute":

                        if(empty($_SESSION['select_db']) && strpos($_POST['sql_code'], 'create database') !== 0){
                            $_SESSION['error'] = "Nalezy wybrać bazę danych z listy";
                            $_SESSION['action'] = "SQL";
                            header("Location: molestusV2.php");
                            exit();
                        }

                        if (!empty($_POST['sql_code'])) {
                            $_SESSION['sql_query'][] = '<div class="div_left">' . $_POST['sql_code'] . '</div>';
                        
                            $query_output = '';
                        
                            try {
                                $sql_query_results = mysqli_query($login_id, $_POST['sql_code']);
                            
                                if ($sql_query_results !== false) {
                                    if (mysqli_field_count($login_id) > 0) {
                                        $query_output .= '<div class="div_right"><table class="data_table">';
                                        $query_output .= "<tr>";
                            
                                        $column_names = mysqli_fetch_fields($sql_query_results);
                                        foreach ($column_names as $column_name) {
                                            $query_output .= "<th>" . $column_name->name . "</th>";
                                        }
                            
                                        $query_output .= "</tr>";
                            
                                        while ($results = mysqli_fetch_array($sql_query_results, MYSQLI_ASSOC)) {
                                            $query_output .= "<tr>";
                            
                                            foreach ($results as $result) {
                                                $query_output .= "<td>" . $result . "</td>";
                                            }
                            
                                            $query_output .= "</tr>";
                                        }
                            
                                        $query_output .= "</table></div>";
                                    } else {
                                        $query_output = '<div class="div_right">Zapytanie wykonane poprawnie</div>';
                                    }
                                } else {
                                    throw new Exception("Błąd zapytania");
                                }
                            } catch (Exception $e) {
                                $query_output = '<div class="div_right">' . $e->getMessage() . '</div>';
                            }
                        
                            $_SESSION['sql_query'][] = $query_output;
                        } else {
                            $_SESSION['sql_query'][] = '<div class="div_right">Nie przesłano zapytania SQL</div>';
                        }

                        $_SESSION['action'] = "SQL";
                        header("Location: molestusV2.php");
                        exit();

                        break;

                    default:
                        echo '<img src="logo_molestus.png" alt="logo molestus" id="logo_main">';
                        echo '<h1>MOLESTUS</h1>';
                }
            }else{
                echo '<img src="logo_molestus.png" alt="logo molestus" id="logo_main">';
                echo '<h1>MOLESTUS</h1>';
            }

            echo<<<END
                </div>
            </main>
            END;

            mysqli_close($login_id);

        }
        else {
                
            $error = "";
    
            if(isset($_SESSION['login_error'])){
                $error = '<p id="login_error">' . $_SESSION['login_error'] . "</p>";
            }
    
            echo<<<END
    
            <div id="login_field">
                <h1>molestus</h1>
                <hr>
    
                <img src="logo_molestus.png" alt="Logo molestus">
    
                <form action="molestusV2.php" method="post" id="login_form">
    
                    <label for="login">Nazwa użytkownika: </label>
                    <input type="text" name="login" id="login"><br><br>
    
                    <label for="password">Hasło: </label>
                    <input type="password" name="password" id="password"><br><br>
    
                    <input type="submit" id="login_from_submit" value="Zaloguj się">
                    $error
                </form>
            </div>
                        
            END;
        }
    ?> 

    </body>
</html>
 