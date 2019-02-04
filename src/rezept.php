<?php
/**
 * Created by PhpStorm.
 * User: Salchegger Robert
 * Date: 17.01.2019
 * Time: 08:08
 */

if(isset($_POST['insert1']))
{
    $anzahl = $_POST['anzahl'];

    ?>
    <form method="post">
    <div class="table">
        <div class="tr">
            <div class="th">
                <label for="rez">Rezeptname:</label>
            </div>
            <div class="td">
                <input class="rezept_name" type="text" id="rez" name="rezname" placeholder="z.B. Warmer Apfelstrudel" required>
            </div>
        </div>
        <div class="tr">
            <div class="th">
                <label for="rez">Zubereitung:</label>
            </div>
            <div class="td">
                <textarea class="rezept_textarea" name="zubereitung"></textarea>
            </div>
        </div>
        <?php
        $query = 'select zutat_einheit.zuei_id, zutat.zut_name, einheit.ein_name from zutat_einheit natural join (zutat, einheit)';
        $stmt = $con->GetStatement($query);


        $rowAgain = array();
        while($row = $stmt->fetch(PDO::FETCH_NUM))
        {
            $rowAgain[] = $row;
        }

        $cC = $stmt->columnCount();
        $rC = sizeof($rowAgain);

        for($i = 0; $i < $anzahl; $i++) {
            ?>
            <div class="tr">
                <div class="th">
                    <label>Menge</label>
                </div>
                <div class="td">
                    <input class="menge" type="number" name="menge[]" placeholder="Wert eingeben" min="0" max="999">
                </div>
                <div class="td">
                    <select class="zutateneinheit" name="zutateinheit[]">
                        <?php
                        for($j = 0; $j < $rC; $j++)
                        {
                            echo '<option value="'.$rowAgain[$j][0].'">'.$rowAgain[$j][2].' '.$rowAgain[$j][1];
                        }
                        ?>
                    </select>
                </div>
            </div>
        <?php
        }
        ?>
        <div class="tr">
            <div class="td">
                <?php echo '<input type="hidden" name="anzahl" value="'.$anzahl.'">'; ?>
                <input type="submit" name="insert2" value="Rezept speichern">
            </div>
        </div>
    </div>
    </form>
    <?php
} else if(isset($_POST['insert2']))
{

    $rezname = $_POST['rezname'];
    $menge = $_POST['menge'];
    $anzahl = $_POST['anzahl'];
    $zutateinheit = $_POST['zutateinheit'];
    $zubereitung = $_POST['zubereitung'];
    $pos = 0;
    while($pos < $anzahl)
    {
        if($menge[$pos] == '' || $menge[$pos] < 1)
        {
            $pos = $anzahl;
            echo '<h4>Sie haben nicht für jede Zutat eine Menge angegeben!</h5><a href="javascript:history.back()">zurück</a> ';
            exit();
        }
        $pos++;
    }
    try {
        // zuerst ermitteln, ob rezeptname bereits erfasst wurde ... ID auslesen!
        $query = 'select rez_id from rezeptname where rez_name = ?';
        $rezArray = array($rezname);
        $stmt = $con->GetStatement($query, $rezArray);
        $rezid = $stmt->fetch(PDO::FETCH_NUM);
        $lastID = $rezid[0];
        if($rezid <= 0) {
            $query = 'insert into rezeptname (rez_name) values(?)';

            $stmt = $con->GetStatement($query, $rezArray);
            $lastID = $con->GetLastInsertID();
        }
        $query = 'insert into zubereitung (zub_beschreibung, rez_id) values(?, ?)';
        $rezArray = array($zubereitung, $lastID);
        $stmt = $con->GetStatement($query, $rezArray);
        $lastIDZubereitung = $con->GetLastInsertID();

        for($i = 0; $i < $anzahl; $i++) {
            $query = 'insert into zubereitung_einheit values(?, ?, ?)';
            $zubereitungeinheit = array($lastIDZubereitung, $zutateinheit[$i], $menge[$i]);

            $stmt = $con->GetStatement($query, $zubereitungeinheit);
        }
        echo '<h5>Rezept <b>'.$rezname.'</b> wurde erfolgreich erfasst!</h5><br>';

    } catch(Exception $e)
    {
        if($e->getCode() == '23000')
        {
            echo '<h5>Insert Exception: Sie haben versucht bei den Zutaten die gleiche Zutat mit anderer Menge zu erfassen!</h5><br>'.$e->getMessage();
        } else {
            echo $e->getMessage();
        }
    }

} else {
    ?>
<br>
<h3>Neue Rezepte erfassen</h3>
<h5>Geben Sie zuerst an wie viele Zutaten das Rezept braucht:</h5>
<form method="post">
    <div class="table">
        <div class="tr">
            <div class="th">
                <label for="anz">Anzahl:</label>
            </div>
        <div class="td">
            <input type="number" id="anz" name="anzahl" placeholder="mind. 1"  min="1" max="5">
        </div>
        </div>
        <div class="tr">
            <div class="td">
                <input type="submit" name="insert1" value="zum Erfassen">
            </div>
        </div>
    </div>
</form>
<?php
}
